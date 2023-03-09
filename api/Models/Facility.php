<?php

namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model {

    protected $table = 'facilities';

    protected $fillable = ['mfl_code', 'name', 'county_code', 'latitude', 'longitude', 'active'];

    public function getCounty(){
        return County::where('code', $this->county_code)->first();
    }


}
