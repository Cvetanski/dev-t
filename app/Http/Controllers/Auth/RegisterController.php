<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

///**
// * @OA\Info(title="Your super ApplicationAPI", version="1.0")
// *
// * @OA\Server(url="http://127.0.0.1:8000")
// *
// * @OAS\SecurityScheme(
// *      securityScheme="bearer_token",
// *      type="http",
// *      scheme="bearer"
// * )
// */


class RegisterController extends Controller
{
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
            'last_name' => 'required|string',
            'username' => 'required|string',
            'amount' => 'int',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'last_name' => $fields['last_name'],
            'username' => $fields['username'],
            'amount' => $fields['amount'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        return response()->json([
            'message'=>"You Successfully register!",
            "token" => $token
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/login",
     * summary="auth",
     * description="Login by email, password",
     * operationId="authLogin",
     * tags={"auth"},
     * @OA\RequestBody(
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *    ),
     * ),
     *     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="message",type="string", example="You Are loggedIn"),
     *              @OA\Property(property="token",type="string", example="Token"),
     *          )
     *      ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
     *        )
     *     )
     * )
     */

    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $fields['email'])->first();

        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Bad credentials'
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        return response()->json([
            'message' => 'Logged in',
            'token' => $token
        ]);
    }


    /**
     * @OA\Post(
     * path="/api/logout",
     * summary="auth",
     * description="Logout user and invalidate token",
     * operationId="authLogout",
     * tags={"auth"},
     * security={ {"tokens": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Success"
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="Returns when user is not authenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Not authorized"),
     *    )
     * )
     * )
     */

    public function logout(Request $request) {
        auth('sanctum')->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out'
        ]);
    }
}
