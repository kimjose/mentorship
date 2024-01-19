<?php

namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticQuestion extends Model{

    protected $fillable = ['analytic_id', 'question_id', 'order_by'];

    public $timestamps = false;

    /**
     * 
     * @return Question
     */
    public function question(){
        return Question::find($this->question_id);
    }

}
