<?php

namespace App\Http\Controllers\Auth;

use App\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Auth;
use App\Learner;
use App\Tutor;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    
     public function redirectTo()
     {

        if (Auth::user()->typeOfUser =='tutor')
        {
            return route('tutprofile');
        }
        else
        {
            return route('stdindex');
        }

     }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:70',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string|max:15',
            'typeOfUser' => 'required|string',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
       
        if( $data['typeOfUser'] == 'student')
        {
      
           Learner::create([
               'name' => $data['name'],
               'email' => $data['email'],
               'phone' => $data['phone'],
              
           ]);

       }
       elseif($data['typeOfUser'] == 'tutor')
       {
           Tutor::create([
               'name' => $data['name'],
               'email' => $data['email'],
               'phone' => $data['phone'],
               
               
           ]);
       }

        $user= User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'typeOfUser' => $data['typeOfUser'],
            'password' => bcrypt($data['password']),
        ]);

        return $user;
      
    }
}
