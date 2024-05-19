<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login(Request $request)
    {
        session()->start();
        session()->put("username", $request->username);
        return response()->json([
            "status" => "success",
            "message" => "Login successfull",
            'code' => 200,
        ]);
    }
}
