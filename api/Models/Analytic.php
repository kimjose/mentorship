<?php

namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class Analytic extends Model{

    protected $fillable = ['name', 'description', 'created_by', 'analytic_type', 'checklist_id', 'qn_type'];

    /** @return User */
    public function creator()
    {
        return User::find($this->created_by);
    }

}
