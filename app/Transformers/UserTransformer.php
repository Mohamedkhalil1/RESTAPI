<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
  
    public function transform(User $user)
    {
        return [
            'idenifier' => (int)$user->id,
            'Name'      => $user->name,
            'Email'     => $user->email,
            'isVerified' => (int)$user->verified,
            'isAdmin'    => ($user->admin ==='true'),
            'creationDate' => $user->created_at,
            'LastChangeDate' => $user->updated_at,
            'deletedDate' => isset($user->daleted_at) ? (string)$user->deleted_at : null     
        ];
    }

    public static function originAttribute($index){
        $attributes = [
            'idenifier' => 'id',
            'Name'      => 'name',
            'Email'     => 'email',
            'isVerified' => 'verified',
            'isAdmin'    => 'admin',
            'creationDate' => 'created_at',
            'LastChangeDate' =>'updated_at',
            'deletedDate' => 'daleted_at'  
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null ; 
    }
}
