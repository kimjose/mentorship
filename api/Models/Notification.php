<?php
namespace Umb\Mentorship\Models;

use Umb\Mentoship\Models\User;

class Notification extends \Illuminate\Database\Eloquent\Model
{

    protected $fillable = ['user_id', 'message', 'read', 'mail_sent'];

    public function getUser(){
        return User::find($this->user_id);
    }

}


