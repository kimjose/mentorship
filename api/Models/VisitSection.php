<?php 
namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class VisitSection extends Model{

    protected $fillable = ['visit_id', 'section_id', 'user_id', 'submitted'];

    public $primaryKey = ['visit_id', 'section_id'];

    public $incrementing = false;

}
