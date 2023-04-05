<?php
namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class ChartAbstraction extends Model{

    protected $fillable = ['visit_id', 'created_by', 'ccc_number', 'age', 'ap_ids'];

}
