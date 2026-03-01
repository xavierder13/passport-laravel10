<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {   
        $users = User::all();
        return response()->json(['users' => $users], 200);
    }

    public function store(Request $request)
    { 

    }

    public function show($id) 
    { 
        $user = User::find($id);
        return response()->json(['user' => $user], 200);
    }

    public function update(Request $request, $id) 
    {

    }

    public function destroy($id) 
    { 

    }
    
}
