<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Services\ProveedorService;
use App\Proveedor;
use App\Domicilio;
use App\RubrosDetalle;
use App\Log;

class RubrosDetalleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,$proveedor,$domicilio)
    {
        return RubrosDetalle::create([
                    'proveedor_id'=> $proveedor->id,
                    'rubro_id'=> $request->rubro_id,
                    'habilitacion'=> $request->habilitacion,
                    'domicilio_id'=> $domicilio->id,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $datosCargados= true;
        $proveedor = Proveedor::where('user_id', $id)->firstOrFail();
        $this->validatorRubro($request);
        if ($request->comercio){
            $this->validatorDomicilio($request);
            $domicilio= $this->createDomicilio($request);
            $rubro= $this->create($request,$proveedor, $domicilio);
        }
        else{
            $domicilio= Domicilio::where('id', $proveedor->domicilio_id)->firstOrFail();
            $rubro= $this->create($request,$proveedor, $domicilio);
        }
        if (($rubro)&&($domicilio)){
                $datosCargados= true;
        }
        if ($rubro){
                $datosCargados= true;
        }
        if ($datosCargados){
            return response()->json(['data' => 'OK'], 200);
        
        } else {
            return response()->json([
                'error' => 'Unauthorized', 'rubro' => $rubro
            ], 401);
        }
    }

        /**
     * @param $request
     */
    protected function validatorDomicilio(Request $request)
    {
      return $this->validate($request, 
        [
            'calle'=>'max:55',
            'numero'=> 'max:10',
            'piso'=> 'max:10',
            'localidad_id'=> 'exists:localidades,id'
        ]);
    }

           /**
     * @param $request
     */
    public function createDomicilio(Request $request)
    {
        return Domicilio::create([
                    'tipo_domicilio'=>'Social',
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
            'rubro_id' =>'required',
            'fecha_habilitacion'=>'date',
            'habilitacion'=>'max:55',
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
        $rubro= RubrosDetalle::where('id', $id)->with('domicilio.localidad.provincia')->firstOrFail();

        if ($rubro) {
            return response()->json(['data' => $rubro], 200);
        } else {
            return response()->json(['error' =>  'Internal Server Error'], 500);
        }
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
        $this->validatorDomicilio($request);
        $this->validatorRubro($request);
        //$table_name= "rubro";
        //$accion = "update";
        $rubro = RubrosDetalle::where('id', $id)->firstOrFail();
        $domicilio= Domicilio::where('id', $rubro->domicilio_id)->firstOrFail();
        //Log::logs($id, $table_name, $accion , $rubro, 'Ha actualizado informacion personal');
        $rubro->update($request->all());
        $domicilio->update($request->all());
        if($rubro->save()&& $domicilio->save()){
            return response()->json(['data' =>  'OK'], 200);
        } else {
            return response()->json(['error' => 'Internal Server Error'], 500 );
        }
    
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

    public function getAll(Request $request, $id){
        $proveedor = Proveedor::where('user_id', $id)->firstOrFail();

        $rubros = RubrosDetalle::where('proveedor_id', $proveedor->id)
            ->with('domicilio.localidad.provincia', 'rubro.subcategoria.categoria')->get();
        return response()->json(['rubros' => $rubros], 200);
    }


}
