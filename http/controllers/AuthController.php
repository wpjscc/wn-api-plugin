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
        $request->merge([
            $this->username() => $request->input('login'),
        ]);
        // ref https://github.com/slimkit/plus/blob/35ace420d15d2b8cf3506dc85e5f1883ac432d16/app/Http/Controllers/Auth/LoginController.php#L76
        $request->validate([
            $this->username() => 'required|username',
            'password' => 'required',
        ]);
     
        $user = User::where($this->username(), $request->input('login'))->first();
     
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

     /**
     * Get the login username to be used by the controller.
     *
     * @return string
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function username(): string
    {
        return username(
            request()->input('login')
        );
    }

    public function user(Request $request)
    {
        return $this->success($request->user());
    }

}