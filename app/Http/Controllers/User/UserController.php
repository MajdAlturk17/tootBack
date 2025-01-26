<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Http\Requests\User\LoginAdminRequest;
use App\Http\Requests\User\RegisterAdminRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Application\Services\User\UserService;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    protected UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $result = $this->service->index($request->all());
        return ApiResponse::success($result);
    }

    /**
     * @throws ValidationException
     */
    public function loginAdmin(LoginAdminRequest $request): JsonResponse
    {
        $data = $this->service->loginAdmin($request->validated());
        return ApiResponse::success($data);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logout successful',
        ]);
    }

}
