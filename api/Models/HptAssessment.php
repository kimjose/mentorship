<?php
namespace Umb\Mentorship\Models;
use Illuminate\Database\Eloquent\Model;

class HptAssessment extends Model
{
    protected $table = 'hpt_assessments';

    protected $fillable = [
        'facility_id',
        'date_of_interview',
        'interviewer_id',
        'sub_county',
        'ward', 'facility_ownership',
        'facility_ownership_other',
        'keph_level',
        'review_period_start',
        'review_period_end'
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facility_id');
    }

    public function interviewer()
    {
        return $this->belongsTo(User::class, 'interviewer_id');
    }

}
