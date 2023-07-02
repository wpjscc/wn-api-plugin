<?php namespace Wpjscc\Api\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as ControllerBase;
use Wpjscc\Api\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * The Backend base controller class, used by Backend controllers.
 * The base controller services back end pages.
 *
 * @package winter\wn-backend-module
 * @author Alexey Bobkov, Samuel Georges
 */
class AuthController extends ControllerBase
{
    use \Wpjscc\Api\Traits\ApiResponse;

    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
     
        $user = User::where('email', $request->email)->first();
     
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        $sys = $request->input('sys', []);
        $devideName = sprintf('%s-%s-%s', data_get($sys, 'deviceType', 'none'), data_get($sys, 'deviceBrand', 'none'), data_get($sys, 'deviceModel', 'none'));
        return $this->success([
            'token' => $user->createToken($devideName)->plainTextToken,
            'user' => $user,
        ]);
        
    }

    public function user(Request $request)
    {
        return $this->success($request->user());
    }

}