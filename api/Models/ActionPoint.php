<?php
namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class ActionPoint extends Model{

    protected $fillable = ['visit_id', 'question_id', 'title', 'description', 'assign_to', 'due_date', 'created_by'];    
    
}
