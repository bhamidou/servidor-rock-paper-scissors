<?php

namespace App\Http\Controllers;

use App\Models\Partida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ControllerUsuario;
use App\Http\Controllers\Service\MailController;
use App\Models\Ronda;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ControllerPartida extends Controller
{
    /**
     * Display all partidas, solo admin
     */

    public function index()
    {
        $data = Partida::all();
        return response()->json($data);
    }

    /**
     * Store new partida anyone
     */
    public function store(Request $req)
    {
        //guardando el body en variables para trabajar más fácil
        $tirada1 = $req->get("tirada_user_1");
        $tirada2 = $req->get("tirada_user_2");
        $id_user_1 = $req->get("id_user_1");
        $id_user_2 = $req->get("id_user_2");

        //siguiendo el standar de que el user1 siempre será el más pequeño
        if ($id_user_1 > $id_user_2) {
            $aux = $id_user_2;
            $id_user_2 = $id_user_1;
            $id_user_1 = $aux;
        }

        $idronda = $req->get("id_ronda");

        //array asociativo para pasar al validation
        $validation = [
            'id_ronda' => $idronda,
            'id_user_1' => $id_user_1,
            'id_user_2' => $id_user_2,
            'tirada_user_1' => $tirada1,
            'tirada_user_2' =>  $tirada2,
        ];


        $validator = $this->validatorPartida($validation);

        if ($validator->fails()) {
            return response()->json(["msg" => $validator->errors()], 400);
        }

        //si pasa la validación, se pasará a comprobar la partida y guardarla

        $partidas = count(DB::select('select * from partida where id_ronda = ?', [$idronda]));

        //check max 5 partidas
        if ($partidas <= 5) {

            //Select del número de victorias de los usuarios
            $select = DB::select('SELECT ganador, count(ganador) as "victorias" FROM partida WHERE id_ronda = ? GROUP by ganador;', [$idronda]);

            /**
             * Si es la primer victoria para ambos, la select será null, pero se debe de guardar el resultado
             * Hay un problema, no se como podría refactorizar este código dado que el contenido de los if es el mismo,
             * podría encapsularlo en una función, pero quizás se abstraerá demasiado y quizás no se entienda lo que se quiere 
             * realizar, además de que más de 2 niveles de anidación de condicionales segun código limpio, que leímos con marciano.
             *
             * */
            if (empty($select[0]) || empty($select[1])) {

                //Si se llega a las 3 victorias de la partida se actualiza el estado y el ganador de la ronda
                if (!empty($select[0]) && $select[0]->victorias >= 3) {
                    $ganador = $select[0]->ganador;

                    $rtnMsg = "ID: " . $ganador . "  ha ganado 3 partidas";

                    ControllerRonda::updateFinPartida($idronda, $ganador);
                    ControllerUsuario::updatePg($ganador);
                    ControllerUsuario::updatePj($ganador);


                    return response()->json(["msg" => $rtnMsg, "ronda" => "finalizada"], 202);
                }

                //Si se llega a las 3 victorias de la partida se actualiza el estado y el ganador de la ronda
                if (!empty($select[1]) && $select[1]->victorias >= 3) {
                    $ganador = $select[1]->ganador;

                    $rtnMsg = "ID: " . $ganador . "  ha ganado 3 partidas";

                    //Si se llega a las 3 victorias de la partida se actualiza el estado y el ganador de la ronda
                    ControllerRonda::updateFinPartida($idronda, $ganador);
                    ControllerUsuario::updatePg($ganador);
                    ControllerUsuario::updatePj($ganador);

                    return response()->json(["msg" => $rtnMsg, "ronda" => "finalizada"], 202);
                }

                $partida = $this->savePartida($tirada1, $tirada2, $id_user_1, $id_user_2, $req);
                return response()->json(["msg" => $partida, "ronda" => "continua"], 202);
            } else {

                //se comprueba si se llega a las victorias de los dos usuarios.
                if ($select[0]->victorias >= 3 || $select[1]->victorias >= 3) {

                    $ganador = $select[1]->ganador;

                    //Se comprueba quien es el ganador
                    if ($select[0]->victorias >= 3) {
                        $ganador = $select[0]->ganador;
                    }

                    $rtnMsg = "ID: " . $ganador . "  ha ganado 3 partidas";

                    //Si se llega a las 3 victorias de la partida se actualiza el estado y el ganador de la ronda
                    ControllerRonda::updateFinPartida($idronda, $ganador);
                    ControllerUsuario::updatePg($ganador);
                    ControllerUsuario::updatePj($ganador);

                    return response()->json(["msg" => $rtnMsg, "ronda" => "finalizada"], 202);
                } else {

                    //si hay menos de 5 partidas, y no se han llegado a las 3 victorias, se guarda la partida
                    $partida =  $partida = $this->savePartida($tirada1, $tirada2, $id_user_1, $id_user_2, $req);
                    return response()->json(["msg" => $partida, "ronda" => "continua"], 202);
                }
            }
        } else {
            /**
             * si se ha terminado la partida, y algun usuario no se ha dado cuenta, se le informa de que ha terminado la ronda con 5 partidas max
             */

            return response()->json(["msg" => "Max 5 partidas por ronda", "ronda" => "terminada"], 400);
        }
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
     * Update partida, solo admin.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage, solo admin
     */
    public function destroy(string $id)
    {
        if (Partida::find($id)) {
            Partida::find($id)->delete();
            return response()->json([
                "msg" => "Partida with ID " . $id . " deleted"
            ], 202);
        } else {
            return response()->json([
                "msg" => "Partida with ID " . $id . " not found"
            ], 404);
        }
    }

    private function savePartida($tirada1, $tirada2, $id_user_1, $id_user_2, $req)
    {
        $ganador = $this->checkWinner($tirada1, $tirada2, $id_user_1, $id_user_2);
        $store = [
            'id_ronda' => $req->get("id_ronda"),
            'tirada_user_1' => $req->get("tirada_user_1"),
            'tirada_user_2' => $req->get("tirada_user_2"),
            'ganador' => $ganador
        ];
        $partida = Partida::create($store);
        return $partida;
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

    private function msgValidation()
    {

        $messages = [
            'id_ronda.required' => 'El ID de la ronda es obligatorio.',
            'id_ronda.exists' => 'El ID de la ronda no existe.',

            'id_user_1' => [
                'required' => 'El ID del usuario 1 es obligatorio.',
                'exists'  => 'El ID del usuario 1 no existe o no tiene una ronda, se recomienda crear un nuevo usuario y una nueva ronda.',

                'different' => 'El ID del usuario 2 debe ser diferente al ID del usuario 1.'
            ],

            'tirada_user_1.required' => 'La tirada del usuario 1 es obligatoria.',
            'tirada_user_1.in' => 'La tirada del usuario 1 debe ser rock, paper o scissors.',

            'id_user_2' => [
                'required' => 'El ID del usuario 2 es obligatorio.',
                'exists'  => 'El ID del usuario 2 no existe o no tiene una ronda, se recomienda crear un nuevo usuario y una nueva ronda.',
                'different' => 'El ID del usuario 2 debe ser diferente al ID del usuario 1.'
            ],

            'tirada_user_2.required' => 'La tirada del usuario 2 es obligatoria.',
            'tirada_user_2.in' => 'La tirada del usuario 2 debe ser rock, paper o scissors.',
        ];
        return $messages;
    }

    private function validatorPartida($req)
    {
        $validator = Validator::make($req, [
            'id_ronda' => 'required|exists:ronda,id',
            'id_user_1' => [
                'required',
                'exists:usuario,id',
                Rule::exists('ronda', 'id_user_1')->where(function (Builder $query) use ($req) {
                    return $query->where('id_user_1', $req['id_user_1'])->where('id', $req['id_ronda']);
                }),
                'different:id_user_2'
            ],
            'tirada_user_1' => 'required|in :rock,paper,scissors',
            'id_user_2' => [
                'required',
                'exists:usuario,id',
                Rule::exists('ronda', 'id_user_2')->where(function (Builder $query) use ($req) {
                    return $query->where('id_user_2', $req['id_user_2'])->where('id', $req['id_ronda']);
                }),
                'different:id_user_1'
            ],
            'tirada_user_2' => 'required|in :rock,paper,scissors'
        ], $this->msgValidation());
        return $validator;
    }
}
