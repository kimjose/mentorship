<?php

namespace Umb\Mentorship\Controllers;

use Umb\Mentorship\Controllers\Utils\Utility;
use Umb\Mentorship\Models\Facility;
use Umb\Mentorship\Models\Team;
use Illuminate\Database\Capsule\Manager as DB;

class FacilitiesController extends Controller
{


    public function __construct()
    {
        parent::__construct();
    }

    public function addFacility($data){
        try {
            $attributes = ['mfl_code', 'name', 'county_code', 'latitude', 'longitude', 'active', 'team_id'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $exists = Facility::where('mfl_code', $data['mfl_code'])->first();
            throw_if($exists != null, new \Exception("Facility already exists.", -1));
            $data['latitude'] = $data['latitude'] == '' ? null : $data['latitude'];
            $data['longitude'] = $data['longitude'] == '' ? null : $data['longitude'];
            $data['team_id'] = $data['team_id'] == '' ? null : $data['team_id'];
            Facility::create($data);
            $this->response(SUCCESS_RESPONSE_CODE, "The facility has been added successfully");
        } catch (\Throwable $th){
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function updateFacility($id, $data){
        try {
            $attributes = ['mfl_code', 'name', 'county_code', 'active', 'team_id'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $exists = Facility::where('mfl_code', $data['mfl_code'])->where('id','!=', $id)->first();
            throw_if($exists != null, new \Exception("Facility already exists.", -1));
            $facility = Facility::findOrFail($id);
            $facility->mfl_code = $data['mfl_code'];
            $facility->name = $data['name'];
            $facility->county_code = $data['county_code'];
            $facility->active = $data['active'];
            $facility->latitude = $data['latitude'] == '' ? null : $data['latitude'];
            $facility->longitude = $data['longitude'] == '' ? null : $data['longitude'];
            $facility->team_id = $data['team_id'] == '' ? null : $data['team_id'];
            $facility->save();
            $this->response(SUCCESS_RESPONSE_CODE, "The facility has been updated successfully");
        } catch (\Throwable $th){
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function getFacilities(){
        return Facility::all();
    }

    public function createTeam($data){
        try {
            $attributes = ['team_lead', 'name'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            Team::create($data);
            self::response(SUCCESS_RESPONSE_CODE, "Team created successfully.");
        } catch (\Throwable $th){
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function updateTeam($id,$data){
        try {

            $attributes = ['team_lead', 'name'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $team = Team::findOrFail($id);
            $team->update($data);
            self::response(SUCCESS_RESPONSE_CODE, "Team updated successfully.");
        } catch (\Throwable $th){
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function removeFacilityFromTeam($data){
        try {
            $attributes = ['team_id', 'facility_id'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $facility = Facility::where('team_id',$data['team_id'])->where('id', $data['facility_id'])->firstOrFail();
            $data = (array)$facility;
            $data['team_id'] = null;
            $facility->update($data);
            self::response(SUCCESS_RESPONSE_CODE, 'Facility removed from team successfully.');
        } catch (\Throwable $th){
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function addFacilitiesToTeam($data) {
        try{
            $facility_ids = [];
            $attributes = ['team_id'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            extract($data);
            DB::beginTransaction();
            foreach($facility_ids as $facilityId){
                $facility = Facility::findOrFail($facilityId);
                $facility->team_id = $team_id;
                $facility->save();
            }
            DB::commit();
            self::response(SUCCESS_RESPONSE_CODE, 'Facilities added to team.');
        } catch (\Throwable $th){
            DB::rollback();
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

}
