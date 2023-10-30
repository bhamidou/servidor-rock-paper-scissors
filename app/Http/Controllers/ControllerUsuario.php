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
    public function store(Request $request)
    {

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
