<?php
namespace Umb\Mentorship\Models;
use Illuminate\Database\Eloquent\Model;

class HptQuestion extends Model
{
    protected $table = 'hpt_questions';

    protected $fillable = [
        'section_id',
        'question_text',
        'question_type',
        'options'
    ];

    public function section()
    {
        return $this->belongsTo(HptSection::class, 'section_id');
    }

    public function responses()
    {
        return $this->hasMany(HptAssessmentResponse::class, 'question_id');
    }
}

