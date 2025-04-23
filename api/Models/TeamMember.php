<?php
namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model{

    protected $fillable = ['user_id', 'team_id'];

    public $primaryKey = ['user_id', 'team_id'];

    public $incrementing = false;

}
