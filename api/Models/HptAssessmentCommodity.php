<?php
namespace Umb\Mentorship\Models;
use Illuminate\Database\Eloquent\Model;

class HptAssessmentCommodity extends Model
{  
    protected $table = 'hpt_assessment_commodities';

    protected $fillable = [
        'assessment_id',
        'commodity_name',
        'able_to_verify',
        'reason_for_not_verifying',
        'reason_for_not_verifying_other',
        'created_by'
    ];

    public function assessment()
    {
        return $this->belongsTo(HptAssessment::class, 'assessment_id');
    }
}

