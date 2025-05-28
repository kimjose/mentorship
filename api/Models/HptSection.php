<?php
namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class HptSection extends Model
{
    protected $table = 'hpt_sections';

    protected $fillable = [
        'name',
        'linked_to_commodity',
    ];


    public function questions()
    {
        return $this->hasMany(HptQuestion::class, 'section_id');
    }
}

