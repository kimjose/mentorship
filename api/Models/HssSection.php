<?php
namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class HssSection extends Model
{
    protected $table = 'hss_sections';
    protected $fillable = ['name'];
 
    public function questions()
    {
        return $this->hasMany(HssQuestion::class, 'section_id');
    }
}
