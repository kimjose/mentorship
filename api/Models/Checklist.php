<?php 

namespace Umb\Mentoship\Models;

use Illuminate\Database\Eloquent\Model;

class Checklist extends Model{

    protected $fillable  = ['title', 'description', 'abbr', 'created_by'];
    
}
