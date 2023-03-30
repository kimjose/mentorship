<?php
namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class ActionPoint extends Model{

    protected $fillable = ['visit_id', 'facility_id', 'question_id', 'title', 'description', 'assign_to', 'due_date', 'created_by'];   
    
    /**
     * @return Question
     */
    public function question(){
        return Question::find($this->question_id);
    }


    public function creator(){
        return User::find($this->created_by);
    }

}
