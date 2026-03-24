<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;

class UserController extends Controller
{
    public function index()
    {   
        $users = User::all();
        return response()->json(['users' => $users], 200);
    }

    public function store(Request $request)
    {   

        $rules = [
            'name.required' => 'Please enter name',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email',
            'email.unique' => 'Email already exists',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be atleast 8 characters',
            'password.same' => 'Password and Confirm Password did not match',
            'confirm_password.required' => 'Confirm Password is required',
            'branch_id.required' => 'Branch is required',
            'branch_id.integer' => 'Branch must be an integer',
            'position_id.integer' => 'Position must be an integer',
        ];

        $valid_fields = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8|same:confirm_password',
            'confirm_password' => 'required',
            'branch_id' => 'required|integer',
            'position_id' => 'nullable|integer',
        ];

        $validator = Validator::make($request->all(), $valid_fields, $rules);

        if($validator->fails())
        {   
            return response()->json($validator->errors(), 200);
        }

        // $user = new User();
        // $user->name = $request->get('name');
        // $user->email = $request->get('email');
        // $user->password = Hash::make($request->get('password'));
        // $user->branch_id = $request->get('branch_id');
        // $user->position_id = $request->get('position_id');
        // $user->active = $request->get('active');
        // $user->save();

        // $user->assignRole($request->get('roles'));

        $user = User::with('roles')
                    ->with('roles.permissions')
                    ->with('branch')
                    ->with('position')
                    ->where('id', '=', 1)->first();

        return response()->json(['success' => 'Record has successfully added', 'user' => $user], 200);
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
