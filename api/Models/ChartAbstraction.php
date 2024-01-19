<?php

namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class ChartAbstraction extends Model
{

    protected $fillable = ['visit_id', 'created_by', 'ccc_number', 'age', 'ap_ids'];

    /** @return ChartAbstractionGap[] */
    public function gaps()
    {
        return ChartAbstractionGap::where('abstraction_id', $this->id)->get();
    }

    /** @return User */
    public function creator()
    {
        return User::find($this->created_by);
    }
}
