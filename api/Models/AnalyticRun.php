<?php

namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticRun extends Model{

    protected $fillable = ["analytic_id", "created_by", "start_date", "end_date", "facility_ids"];

    /** @return User */
    public function creator()
    {
        return User::find($this->created_by);
    }

}