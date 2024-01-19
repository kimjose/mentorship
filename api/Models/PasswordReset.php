<?php
namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model{

    protected $fillable = ['user_id', 'token', 'is_used', 'expires_at'];
    
}
