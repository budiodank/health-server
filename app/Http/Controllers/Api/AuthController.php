<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
	{
		$validatedData = $request->validate([
			'name' => 'required|string|max:255',
			'telephone' => 'required|string|max:15',
			'email' => 'required|string|email|max:255|unique:users',
			'password' => 'required|string|min:8',
			'street' => 'required|string|max:255',
			'village' => 'required|string|max:255',
			'city' => 'required|string|max:255',
			'province' => 'required|string|max:255',
			'country' => 'required|string|max:255',
			'weight' => 'required|string|max:3',
			'height' => 'required|string|max:3',
			'disease' => 'required',
			'snore' => 'required',
		]);

		$email = $request->get('email');

		$checkUsername = User::where('email', $email)->first();

		$token = "";
		$token_type = "";

		if ($checkUsername == false) {

			$codedetail = new User;

			$user_id = $codedetail->getCode(10);

			$user = User::create([
				'user_id' => $user_id,
				'name' => $validatedData['name'],
				'telephone' => $validatedData['telephone'],
				'email' => $validatedData['email'],
				'password' => Hash::make($validatedData['password']),
				'street' => $validatedData['street'],
				'village' => $validatedData['village'],
				'city' => $validatedData['city'],
				'province' => $validatedData['province'],
				'country' => $validatedData['country'],
				'weight' => $validatedData['weight'],
				'height' => $validatedData['height'],
				'disease' => json_encode($validatedData['disease']),
				'snore' => $validatedData['snore'],
				'active' => "Y",
				'access' => "000002",
			]);	

			$data = User::where('user_id', $user_id)->first();

			$token = $user->createToken('auth_token')->plainTextToken;

			$error = false;
			$code = 200;
			$message = "Register successfully!";
			$token_type = "Bearer";
		} else {
			$error = true;
			$code = 500;
			$data = NULL;
			$message = "Email already used!";
		}


		return response()->json([
			'error' => $error,
			'code' => $code,
			'message' => $message,
			'data' => $data,
			'access_token' => $token,
			'token_type' => $token_type,
		]);
	}

	public function login(Request $request)
	{
		if (!Auth::attempt($request->only('email', 'password'))) {
			return response()->json([
				'message' => 'Invalid login details'
			], 401);
		}

		$user = User::where('email', $request['email'])->firstOrFail();

		$token = $user->createToken('auth_token')->plainTextToken;

		return response()->json([
			'error' => false,
			'code' => 200,
			'message' => "Login successfully!",
			'access_token' => $token,
			'token_type' => 'Bearer',
		]);
	}

	public function profile(Request $request)
	{
		return response()->json([
			'error' => false,
			'code' => 200,
			'message' => "Get Profile Successfully!",
			'data' => $request->user(),
		]);
	}

	public function checkUsername(Request $request)
    {
        $email = $request->get('email');
        
        $user = User::where('email', $email)->first();

        if ($user) {
            return response()->json([
				'error' => true,
				'code' => 500,
				'message' => "Email already used!",
			]);
        }

        return response()->json([
			'error' => false,
			'code' => 200,
			'message' => "Email successfully used!",
		]);
    }

	public function logout()
    {

    	auth()->user()->tokens()->delete();

        return response()->json([
        	'error' => false,
            'message' => "Logout Successfully!",
        ], 200);
    }
}
