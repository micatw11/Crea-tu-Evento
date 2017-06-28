<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProveedorRequest;
use Illuminate\Support\Facades\Storage;
use App\Proveedor;
use App\Rol;
use App\Domicilio;
use App\Rubro;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Proveedor::with('user.usuario', 'domicilio');

        if($request->filter){
            $like = '%'.$request->filter.'%';
            $query->where('nombre','like', $like )
                    ->orWhere('email', 'like', $like);
        }

        $proveedores = $query->paginate(10);
        return response()->json($proveedores);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $this->validatorDomicilio($request);
        $this->validatorProveedor($request);
        $domicilio= $this->createDomicilio($request);
        $proveedor= $this->createProveedor($request,$domicilio);
        
        if (($proveedor)&&($domicilio)){
            return response()->json(['data' => 'OK'], 200);
        
        } else {
            return response()->json([
                'error' => 'Unauthorized', 'proveedor' => $proveedor
            ], 401);
        }
    }

    protected function validatorProveedor(Request $request)
    {
      return $this->validate($request, 
        [
            'user_id' => 'required|exists:usuarios,id',
            'nombre' => 'required|min:4|max:55',
            'cuit' => 'required|min:9|max:11',
            'ingresos_brutos' => 'required|min:5|max:10',
            'email' => 'required|email',
            'dni' => 'required'
        ]);
    }

    public function createProveedor(Request $request, $domicilio)
    {
        
        $img = $request->dni;
        $extension = null;

        if(strstr($img, 'data:image/jpeg;base64,'))
        {
            $img = str_replace('data:image/jpeg;base64,', '', $img);
            $extension = 'jpeg';
        }
        else if(strstr($img, 'data:image/jpg;base64,'))
        {
            $img = str_replace('data:image/jpg;base64,', '', $img);
            $extension = 'jpg';
        }
        else if(strstr($img, 'data:application/pdf;base64,'))
        {
            $img = str_replace('data:application/pdf;base64,', '', $img);
            $extension = 'pdf';
        }
        else 
        {
            $img = str_replace('data:image/png;base64,', '', $img);
            $extension = 'png';
        }

        $file = base64_decode($img);
        $filename  = str_random(30) . '.'.$extension;
        Storage::put('public/proveedores/'.$filename, $file);

        return Proveedor::create([
                    'user_id' => $request->user_id,
                    'nombre' => $request->nombre,
                    'cuit' => $request->cuit,
                    'ingresos_brutos' => $request->ingresos_brutos,
                    'email' => $request->email,
                    'estado' => "Tramite",
                    'domicilio_id' => $domicilio->id,
                    'dni' => $filename]);
    }

    protected function validatorDomicilio(Request $request)
    {
      return $this->validate($request, 
        [
            'calle'=>'required|min:4|max:55',
            'numero'=> 'required|min:1|max:10',
            'piso'=> 'min:1|max:10',
            'localidad_id'=> 'required|exists:localidades,id'
        ]);
    }

    public function createDomicilio(Request $request)
    {
        return Domicilio::create([
                    'calle'=> $request->calle,
                    'numero'=> $request->numero,
                    'piso'=> $request->piso,
                    'localidad_id'=> $request->localidad_id
            ]);
    }

    protected function validatorRubro(Request $request)
    {
      return $this->validate($request, 
        [
            'categoria_id',
            'denominacion',
            'fecha_habilitacion',
            'habilitacion',
        ]);
    }

    public function createRubro(Request $request,$proveedor)
    {
        return Rubro::create([
                    'proveedor_id'=> $proveedor->id,
                    'categoria_id'=> $request->categoria_id,
                    'denominacion'=> $request->denominacion,
                    'fecha_habilitacion'=> $request->fecha_habilitacion,
                    'habilitacion'=> $request->habilitacion
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function cambiarEstado(Request $request, $id){

        $proveedor = Proveedor::where('user_id', $id)->with('user')->firstOrFail();

        if($request->action == 'Baja'){
            $proveedor->user->roles_id = Rol::roleId('Usuario');
        } 
        else if ( $request->action == 'Aprobado' && $proveedor->user->roles_id == Rol::roleId('Usuario') ) 
        {
            $proveedor->user->roles_id = Rol::roleId('Proveedor');
        }
        else {
            return response()->json(['error' =>  'Bad Request'], 400);
        }

        $proveedor->estado = $request->input('action');


        if($proveedor->user->save() && $proveedor->save()){
            return response()->json(['data' =>  'OK', 'proveedor' => $proveedor], 200);
        } else {
            return response()->json(['error' =>  'Internal Server Error'], 500);
        }
    }

    public function PerfilProveedor (Request $request, $id){
        $proveedor = Proveedor::where('user_id', $id)
                              ->with('user.usuario', 'domicilio')->firstOrFail();
    
        if($proveedor){
            return response()->json(['data' =>  $proveedor], 200);
        } else {
            return response()->json(['error' =>  'Internal Server Error' , 'request' => $request ], 500);
        }
    }
}
