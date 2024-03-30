<?php

namespace app\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserStoreRequest;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use App\Http\services\RegisterService as Register;
use App\Http\services\LoginService as Login;

class AuthController extends Controller
{
    use HttpResponses;

    public function register(UserStoreRequest $request)
    {
        $validated = $request->validated();

        $user = Register::register($validated);

        return $this->success([
            'user' => $user,
            'token' => $user->createToken("API Token Of $user->name")->plainTextToken,
        ], 'User Created Successfully.');
    }

    public function login(UserLoginRequest $request)
    {
        $credentials = $request->validated();
        $remember = $request->filled('remember');

        $result = Login::login($credentials, $remember);
        $user = Auth::user();

        return $result
            ?
            $this->success([
                'user' => $user,
                'token' => $user->createToken("API Token Of $user->name")->plainTextToken,
            ], 'User Logged Successfully!')
            : $this->error([], 'User Not Found', 404);
    }


    public function logout()
    {
        $result = Auth::logout();

        return $result
            ? $this->success(['user' => Auth::user()], 'User Logged Out Successfully!')
            : $this->error([], 'User Not Logged', 404);

    }

    public function salam() {
        return response()->json('this is my salam method');
    }
}
