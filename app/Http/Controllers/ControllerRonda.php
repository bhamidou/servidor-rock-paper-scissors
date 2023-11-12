<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ronda;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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
    public function store(Request $req)
    {
        $validator = $this->validatorRonda($req);
        
        if ($validator->fails()) {
            return response()->json(["msg" => $validator->errors()], 400);
        }

        $id_user_1 = $req->get("id_user_1");
        $id_user_2 = $req->get("id_user_2");

        
        if($id_user_1>$id_user_2){
            $aux = $id_user_2;
            $id_user_2 = $id_user_1;
            $id_user_1 = $aux;
        }

        $select = DB::select('select * from ronda where status = ? and id_user_1 = ? and id_user_2 = ?', [0, $id_user_1, $id_user_2]);
        
        if($select == null){
            $store = [
                'id_user_1' => $id_user_1,
                'id_user_2' => $id_user_2,
                'status' => 0,
                'ganador' => 0,
            ];
            $ronda = Ronda::create($store);
        }else{
            return response()->json(["msg" => "id_user_1 o id_user_2 tinen alguna ronda sin terminar"], 400);
        }
        $rtnMsg = ["msg" => $ronda];
        
        if($aux){
            $rtnMsg = ["msg" => $ronda, "alert" =>"el ID de los usuarios ha sido reordenado", 
                "order" => [
                    'id_user_1' => $id_user_1,
                    'id_user_2' => $id_user_2,
                ]];
        }

        return response()->json($rtnMsg, 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ronda = Ronda::find($id);
        return response()->json($ronda);
        
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
        if (Ronda::find($id)) {
            Ronda::find($id)->delete();
            return response()->json([
                "msg" => "Ronda with ID " . $id . " deleted"
            ], 202);
        } else {
            return response()->json([
                "msg" => "Ronda with ID " . $id . " not found"
            ], 404);
        }
    }

    static function updateFinPartida($id_ronda, $id_winner){
        $ronda = Ronda::find($id_ronda);
        $ronda->status = 1;
        $ronda->ganador = $id_winner;
        $ronda->save();
    }

    private function msgValidation()
    {

        $messages = [
            'id_user_1' => [
                'required' => 'El ID del usuario 1 es obligatorio.',
                'exists'  =>'El ID del usuario 1 no existe o la ronda todavía no ha finalizado, se recomienda crear un nuevo usuario o terminar la ronda.',
                'different' => 'El ID del usuario 2 debe ser diferente al ID del usuario 1.'
            ],

            'id_user_2' => [
                'required' => 'El ID del usuario 2 es obligatorio.',
                'exists'  => 'El ID del usuario 2 no existe o la ronda todavía no ha finalizado, se recomienda crear un nuevo usuario o terminar la ronda.',
                'different' => 'El ID del usuario 2 debe ser diferente al ID del usuario 1.'
            ]
        ];
        return $messages;
    }

    private function validatorRonda($req)
    {
        $validator = Validator::make($req->all(), [
            'id_user_1' => [
                'required',
                'exists:usuario,id',
                'different:id_user_2'
            ],
            'id_user_2' => [
                'required',
                'exists:usuario,id',
                'different:id_user_1'
            ],
        ], $this->msgValidation());
        return $validator;
    }


}
