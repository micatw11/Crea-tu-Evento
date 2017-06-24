<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProveedorRequest;
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
    public function store(ProveedorRequest $request)
    {   
        $domicilio= $this->createDomicilio($request);
        $proveedor= Proveedor::create([
                    'user_id' => $request->user_id,
                    'nombre' => $request->nombre,
                    'cuit' => $request->cuit,
                    'ingresos_brutos' => $request->ingresos_brutos,
                    'email' => $request->email,
                    'estado' => "Tramite",
                    'domicilio_id' => $domicilio->id]);
       
        $rubro= Rubro::create([
                    'proveedor_id'=> $proveedor->id,
                    'categoria_id'=> $request->rubro_categoria_id,
                    'denominacion'=> $request->rubro_denominacion,
                    'fecha_habilitacion'=> $request->rubro_fecha_habilitacion,
                    'habilitacion'=> $request->rubro_habilitacion
            ]);

        $domicilioRubro= $this->createDomicilio($request->rubro_domicilio);

        if (($proveedor)&&($domicilio)&&$rubro){
            return response()->json(['data' => 'OK'], 200);
        
        } else {
            return response()->json([
                'error' => 'Unauthorized', 'proveedor' => $proveedor
            ], 401);
        }
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

        if($request->has('action') === 'Baja'){
            $proveedor->user->roles_id = Rol::roleId('Usuario');
        } 
        else if ( $request->has('action') === 'Aprobado' && $proveedor->user->roles_id == Rol::roleId('Usuario') ) 
        {
            $proveedor->user->roles_id = Rol::roleId('Proveedor');
        }
        $proveedor->estado = $request->input('action');


        if($proveedor->save()){
            return response()->json(['data' =>  'OK'], 200);
        } else {
            return response()->json(['error' =>  'Internal Server Error' , 'request' => $request ], 500);
        }
    }
}
