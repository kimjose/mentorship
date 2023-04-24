<?php 

namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class Checklist extends Model{

    protected $fillable  = ['title', 'description', 'abbr', 'created_by', 'status', 'published_at', 'published_by', 'retired_at', 'retired_by'];
    
    /**
     * 
     * @return Section[]
     */
    public function getSections(){
        return Section::where("checklist_id", $this->id)->get();
    }

}
