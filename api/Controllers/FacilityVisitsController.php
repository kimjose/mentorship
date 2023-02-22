<?php

namespace Umb\Mentorship\Controllers;

use Umb\Mentorship\Controllers\Utils\Utility;
use Umb\Mentorship\Models\FacilityVisit;

class FacilityVisitsController extends Controller
{

    public function getVisits()
    {
        $visits = FacilityVisit::all();
        foreach ($visits as $visit) {
            $visit['creator'] = $visit->getCreator();
            $visit['facility'] = $visit->getFacility();
        }
        return $visits;
    }

    public function createVisit($data)
    {
        try {
            $attributes = ['facility_id', 'visit_date'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $existing = FacilityVisit::where('facility_id', $data['facility_id'])->where('visit_date', $data['visit_date'])->first();
            if ($existing) throw new \Exception("A similar visit already exists", 1);
            $data['created_by'] = $this->user->id;
            FacilityVisit::create($data);
            $this->response(SUCCESS_RESPONSE_CODE, 'Visit created successfully! 👍');
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function updateVisit($id, $data)
    {
        try {
            $attributes = ['facility_id', 'visit_date'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $existing = FacilityVisit::where('facility_id', $data['facility_id'])->where('visit_date', $data['visit_date'])->where('id', '!=', $id)->first();
            if ($existing) throw new \Exception("A similar visit already exists", 1);
            $visit = FacilityVisit::findOrFail($id);
            $visit->update($data);
            $this->response(SUCCESS_RESPONSE_CODE, 'Visit updated successfully! 👍');
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }
}
