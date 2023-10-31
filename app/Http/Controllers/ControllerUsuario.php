<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;

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
    public function store(Request $req)
    {
        $vec = [
            "nombre" => $req->get("nombre"),
            "email" => $req->get("email"),
            "password" => $req->get("password"),
            "pg" => $req->get("pg"),
            "pj" => $req->get("pj")
        ];

        DB::insert('insert into usuario (nombre, email, password, pg, pj) values ( :nombre, :email ,:password , :pg , :pj)', $vec);

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
}
