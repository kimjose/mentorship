<?php
namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class HssQuestion extends Model
{
    protected $table = 'hss_questions';
    protected $fillable = ['section_id', 'question'];

    public function section()
    {
        return $this->belongsTo(HssSection::class, 'section_id');
    }
}
