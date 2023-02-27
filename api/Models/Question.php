<?php
namespace Umb\Mentorship\Models;
use Illuminate\Database\Eloquent\Model;

class Question extends Model{

    protected $fillable = ["question", "type", 'frm_option', "order", "frequency", "section_id", "created_by"];

    /**
     * @return Section
     */
    public function section(){
        return Section::find($this->section_id);
    }

}
