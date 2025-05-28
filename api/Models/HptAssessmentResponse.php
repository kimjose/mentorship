<?php
namespace Umb\Mentorship\Models;
use Illuminate\Database\Eloquent\Model;

class HptAssessmentResponse extends Model
{   

    protected $table = 'hpt_assessment_responses';

    protected $fillable = [
        'assessment_id', 'assessment_commodity_id',
        'question_id',
        'answer',
        'created_by'
    ];

    public function assessment()
    {
        return $this->belongsTo(HptAssessment::class, 'assessment_id');
    }

    public function question()
    {
        return $this->belongsTo(HptQuestion::class, 'question_id');
    }
}
