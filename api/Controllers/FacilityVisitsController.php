<?php

namespace Umb\Mentorship\Controllers;

use Umb\Mentorship\Controllers\Utils\Utility;
use Umb\Mentorship\Models\FacilityVisit;
use Umb\Mentorship\Models\VisitSection;
use Illuminate\Database\Capsule\Manager as DB;
use Umb\Mentorship\Models\Response;

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

    public function openVisitSection($data)
    {
        try {
            $attributes = ['visit_id', 'section_id'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $visitId = $data['visit_id'];
            $sectionId = $data['section_id'];
            $userId = $this->user->id;
            // $openedByOther = VisitSection::where(function($q) use($userId, $sectionId, $visitId){
            //     $q->where('visit_id', $visitId);
            //     $q->where('section_id', $sectionId);
            //     $q->where('user_id', '!=', $userId);
            // })->get();
            $openedByOther = VisitSection::where('visit_id', $visitId)->where('section_id', $sectionId)->first();
            if ($openedByOther == null) {
                $data['user_id'] = $userId;
                VisitSection::create($data);
            } elseif ($openedByOther->user_id != $userId) throw new \Exception("This section has been opened by another user", 1);
            $this->response(SUCCESS_RESPONSE_CODE, 'Go on;.... 🤸');
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function submitResponse($data)
    {
        try {
            extract($data);
            DB::beginTransaction();
            foreach ($qid as $k => $v) {
                $prevResponse = Response::where('visit_id', $visit_id)->where('question_id', $qid[$k])->first();

                if ($prevResponse) {
                    if ($type[$k] == 'check_opt') {
                        $prevResponse->update(['answer' => implode(",", $answer[$k])]);
                    } else {
                        $prevResponse->update(['answer' => trim($answer[$k])]);
                    }
                } else {
                    $response = [
                        "visit_id" => $visit_id, "question_id" => $qid[$k], "created_by" => $this->user->id
                    ];
                    if ($type[$k] == 'check_opt') {
                        $response["answer"] = implode(",", $answer[$k]);
                    } else {
                        $response['answer'] = trim($answer[$k]);
                    }
                    Response::create($response);
                }
            }
            DB::commit();
            $this->response(SUCCESS_RESPONSE_CODE, 'Response submitted successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function createActionPoint($data){
        try {
            $attributes = ['visit_id', 'section_id'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

}
