<?php

namespace Umb\Mentoship\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model{

    protected $fillable  = ['title', 'abbr', 'checklist_id', 'order', 'created_by'];

}
