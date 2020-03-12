<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{   
    use Notifiable;

    const VERIFIED_USER = '1';
    const UNVERIFIED_USER= '0';

    const ADMIN_USER = 'true';
    const REGULAR_USER='false';

    protected $url ="/users";

    protected $table = 'users'; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'verified',
        'verification_token',
        'admin'
    ];
    

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','verification_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getUrlAttribute(){
        return route('users.show',$this->id);
    }

    public function setNameAttribute($name){
        $this->attributes['name'] = strtolower($name);
    }

    public function getNameAttribute(){
        return ucwords($this->attributes['name']);
    }

    public function setEmailAttribute($email){
        $this->attributes['email'] = strtolower($email);
    }

    public function getEmailAttribute(){
        return ucwords($this->attributes['email']);
    }

    public function isVerified()
    {
        return $this->verified === User::VERIFIED_USER;
    }

    public function isAdmin()
    {
        return $this->admin === User::ADMIN_USER;
    }

    public static function generateVerificationCode(){
        return str_random(40);
    }
}
