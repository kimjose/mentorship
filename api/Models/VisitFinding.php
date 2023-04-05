<?php 
namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class VisitFinding extends Model{
    
    protected $fillable = ['visit_id', 'description', 'ap_ids', 'created_by'];

    /** @return User */
    public function createdBy(){
        return User::find($this->created_by);
    }
    
}
