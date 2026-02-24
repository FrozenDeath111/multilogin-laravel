<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class AuthController extends ResponseController
{
    protected function getModel(): Model
    {
        return new User();
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'c_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->setData($validator->errors())->sendError('Validation Error');
        }

        $input = $request->all();

        $userExists = $this->getModel()::where('email', $input['email'])->exists();

        if ($userExists)
            return $this->setStatusCode(400)->sendError("User Already Exists");

        try {
            $user = $this->getModel()::create($input);
            $data['token'] = $user->createToken("E-com")->accessToken;
            $data['user'] = $user;

            return $this->setData($data)->sendResponse('User creation successful');
        } catch (\Throwable $th) {
            return $this->setStatusCode(400)->sendError($th->getMessage());
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->setData($validator->errors())->sendError('Validation Error');
        }

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $data['token'] = $user->createToken("E-com")->accessToken;
            $data['user'] = $user;
            return $this->setData($data)->sendResponse('Login Successful');
        } else {
            return $this->setStatusCode(401)->setData(['error' => 'Unauthorised'])->sendError('Wrong Email or Password.');
        }
    }

    public function profile()
    {
        $user = Auth::user();
        if ($user)
            return $this->setData($user)->sendResponse('Fetching Profile Successful');
        else
            return $this->setStatusCode(400)->sendError();
    }

    public function getUser()
    {
        $user = Auth::user();
        $user['app_name'] = config('app.name');

        if ($user)
            return $this->setData($user)->sendResponse('Fetching Profile Successful');
        else
            return $this->setStatusCode(400)->sendError();

    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return $this->sendResponse('Successfully logged out');
    }

    public function SSOVerify($token)
    {
        $userData = Cache::pull("sso_token_" . $token);

        if (!$userData) {
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }

        return response()->json($userData);
    }
}
