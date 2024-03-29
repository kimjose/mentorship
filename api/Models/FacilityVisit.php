<?php

namespace Umb\Mentorship\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityVisit extends Model{

    protected $fillable = ['facility_id', 'visit_date', 'latitude', 'longitude', 'created_by', 'closed'];

    public function getCreator(){
        return User::find($this->created_by);
    }

    /**
     * @return Facility
     */
    public function getFacility(){
        return Facility::find($this->facility_id);
    }

}
