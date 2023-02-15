<?php 

namespace Umb\Mentoship\Models;

use Illuminate\Database\Eloquent\Model;

class CheckList extends Model{

    protected $fillable  = ['name', 'abbr', 'created_by'];
    
}
