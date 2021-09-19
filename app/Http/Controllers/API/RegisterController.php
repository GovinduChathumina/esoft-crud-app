<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
   
class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        
        $success = $request->isMethod('put') ? User::findOrFail($request->id) : new User;

        $success->name = $request->input('name');
        $success->shop_name = $request->input('shop_name');
        $success->email = $request->input('email');
        $success->password = bcrypt($request['password']);

        if($success->save()) {
            return $this->sendResponse($success, 'User register successfully.');
        }
    }
   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);
        
        if(auth()->attempt($loginData)){ 
            $user = auth()->user(); 
            $success['token'] =  auth()->user()->createToken('authToken')->accessToken;
            $success['name'] =  $user->name;
   
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }
}