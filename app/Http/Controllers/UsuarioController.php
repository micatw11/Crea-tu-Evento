<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UsuarioRequest;
use Illuminate\Http\Request;

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
        $img = $request->avatar;
        $file = base64_decode($img);
        $filename  = time() . '.' . $file->getClientOriginalExtension();
        $path = 'avatars/'.$id.'/'.$filename;
        $path = Storage::put($path, $file);
        //$usuario->avatar = $path;
        $string = '<img src="'.$path.'"/>';
        return response()->json(['data' => $string ]);
    }
}
