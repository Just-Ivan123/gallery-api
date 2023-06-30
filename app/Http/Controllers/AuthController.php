<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        $response = $this->authService->login($request->validated());

        return response()->json($response);
    }

    public function register(RegisterRequest $request)
    {
        $response = $this->authService->register($request->validated());

        return response()->json($response);
    }

    public function logout()
    {
        $response = $this->authService->logout();

        return response()->json($response);
    }

    public function refresh()
    {
        $response = $this->authService->refresh();

        return response()->json($response);
    }

    public function checkAuthentication(Request $request)
    {
        $token = $request->input('token');
        $response = $this->authService->checkAuthentication($token);

        return response()->json($response);
    }
}