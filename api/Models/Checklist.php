<?php 

namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class Checklist extends Model{

    protected $fillable  = ['title', 'description', 'abbr', 'created_by'];
    
    /**
     * 
     * @return Section[]
     */
    public function getSections(): array{
        return Section::where("checklist_id", $this->id)->get();
    }

}