<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ControllerUsuario extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Usuario::all();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
    $messages = [
        'nombre.required' => 'El campo nombre es obligatorio.',
        'email.required' => 'El campo email es obligatorio.',
        'email.email' => 'El campo email debe ser una dirección de correo válida.',
        'email.unique' => 'El email ya está registrado.',
        'password.required' => 'La contraseña es obligatorio.'
    ];

    $validator = Validator::make($request->all(), [
        'nombre' => 'required',
        'email' => 'required|email|unique:User,email',
        'password' => 'required'
    ], $messages);

    if ($validator->fails()) {
        return response()->json(["msg" => $validator->errors()], 400);
    }

    // Si la validación es exitosa, entonces almacenamos el usuario
    $user = new User;
    $user->id = $request->id;
    $user->nombre = $request->nombre;
    $user->email = $request->email;
    $user->password = Hash::make($request->password);
    $user->pg = 0;
    $user->pj = 0;
    $user->rol = 0;
    $user->save();

    return response()->json(["msg" => $user], 200);
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    }

    static function updatePg(string $id){
        DB::update('update usuarios set pj = pj + 1  where id = :id', ["id" => $id]);
    }

    static function updatePj(string $id){
        DB::update('update usuarios set pg = pg + 1  where id = :id', ["id" => $id]);
    }
    
}
