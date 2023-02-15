<?php

use Illuminate\Database\Eloquent\Model;

class Question extends Model{

    protected $fillable = ["question", "type", 'options', "order", "section_id", "created_by"];

}
