<?php
namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model{

    protected $fillable = ['first_name', 'middle_name', 'last_name', 'email', 'password', 'last_login'];

    protected $hidden = ['password'];

    public $timestamps = false;

}
