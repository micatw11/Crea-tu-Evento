<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Reserva;
use App\Mensaje;

class MensajesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
            $ancestrosId = DB::table('mensajes')
                    ->join('users', 'mensajes.from_user_id', '=', 'users.id')
                    ->join('usuarios', 'usuarios.user_id', '=', 'users.id')
                    ->where('mensajes.estado', 1)
                    ->where('from_user_id', Auth::id())
                    ->orWhere('to_user_id', Auth::id())
                    ->whereNotNull('mensajes.ancestro_id')
                    ->groupBy('mensajes.reserva_id')
                    ->select(DB::raw('MAX(mensajes.ancestro_id) as ancestro_id'))->pluck('ancestro_id');
            
            $mensajes = Mensaje::select('mensajes.*')->whereIn('mensajes.ancestro_id', $ancestrosId)
                ->with('fromUser.usuario', 'toUser.usuario', 'reserva.publicacion')->orderBy('created_at', 'asc')->paginate(10);

                   

        return response()->json($mensajes, Response::HTTP_OK);
    }
//SELECT MAX(ancestro_id), mensajes.* FROM `mensajes` WHERE ancestro_id IS NOT NULL GROUP BY reserva_id
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $form_user_id = Auth::id();
        $to_user_id = null;

        $mensajeAnterior = Mensaje::where('id', $request->prev_message_id)->firstOrFail();

        if($mensajeAnterior->to_user_id == $form_user_id){
            $to_user_id = $mensajeAnterior->from_user_id;
        }
        else
        {
            $to_user_id = $mensajeAnterior->to_user_id;
        }

        $newMensaje = Mensaje::create([ 'from_user_id' => Auth::id(), 'to_user_id' => $to_user_id, 
                        'ancestro_id' => $mensajeAnterior->id, 'mensaje' => $request->mensaje, 
                        'reserva_id' => $mensajeAnterior->reserva_id]);
        if( $newMensaje->save() ){
            return response(null, Response::HTTP_OK);
        }
        else
        {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mensaje = Mensaje::where('id', $id)->firstOrFail();
        $reserva = Reserva::where('id', $mensaje->reserva_id)->where('estado', 'presupuesto')
                        ->with('publicacion','articulos', 'rubros')->first();

        Mensaje::where('reserva_id', $mensaje->reserva_id)->update(['lectura' => true]);


        $mensajes = Mensaje::where('reserva_id', $mensaje->reserva_id)
                        ->where('estado', 1)
                        ->with('fromUser.usuario', 'toUser.usuario')->get();

        return response()->json(['mensajes' => $mensajes, 'presupuesto' => $reserva], Response::HTTP_OK);
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
}
