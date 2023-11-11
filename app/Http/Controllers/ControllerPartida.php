<?php

namespace App\Http\Controllers;

use App\Models\Partida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ControllerUsuario;
use Illuminate\Support\Facades\Validator;

class ControllerPartida extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Partida::all();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req)
    {

        $input = $req->all();
        $messages = [
            'id_ronda' => [
                "required" =>"El id de la ronda es necesario",
                "exists" =>"El id de la ronda no existe",
        ],
            'id_user_1' => [
                'required' => 'Es necesario el id del usuario 1',
                'exist' => 'Este usuario no existe',
            ],
            'tirada_user_1' => 'La tirada 1 solo puede ser rock,paper o scissors',
            'id_user_2' => [
                'required' => 'Es necesario el id del usuario 2',
                'exist' => 'Este usuario no existe',
            ],
            'tirada_user_2' => 'La tirada 2 solo puede ser rock,paper o scissors',
        ];

        $validator = Validator::make($req->all(), [
            'id_ronda' => 'required|exists:ronda,id',
            'id_user_1' => 'required|exists:usuario,id|different:id_user_2',
            'tirada_user_1' => 'required|in :rock,paper,scissors',
            'id_user_2' => 'required|exists:usuario,id|different:id_user_1',
            'tirada_user_2' => 'required|in :rock,paper,scissors'
        ], $messages);

        
        if($validator->fails()){
            return response()->json($validator->errors(),202);
        }

        $ganador = $this->checkWinner($req->get("tirada_user_1"),$req->get("tirada_user_2"), $req->get("id_user_1"), $req->get("id_user_2"));
        $store = [
            'id_ronda' => $req->get("id_ronda"),
            'tirada_user_1' => $req->get("tirada_user_1"),
            'tirada_user_2' => $req->get("tirada_user_2"),
            'ganador' => $ganador
        ];

        $partida = Partida::create($store);

        return response()->json($partida);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $game = Partida::find($id);
        return response()->json($game);
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

    private function checkRonda(string $idUser1, string $idUser2, string $idRonda)
    {
        $vec = ["idUser1" => $idUser1, "idUser2" => $idUser2, "idRonda"  => $idRonda];

        $checkNumRondas = \DB::select("SELECT COUNT(ganador) as 'count' FROM partidas WHERE iduser1 = :idUser1 and idUser2 = :idUser2 and idRonda = :idRonda", $vec);

        return $checkNumRondas;
    }

    private function createNewRonda()
    {
    }

    private function orderIdUser(string $idUser1, string $idUser2)
    {

        $order = [];
        if ($idUser1 < $idUser2) {
            $order = ["idUser1" => $idUser1, "idUser2" => $idUser2];
        } else {
            $order = ["idUser1" => $idUser2, "idUser2" => $idUser1];
        }

        return $order;
    }

    private function checkWinner($tirada1, $tirada2, $id1, $id2)
    {
            if ($tirada1 == $tirada2) {
                $resultado = 0;
            } else if ($tirada1 == "rock" && $tirada2 == "scissors") {
                $resultado = $id1;
            } else if ($tirada1 == "paper" && $tirada2 == "rock") {
                $resultado = $id1;
            } else if ($tirada1 == "scissors" && $tirada2 == "paper") {
                $resultado = $id1;
            } else {
                $resultado = $id2;
            }
            return $resultado;
    }

}
