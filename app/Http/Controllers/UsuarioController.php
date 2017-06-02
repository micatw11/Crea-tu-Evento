<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UsuarioRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Http\Response;
use App\Usuario;


class UsuarioController extends Controller
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
    public function store(UsuarioRequest $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $usuario = Usuario::where('user_id', $id)->with('localidad.provincia')->firstOrFail();
        return response()->json(['data' =>  $usuario]);
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
    public function update(UsuarioRequest $request, $id)
    {
        $usuario = Usuario::where('user_id', $id)->firstOrFail();
        $usuario->update($request->all());
        if($usuario->save()){
            return response()->json(['data' =>  'OK'], 200);
        } else {
            return response()-json(['error' => 'Internal Server Error'], 500 );
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

    public function updateAvatar(Request $request, $id){

        $usuario = Usuario::where('user_id', $id)->firstOrFail();

        //Se guarda el avatar en el almacenamiento 
        $filename = $this->saveAvatar($request);

        //Se elimina el anterior avatar del almacenamiento 
        $this->deleteAvatar($usuario);

        $usuario->avatar = $filename;

        if($usuario->save()){
            return response()->json(['data' => $filename ], 200);
        } else {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }


    protected function deleteAvatar(Usuario $usuario){
        $currentAvatar = $usuario->avatar;

        if($currentAvatar) {
            $file = "public/avatars/{$currentAvatar}";

            if(Storage::exists($file)) {
                Storage::delete($file);
            }
        }
    }

    protected function saveAvatar($request){

        $img = $request->avatar;
        $img = str_replace('data:image/png;base64,', '', $img);
        $file = base64_decode($img);
        $filename  = str_random(30) . '.'.'jpg';
        Storage::put('public/avatars/'.$filename, $file);

        return $filename;
    }
}
