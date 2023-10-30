<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ronda;
use Illuminate\Support\Facades\DB;
class ControllerRonda extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Ronda::all();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::insert('insert into ronda (estado, ganador) values (0, 0)');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
