<?php

namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model{

    protected $fillable  = ['title', 'abbr', 'checklist_id', 'order', 'created_by'];

    /**
     * 
     * @return Question[]
     */
    public function getQuestions(): array{
        return Question::where("section_id", $this->id)->get();
    }

}
