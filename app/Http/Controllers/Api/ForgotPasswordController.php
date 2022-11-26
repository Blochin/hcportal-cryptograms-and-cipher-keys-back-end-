<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\RegisterAdminMail;
use App\Mail\RegisterMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use App\Mail\ResetCodeMail;

/**
 * @group Authentication
 *
 * APIs for Auth
 */
class ForgotPasswordController extends Controller
{
    use ApiResponser;

    /**
     * Send forgot password code
     *
     * @unauthenticated
     * 
     * Send forgot password code<br><br>
     * <b>Status codes:</b><br>
     * <b>200</b> - Successfully logged in<br>
     * <b>422</b> - Validation error<br>
     * 
     * @bodyParam  email string required Login e-mail Example: login@login.sk
     * 
     * @responseFile responses/auth/reset_code.200.json
     * @responseFile responses/auth/reset_code.422.json
     * 
     */
    public function sendCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return $this->success($validator->messages(), 'Validation errors.', JsonResponse::HTTP_UNPROCESSABLE_ENTITY,  422);
        }

        $client = User::where('email', $request->email)->first();


        if (!$client) {
            return $this->success(['login' => trans('auth.password.not_found')], 'Validation errors.', JsonResponse::HTTP_UNPROCESSABLE_ENTITY,  422);
        }

        $code = mt_rand(100000, 999999);

        $resetPassword = DB::table('password_resets')->where('email', $client->email)->delete();
        $resetPassword = DB::table('password_resets')->insert([
            'email' => $client->email,
            'token' => $code,
            'created_at' => now()
        ]);


        Mail::to($request->email)->send(new ResetCodeMail($client, $code));

        return $this->success([], 'Successfully password reset code sent.', 200, 200);
    }


    /**
     * Change password
     *
     * @unauthenticated
     * 
     * Login to web application<br><br>
     * <b>Status codes:</b><br>
     * <b>200</b> - Successfully logged in<br>
     * <b>422</b> - Validation error<br>
     * 
     * @bodyParam  email string required Login e-mail Example: login@login.sk
     * @bodyParam  password string required New password
     * @bodyParam  code string required Password reset code
     * 
     * @responseFile responses/auth/reset_password.200.json
     * @responseFile responses/auth/reset_password.422.json
     * 
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'code' => 'required|integer',
            'password' => 'required|min:7|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/|string',
        ]);

        if ($validator->fails()) {
            return $this->success($validator->messages(), 'Validation errors.', JsonResponse::HTTP_UNPROCESSABLE_ENTITY,  422);
        }

        $client = User::where('email', $request->email)->first();
        $code = DB::table('password_resets')->where('email', $request->email)->where('token', $request->code)->first();


        if (!$client) {
            return $this->success(['login' => trans('auth.password.not_found')], 'Validation errors.', JsonResponse::HTTP_UNPROCESSABLE_ENTITY,  422);
        } elseif (!$code) {
            return $this->success(['login' => trans('auth.password.not_found_code')], 'Validation errors.', JsonResponse::HTTP_UNPROCESSABLE_ENTITY,  422);
        }

        $resetPassword = DB::table('password_resets')->where('email', $client->email)->delete();

        $client->update([
            'password' => Hash::make($request->password)
        ]);


        return $this->success([], 'Successfully reset password.', 200, 200);
    }
}
