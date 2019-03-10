<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Validator;
use http\Env\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    public $response = array('error' => false, 'error_list' => array(), 'data' => array(), 'message' => '');

    /**
     * LoginController constructor.
     * @param array $response
     */


    public function loginView(){
            if(Auth::check()){
                return \redirect('/dashboard');
            }
            return view('login',$this->dataSendView);

    }

    public function login()
    {
        $rule = [
            'username'=>'required',
            'password'=>'required'
        ];
        $validator = Validator::make($this->request, $rule);
        if ($validator->fails()) {
            $response['message'] = $validator->errors();
            $response['errors'] = true;
            Session::flash('message', 'Tên đăng nhập hoặc mật khẩu không được để trống');
            return Redirect::back()->withInput(Input::all());
        }

        if (!empty($this->request['username']) && !empty($this->request['password'])) {

            if(Auth::attempt(['username' => $this->request['username'], 'password' => $this->request['password']])){
                return \redirect('/dashboard');
            }else{
                Session::flash('authentication_fail', 'Tên đăng nhập hoặc mật khẩu không đúng');
                return Redirect::back()->withInput(Input::all());
            }

        } else {
            return $this->jsonReponse([
                'message' => "Username or Password empty"
            ], 500);
        }

    }

    public function logout(){

        Auth::logout();
        return redirect('/authentication/loginView');

    }

}
