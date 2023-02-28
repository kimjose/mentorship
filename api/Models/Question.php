<?php
namespace Umb\Mentorship\Models;
use Illuminate\Database\Eloquent\Model;

class Question extends Model{

    protected $fillable = ["question", "frequency_id", "type", 'frm_option', "order", "frequency", "section_id", "created_by"];

    /**
     * @return Section
     */
    public function section(){
        return Section::find($this->section_id);
    }

    /**
     * @return Frequency
     */
    public function frequency(){
        return Frequency::findOrFail($this->frequency_id);
    }

}
