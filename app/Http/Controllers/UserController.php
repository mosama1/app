<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Profile;
use File;

class UserController extends Controller
{

    public function index()
    {
    	$users = User::orderBy('created_at', 'desc')->get(['user_name', 'id']);
    	foreach ($users as $user) {
    		$user->profile;
    	}

    	return response()->json([
    			'users' => $users
    		]);
    }

    public function show($id)
    {
    	$user = User::with('profile', 'recipes')->findOrFail($id);


    	return response()->json([
    			'user' => $user
    		]);
    }

    public function edit($id, Request $request)
    {
    	$form = User::with('profile')->findOrFail($id);
    	$form->image = $form->profile->image;
    	return response()->json([
			'form' => $form
			]);
    }

    public function update($id, Request $request)
    {
    	$this->validate($request, [
    		'profile.name' => 'required|min:3',
    		'profile.last_name' => 'required|min:3',
    		'profile.sex' => 'required',
    		'profile.direction' => 'required',
    		'profile.phone' => 'required',
    		'profile.birthdate' => 'required|date',
    		'image' => 'image',
		]);


		$profile = Profile::where('user_id', $id)->first();
		$profile->name = $request->profile['name'];
		$profile->last_name = $request->profile['last_name'];
		$profile->sex = $request->profile['sex'];
		$profile->direction = $request->profile['direction'];
		$profile->phone = $request->profile['phone'];
		$profile->birthdate = $request->profile['birthdate'];
		if ($request->hasFile('image') && $request->file('image')->isValid()) {
			$filename = $this->getFileName($request->image);
			$request->image->move(base_path('public/img/users'), $filename);
			//remove old image
			File::delete(base_path('public/img/users/'.$profile->image));

			$profile->image = $filename;

		}
		$profile->save();

    	return response()->json([
			'saved' => true,
            'id' => $profile->user_id,
            'image' => $profile->image,
			'message' => 'Perfil Actualiza Satisfactoriamente',

			]);
    }
    protected function getFileName($file)
    {
    	return str_random(32).'.'.$file->extension();
    }
    public function auth_user(Request $request)
    {
        $user = User::with('profile')->findOrFail($request->user_id);


        return response()->json([
                'user' => $user
            ]);
    }

}
