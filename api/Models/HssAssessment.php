<?php

namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class HssAssessment extends Model
{
    protected $table = 'hss_assessments';
    protected $fillable = ['county_code', 'assessment_date', 'user_id', 'section_id', 'status'];

    public function answers()
    {
        return $this->hasMany(HssAnswer::class, 'assessment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function section()
    {
        return $this->belongsTo(HssSection::class, 'section_id');
    }
    public function county()
    {
        return $this->belongsTo(County::class, 'county_code', 'code');
    }
}
