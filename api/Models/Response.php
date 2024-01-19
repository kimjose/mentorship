<?php
namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model{

    protected $fillable = ['visit_id', 'question_id', 'answer', 'created_by'];
    
}
