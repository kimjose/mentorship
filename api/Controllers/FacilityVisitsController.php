<?php

namespace Umb\Mentorship\Controllers;

use Umb\Mentorship\Controllers\Utils\Utility;
use Umb\Mentorship\Models\ApComment;
use Umb\Mentorship\Models\FacilityVisit;
use Umb\Mentorship\Models\Notification;
use Umb\Mentorship\Models\VisitSection;
use Illuminate\Database\Capsule\Manager as DB;
use Umb\Mentorship\Models\ActionPoint;
use Umb\Mentorship\Models\Facility;
use Umb\Mentorship\Models\Response;
use Umb\Mentorship\Models\VisitFinding;

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
            if(!hasPermission(PERM_CREATE_VISIT, $this->user)) throw new \Exception("Forbidden", 403);
            $attributes = ['facility_id', 'visit_date', 'latitude', 'longitude'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $existing = FacilityVisit::where('facility_id', $data['facility_id'])->where('visit_date', $data['visit_date'])->first();
            if ($existing) throw new \Exception("A similar visit already exists", 1);
            $data['latitude'] = $data['latitude'] == '' ? null : $data['latitude'];
            $data['longitude'] = $data['longitude'] == '' ? null : $data['longitude'];
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
            if(!hasPermission(PERM_CREATE_VISIT, $this->user)) throw new \Exception("Forbidden", 403);
            $attributes = ['facility_id', 'visit_date', 'latitude', 'longitude'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $existing = FacilityVisit::where('facility_id', $data['facility_id'])->where('visit_date', $data['visit_date'])->where('id', '!=', $id)->first();
            if ($existing) throw new \Exception("A similar visit already exists", 1);
            $visit = FacilityVisit::findOrFail($id);
            $data['latitude'] = $data['latitude'] == '' ? null : $data['latitude'];
            $data['longitude'] = $data['longitude'] == '' ? null : $data['longitude'];
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
            if(!hasPermission(PERM_CREATE_VISIT, $this->user)) throw new \Exception("Forbidden", 403);
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
            if(!hasPermission(PERM_CREATE_VISIT, $this->user)) throw new \Exception("Forbidden", 403);
            extract($data);
            DB::beginTransaction();
            foreach ($qid as $k => $v) {
                $prevResponse = Response::where('visit_id', $visit_id)->where('question_id', $qid[$k])->first();

                if ($prevResponse) {
                    if ($answer[$k] == null || $answer[$k] == '') {
                        $prevResponse->delete();
                    } else {
                        if ($type[$k] == 'check_opt') {
                            $prevResponse->update(['answer' => implode(",", $answer[$k])]);
                        } else {
                            $prevResponse->update(['answer' => trim($answer[$k])]);
                        }
                    }
                } else {
                    if ($answer[$k] != null && $answer[$k] != '') {
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
            }
            if ($_POST['submitted'])
                DB::statement("update visit_sections set submitted = 1 where visit_id={$visit_id} and section_id = {$section_id}");
            DB::commit();
            $this->response(SUCCESS_RESPONSE_CODE, 'Response submitted successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function createFinding($data){
        try {
            if(!hasPermission(PERM_CREATE_VISIT, $this->user)) throw new \Exception("Forbidden", 403);
            $attributes = ['visit_id', 'description'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $data['created_by'] = $this->user->id;
            VisitFinding::create($data);
            self::response(SUCCESS_RESPONSE_CODE, "Finding created successfully...");
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function updateFinding($id, $data){
        try {
            if(!hasPermission(PERM_CREATE_VISIT, $this->user)) throw new \Exception("Forbidden", 403);
            $attributes = [ 'description'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $data['created_by'] = $this->user->id;
            $finding = VisitFinding::findOrFail($id);
            $finding->update($data);
            self::response(SUCCESS_RESPONSE_CODE, "Finding updated successfully...");
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function createActionPoint($data)
    {
        try {
            if(!hasPermission(PERM_CREATE_VISIT, $this->user)) throw new \Exception("Forbidden", 403);
            $assign_to = [];
            $attributes = ['title', 'description', 'due_date'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            DB::beginTransaction();
            if (isset($data['visit_id'])) {
                $fv = FacilityVisit::findOrFail($data['visit_id']);
                $data['facility_id'] = $fv->facility_id;
            }
            extract($data);
            $assignTo = implode(',', $assign_to);
            $data['created_by'] = $this->user->id;
            $data['assign_to'] = $assignTo;
            $facility = Facility::findOrFail($data['facility_id']);
            $ap = ActionPoint::create($data);
            if(isset($data['finding_id'])){
                $finding = VisitFinding::findOrFail($data['finding_id']);
                $aps = explode(',', $finding->ap_ids);
                $aps[] = $ap->id;
                $finding->ap_ids = implode(',', $aps);
            }
            foreach ($assign_to as $userId) {
                Notification::create([
                    'user_id' => $userId, 'message' => "You have been assigned an action point( {$title} - {$facility->name})"
                ]);
            }
            DB::commit();
            self::response(SUCCESS_RESPONSE_CODE, 'Action Point created successfully.');
        } catch (\Throwable $th) {
            DB::rollback();
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function updateActionPoint($id, $data)
    {
        try {
            if(!hasPermission(PERM_CREATE_VISIT, $this->user)) throw new \Exception("Forbidden", 403);
            $attributes = ['visit_id', 'question_id', 'title', 'description', 'due_date'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $ap = ActionPoint::findOrFail($id);
            $ap->update($data);
            self::response(SUCCESS_RESPONSE_CODE, 'Action Point updated successfully.');
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function addApComment($data)
    {
        try {
            $attributes = ['ap_id', 'comment'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $ap = ActionPoint::findOrFail($data['ap_id']);
            $data['user_id'] = $this->user->id;
            if ($ap->status === "Done") throw new \Exception("This action point has been marked as done and no further comments can be added.");
            ApComment::create($data);
            if ($this->user->id != $ap->created_by) $this->createNotification($ap->created_by, "Someone commented on action point {$ap->title}");
            self::response(SUCCESS_RESPONSE_CODE, 'Comment added successfully.');
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function markApAsDone($data)
    {
        try {
            if(!hasPermission(PERM_CREATE_VISIT, $this->user)) throw new \Exception("Forbidden", 403);
            $attributes = ['id'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $ap = ActionPoint::findOrFail($data['id']);
            if ($ap->status === 'Done') throw new \Exception('This action has already been marked as done.');
            $ap->status = "Done";
            $ap->save();
            self::response(SUCCESS_RESPONSE_CODE, "The action point has been marked as done.");
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }
}
