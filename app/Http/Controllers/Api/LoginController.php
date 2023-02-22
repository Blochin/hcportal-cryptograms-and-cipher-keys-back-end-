<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\RegisterAdminMail;
use App\Mail\RegisterMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Traits\ApiResponser;
use App\Traits\Hashable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

/**
 * @group Authentication
 *
 * APIs for Auth
 */
class LoginController extends Controller
{
    use Hashable;
    use ApiResponser;
    /**
     * Login
     *
     * @unauthenticated
     * 
     * Login to web application<br><br>
     * <b>Status codes:</b><br>
     * <b>200</b> - Successfully logged in<br>
     * <b>422</b> - Validation error<br>
     * 
     * @bodyParam  email string required Login e-mail Example: login@login.sk
     * @bodyParam  password string required Password
     * 
     * @responseFile responses/auth/login.200.json
     * @responseFile responses/auth/login.422.json
     * 
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->success($validator->messages(), 'Validation errors.', JsonResponse::HTTP_UNPROCESSABLE_ENTITY,  422);
        }

        $client = User::where('email', $request->email)->where('password', $this->hash($request->password))->first();


        if (!$client || !$client->activated) {
            return $this->success(['login' => trans('auth.failed')], 'Validation errors.', JsonResponse::HTTP_UNPROCESSABLE_ENTITY,  422);
        }

        // if (config('app.env') == 'production') {
        //     $client->tokens()->delete();
        // }


        $token = $client->createToken('web')->plainTextToken;


        return $this->success([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'is_admin' => $client->hasRole('admin'),
            'user' => $client,
        ], 'Successfully logged in.', 200, 200);
    }

    /**
     * 
     * Log out
     *
     * @authenticated
     * 
     * Log out 
     * 
     * @response  {
     *  "status": "Success",
     *  "status_code": 200,
     *  "message": "Successfully logged out.",
     *  "data": null
     * }
     */
    public function logout()
    {

        $client = auth('sanctum')->user();
        $client->tokens()->delete();

        return $this->success(null, 'Successfully logged out.', 200);
    }

    /**
     * Check token
     * 
     * @authenticated
     *
     * Check if the token is valid
     * 
     * <b>Status codes:</b> <br>
     * <b>200</b> - Valid token <br>
     * <b>201</b> - Invalid token
     * 
     * 
     * @response {
     *   "status": "Success",
     *   "status_code": 201,
     *   "message": "Token valid check.",
     *   "data": false
     * }
     */
    public function tokenCheck()
    {
        return $this->success(auth('sanctum')->check(), 'Token valid check.', 200, auth('sanctum')->check() ? 200 : 201);
    }

    /**
     * Register
     *
     * @unauthenticated
     * 
     * Register to web application<br><br>
     * <b>Status codes:</b><br>
     * <b>200</b> - Successfully registered<br>
     * <b>422</b> - Validation error<br>
     * 
     * @bodyParam  first_name string required Firstname
     * @bodyParam  last_name string required Lastname
     * @bodyParam  email string required Login e-mail Example: login@login.sk
     * @bodyParam  password string required Password
     * 
     * @responseFile responses/auth/register.200.json
     * @responseFile responses/auth/register.422.json
     * 
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->success($validator->messages(), 'Validation errors.', JsonResponse::HTTP_UNPROCESSABLE_ENTITY,  422);
        }

        $validated = $validator->validated();
        $validated['password'] = $this->hash($validated['password']);

        $client = User::create($validated);

        $roleUser = Role::where('name', 'user')->first();
        if ($roleUser) {
            $client->roles()->sync([$roleUser->id]);
        }

        Mail::to($client->email)->send(new RegisterMail($client));
        Mail::to(config('mail.to.email'))->send(new RegisterAdminMail($client));

        return $this->success($client, 'Successfully registered.', 200, 200);
    }
}
