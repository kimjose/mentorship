<?php
namespace Umb\Mentorship\Controllers;

use Umb\Mentorship\Models\HssAssessment;
use Umb\Mentorship\Controllers\Controller;
use Umb\Mentorship\Controllers\Utils\Utility;

class HssController extends Controller
{
    
    public function createAssessment($data){
        try{
            if (!hasPermission(PERM_CREATE_VISIT, $this->user)) throw new \Exception("Forbidden", 403);
            $attributes = ['county_code', 'assessment_date', 'section_id'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $existing = HssAssessment::where('county_code', $data['county_code'])->where('section_id', $data['section_id'])->first();
            throw_if($existing, new \Exception("Assessment already exists for the selected section and county"));
            $data['user_id'] = $this->user->id;
            $assessment = HssAssessment::create($data);
            $this->response(SUCCESS_RESPONSE_CODE, 'Assessment created successfully! 👍', $assessment);
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function submitAssessment($id, $data){
        try{
            if (!hasPermission(PERM_CREATE_VISIT, $this->user)) throw new \Exception("Forbidden", 403);
            $attributes = ['answers'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $assessment = HssAssessment::find($id);
            extract($data);
            $answerData = [];
            foreach ($answers as $key => $value) {
                $datum = [];
                $datum['question_id'] = $key;
                $datum['answer'] = $value;
                $datum['user_id'] = $this->user->id;
                $answerData[] = $datum;
            }
            $assessment->answers()->delete();
            $assessment->answers()->createMany($answerData);
            $assessment->status = 'submitted';
            $assessment->save();
            $this->response(SUCCESS_RESPONSE_CODE, 'Assessment submitted successfully! 👍');
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }



}