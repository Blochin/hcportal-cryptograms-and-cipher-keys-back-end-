<?php

namespace App\Http\Controllers\Admin;

use Brackets\AdminAuth\Http\Controllers\Auth\ForgotPasswordController as AuthForgotPasswordController;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends AuthForgotPasswordController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    /**
     * Get the response for a successful password reset link.
     *
     * @param Request $request
     * @param string $response
     * @return RedirectResponse
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        $message = $response;
        if ($response === Password::RESET_LINK_SENT) {
            $message = trans('passwords.sent');
        }
        return back()->with('status', $message);
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param Request $request
     * @param string $response
     * @return RedirectResponse|JsonResponse
     */

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        $message = trans($response);

        // TODO what should be here?

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => $message]);
    }
}
