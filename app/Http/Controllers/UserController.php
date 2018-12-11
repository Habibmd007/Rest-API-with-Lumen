<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;


class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    public function index()
    {
        echo 'ok';
    }

    public function create(Request $request)
    {
        
        try {

            $this->validate($request,[

                'full_name' => 'required|max:60',
                'username' => 'required|min:6',
                'email' => 'required|email',
                'password' => 'required|min:6',
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'succsess' => 'false',
                'message'  => $e->getMessage(),
            ],422);
            
        }






        try {
            $img_url = $this->imageUploader($request);
            $id = app('db')->table('users')->insertGetId([
                'full_name' => trim($request->input('full_name')),
                'username' => strtolower(trim($request->input('username'))),
                'email' => strtolower(trim($request->input('email'))),
                'password' => app('hash')->make($request->input('password')),
                'image' => $img_url,
            ]);

            $user = app('db')->table('users')->select('full_name', 'username', 'email')->first();
            return response()->json([
                'id'=> $id,
                'full_name' => $user->full_name,
                'username' => $user->username,
                'email' => $user->email,
            ], 201);

        } catch (\PDOException $e) {
            return response()->json([
                'success'=> false,
                'message'=> $e->getMessage(),
            ],400);
        }

    }

    
}
