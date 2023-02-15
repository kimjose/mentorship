<?php
namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model{

    protected $fillable = ['firstname', 'middlename', 'lastname', 'email', 'password'];

    protected $hidden = ['password'];

    public $timestamps = false;

}
