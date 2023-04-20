<?php

namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class Analytic extends Model{

    protected $fillable = ['name', 'description', 'created_by', 'analytic_type', 'checklist_id', 'qn_type'];

}
