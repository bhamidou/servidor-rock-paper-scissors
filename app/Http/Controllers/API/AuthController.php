<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $cred = ['email' => $request->email, 'password' => $request->password];
                
        if(Auth::attempt($cred)){
            $auth = Auth::user();

            $success['token'] =  $auth->createToken('LaravelSanctumAuth')->plainTextToken;
            $success['name'] =  $auth->name;

            return response()->json(["success"=>true,"data"=>$success, "message" => "User logged-in!"]);
        }
        else{
            return response()->json("Unauthorized",401);
        }
    }
    public function signup(Request $request)
    {
        $messages = [
            'name.required' => 'El campo name es obligatorio.',
            'email.required' => 'El campo email es obligatorio.',
            'email.email' => 'El campo email debe ser una dirección de correo válida.',
            'email.unique' => 'El email ya está registrado.',
            'password.required' => 'La contraseña es obligatorio.'
        ];
    
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:usuario,email',
            'password' => 'required'
        ], $messages);
    
        if ($validator->fails()) {
            return response()->json(["msg" => $validator->errors()], 400);
        }
    
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('LaravelSanctumAuth')->plainTextToken;
        $success['name'] =  $user->name;

        return response()->json(["success"=>true,"data"=>$success, "message" => "User successfully registered!"]);
    }
    
    public function logout(Request $request)
    {
        $cred = ['email' => $request->email, 'password' => $request->password];
                
        if(Auth::attempt($cred)){
            $cantidad = Auth::user()->tokens()->delete();
            return response()->json(["success"=>true, "message" => "Tokens Revoked: ".$cantidad],200);
        }
        else {
            return response()->json("Unauthorized",401);
        }

    }
}
