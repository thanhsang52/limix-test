<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/users",
     *     summary="Get list of users",
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="List of users",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        return response()->json(User::all());
    }

    /**
     * @OA\Post(
     *     path="/users",
     *     summary="Create a new user",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="User created"),
     *     @OA\Response(response=422, description="Validation failed")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validate dữ liệu đầu vào
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
            ]);

            // Nếu lỗi validate, trả về lỗi 422
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $data = $validator->validated();
            if(!empty($data['password']))
                $data['password'] = bcrypt($data['password']);

            // Lưu user
            $user = User::create($data);

            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully.',
                'data' => $user,
            ], 201);
        } catch (QueryException $e) {
            // Lỗi SQL, ví dụ lỗi trùng email do race condition
            return response()->json([
                'status' => 'error',
                'message' => 'Database error.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            // Lỗi không xác định
            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     summary="Get a single user",
     *     tags={"Users"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="User details"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function show($id): JsonResponse
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }
    
    /**
     * @OA\Put(
     *     path="/users/{id}",
     *     summary="Update a user",
     *     tags={"Users"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="User updated"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(response=422, description="Validation failed")
     * )
     */
    public function update(Request $request, $id): JsonResponse
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found.'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => "sometimes|required|email|unique:users,email,{$id}",
            'password' => 'sometimes|required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully.',
            'data' => $user
        ]);
    }
    
    /**
     * @OA\Delete(
     *     path="/users/{id}",
     *     summary="Delete a user",
     *     tags={"Users"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="User deleted"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function destroy($id): JsonResponse
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found.'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully.'
        ]);
    }
}
