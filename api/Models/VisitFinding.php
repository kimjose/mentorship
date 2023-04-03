<?php 
namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class VisitFinding extends Model{
    
    protected $fillable = ['visit_id', 'description', 'ap_ids', 'created_by'];
    
}
