<?php

namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticRunLine extends Model
{

    protected $fillable = ["analytic_run_id", "facility_id", "question_id", "answer", "answer_value", "visit_date"];

    public $timestamps = false;
}
