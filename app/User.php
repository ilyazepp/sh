<?php

namespace SHammer;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'login', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get user's products
     */
    public function products()
    {
        return $this->hasMany('App\Product');
    }

    /**
     * Try to login user based on his login/email and password
     */
    public static function checkAuth(Request $request) 
    {
        $result = false;
        if (request()->header('email')) {
            $result = self::_check($request);
        } elseif (request()->header('login')) {
            $result = self::_check($request, 'login');
        } else {
            return response()->json(
                [
                    'code'      =>  401,
                    'message'   =>  'Need to provide login or email'
                ], 401);
        }

        return $result;
    }

    protected static function _check(Request $request, $type = 'email')
    {
        if ($type == 'email') {
            $loginData['email'] = $request->header('email');
        } else {
            $loginData['login'] = $request->header('login');
        }

        $loginData['password'] = $request->header('password');

        $validateString = $type == 'email' ? 'required|email' : 'required';

        $validator = \Validator::make($loginData,
            [
                'password' => 'required|min:6',
                $type => $validateString
            ]       
        );

        if ($validator->fails())
        {
            return response()->json(
                [
                    'code'      =>  401,
                    'message'   =>  $validator->errors()->all()
                ], 401);
        }

        try {
            $result = \Auth::onceBasic($type, $loginData);
        } catch (UnauthorizedHttpException $e) {
            return response()->json(
                [
                    'code'      =>  401,
                    'message'   =>  $e->getMessage()
                ], 401);
        }

        return true;
    }
}
