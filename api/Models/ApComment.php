<?php

namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class ApComment extends Model
{

    protected $fillable = ['ap_id', 'user_id', 'comment'];

    /**
     *@return User
     */
    public function creator() : User{
        return User::find($this->user_id);
    }
}