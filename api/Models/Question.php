<?php
namespace Umb\Mentorship\Models;
use Illuminate\Database\Eloquent\Model;

class Question extends Model{

    protected $fillable = ["question", "type", 'options', "order", "frequency", "section_id", "created_by"];

}
