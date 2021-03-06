<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Mail\UserCreated;
use App\Mail\UserMailChanged;
use App\User;
use Illuminate\Support\Facades\Mail;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return $this->showAll($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ];

        $this->validate($request,$rules);
      
        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $data['verified'] = User::UNVERIFIED_USER;
        $data['verification_code'] = User::generateVerificationCode();
        $data['admin']= User::REGULAR_USER;

        $user = User::create($data);
        
        return $this->showOne($user);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
       return $this->showOne($user);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        
        $rules = [
            'email' => 'email|unique:users,email'.$user->id,
            'password' => 'min:6|confirmed',
            'admin'   => 'in:'.User::REGULAR_USER .',' . User::ADMIN_USER
        ];

        if($request->has('name')){
            $user->name = $request->name;
        }

        
        if($request->has('email') && $request->email != $user->email){
            $user->verified = User::UNVERIFIED_USER;
            $user->verification_code = User::generateVerificationCode();
            $user->email = $request->email;
        }

        if($request->has('password')){
            $user->password = bcrypt($request->password);
        }

        if($request->has('admin')){
            if(!$user->isVerified()){
                return $this->errorResponse('only verified user can modify admin field',409);
            }
            $user->admin = $request->admin;
        }

        if(!$user->isDirty()){
            return $this->errorResponse('you need to specify a different value to update',422);
        }

        $user->save();
        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['data' => $user] , 200);
    }

    public function verify($code){
        $user = User::where('verification_code' , $code)->firstOrFail();

        $user->verified = User::VERIFIED_USER;
        $user->verification_code=null;
        $user->save();

        return $this->getMessage('the account has been verified successfully!');
    }

    public function resend(User $user){
        if($user->isVerified()){
            return $this->errorResponse('this user is already verified',409);
        }

        retry(5,function() use ($user){
            Mail::to($user->email)->send(new UserCreated($user));
        },1000);
        
        return $this->getMessage('the verification email has been resend ');
    }
}
