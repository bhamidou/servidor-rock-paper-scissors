<?php

namespace App\Http\Controllers;

use App\Models\Partida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ControllerUsuario;

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
        if ($this->checkRequiredValues($req)) {

            $vec = [$req->get("idUser1"), $req->get("idUser2")];

            $ordered = $this->orderIdUser($vec[0], $vec[1]);

            $idUsuario1 = $ordered["idUser1"];
            $idUsuario2 = $ordered["idUser2"];

            $tirada1 = $req->get("tirada1");
            $tirada2 = $req->get("tirada2");

            $vecInsert = [
                "idUser1" => $idUsuario1,
                "idUser2" => $idUsuario2,
                "tirada1" => $tirada1,
                "tirada2" => $tirada2,
                "idRonda" => $req->get("idRonda"),
                "ganador" => $this->checkWinner($tirada1, $idUsuario1,$tirada2,$idUsuario2)];

            $numRondas = $this->checkRonda($ordered["idUser1"], $ordered["idUser2"], $req->get("idRonda"));

            if ($numRondas[0]->count <= 5 || $numRondas[0]->count == null) {
                DB::insert('insert into partidas ( idRonda, idUser1, tirada1, idUser2, tirada2, ganador) values (:idRonda, :idUser1, :tirada1, :idUser2, :tirada2, :ganador)',$vecInsert );
                ControllerUsuario::updatePg($ordered["idUser1"]);
                ControllerUsuario::updatePg($ordered["idUser2"]);

                $rtnMsg = ["ganador" => $vecInsert["ganador"]];
            } else {
                $rtnMsg = "Need create new game";
            }
        } else {
            $rtnMsg = "Required parameters";
        }

        return response()->json($rtnMsg);
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
        $vec = ["idUser1" => $idUser1, "idUser2" => $idUser2, "idRonda"  =>$idRonda];

        $checkNumRondas = \DB::select("SELECT COUNT(ganador) as 'count' FROM partidas WHERE iduser1 = :idUser1 and idUser2 = :idUser2 and idRonda = :idRonda", $vec);

        return $checkNumRondas;
    }

    private function createNewRonda(){

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

    private function checkWinner($tirada1, $idUser1, $tirada2, $idUser2 )
    {
        /**
         * 1> rock
         * 2> paper
         * 3> scissors
         * -1 tie (empate)
         * other number is the idUser
         */

         $rtnWinner = -1;
        if($tirada1 != $tirada2){
            if ($tirada1>$tirada2 && $tirada1 == 2){
                $rtnWinner =  $idUser1;
            }elseif($tirada1 == 3 &&  $tirada2 == 2){
                $rtnWinner =  $idUser1;
            }elseif($tirada1 == 3 &&  $tirada2 == 1){
                $rtnWinner =  $idUser2;
            }else{
                $rtnWinner =  $idUser2;
            }
        }else{
            $rtnWinner = -1;
        }
        return $rtnWinner;
    }

    private function checkEmptyValue($value)
    {
        return empty($value);
    }

    private function checkRequiredValues($req)
    {

        $v = [
            "idUser1" => $req->get("idUser1"),
            "idUser2" => $req->get("idUser2"),
            "tirada1" => $req->get("tirada1"),
            "tirada2" => $req->get("tirada2"),
            "idRonda" => $req->get("idRonda")
        ];

        $aux = 0;

        foreach ($v as $value) {
            if (!$this->checkEmptyValue($value)) {
                $aux++;
            }
        }
        $check = false;

        if ($aux == count($v)) {
            $check = true;
        }
        return $check;
    }


}
