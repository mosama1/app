<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Profile;
use Hash;
class AuthController extends Controller
{
	public function __construct()
	{
	    $this->middleware('auth:api')->only('logout');
	}

    public function register(Request $request)
    {

    	$this->validate($request, [
    		'user_name' => 'required|min:5|max:12|unique:users|no_spaces',
    		'email' => 'required|email|unique:users',
    		'password' => 'required|between:6,25|confirmed',

		]);

		$user = new User($request->all());
		$user->password = bcrypt($request->password);
		$user->save();

		$profile = new Profile();
		$profile->user_id = $user->id;
		$profile->save();

		return response()->json([
				'registered' => true
			]);
    }

    public function login(Request $request)
    {
  		$isEmail = filter_var($request->input('user_name'), FILTER_VALIDATE_EMAIL) ? true : false;

    	$this->validate($request, [
    		'user_name' => 'required|no_spaces|',
    		'password' => 'required|between:6,25',
		]);
		$user = User::where(($isEmail ? 'email' : 'user_name'), $request->user_name)
		->first();

		if ($user && Hash::check($request->password, $user->password)) {
			$user->api_token = str_random(60);
			$user->save();

			return response()->json([
				'authenticated' => true,
				'api_token' => $user->api_token,
				'user_id' => $user->id,
				'profile' => $user->profile
			]);
		}

		return response()->json([
			'email' => ['Correo electronico and Password no existen'],
		], 422);
    }
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->api_token = null;
        $user->save();

        return response()
            ->json([
                'logged_out' => true
            ]);
    }
}
