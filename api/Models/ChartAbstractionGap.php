<?php
namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class ChartAbstractionGap extends Model{

    protected $fillable = ['gap', 'abstraction_id'];

    public $timestamps = false;

}
