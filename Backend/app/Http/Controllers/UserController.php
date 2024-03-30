<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/users",
     *     summary="Get a list of users",
     *     tags={"Users"},
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Invalid request")
     * )
     */
    function index()
    {
        $users = User::all();
        return response()->json($users);
    }


    /**
     * @SWG\Post(
     *     path="/users",
     *     summary="Create New User",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="User's name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="User's email",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User's password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Invalid request")
     * )
     */
    function store(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8'
        ]);
        /*        if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }*/
        $data = $request;
        $user = [
            "name" => $data['name'],
            "email" => $data['email'],
            "password" => Hash::make($data["password"])
        ];

        $user = User::create($user);
        $status = "success";
        $response = ['user' => $user,
            'status' => $status];
        return response()->json($response);
    }


    function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'password' => 'string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = User::find($request->id);

        if (isset($request->name)) {
            $user->name = $request->name;
        }
        if (isset($request->password)) {
            $user->password = $request->password;
        }

        $user->save();
        return response()->json([
            'status' => 'success'
        ]);
    }


    /**
     * @SWG\Delete(
     *     path="/users/{id}",
     *     summary="Delete User",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="User's id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Invalid request")
     * )
     */
    function destroy(Request $request)
    {
        $user = User::find($request->id);
        $user->delete();
        return response()->json([
            'status' => 'success'
        ]);
    }


    /**
     * @SWG\Post(
     *     path="/assignRole",
     *     summary="Assign Role to User",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="User's id",
     *         required=true,
     *         parameter=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="role",
     *         in="query",
     *         description="Role's name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Invalid request")
     * )
     */

    function assignRole(Request $request)
    {
        $user_id = $request->user_id;
        $role = $request->role;

        RoleController::assignRole($user_id, $role);

        return response()->json([
            'status' => 'success'
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/user",
     *     summary="Get logged-in user details",
     *     tags={"User"},
     *      @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="User's id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getUserDetails($id)
    {
        $user = User::find($id)->get()->first();
        return response()->json(['user' => $user], 200);
    }
}
