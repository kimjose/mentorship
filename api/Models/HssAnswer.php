<?php
namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class HssAnswer extends Model
{
    protected $table = 'hss_answers';
    protected $fillable = ['county_code','question_id', 'answer', 'assessment_date', 'user_id'];
 
    public function question()
    {
        return $this->belongsTo(HssQuestion::class, 'question_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
