<?php
namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model{

    protected $fillable = ['first_name', 'middle_name', 'last_name', 'email', 'phone_number', 'password', 'last_login', 'facility_id', 'active', 'created_by', 'program_id'];

    protected $hidden = ['password'];

}
