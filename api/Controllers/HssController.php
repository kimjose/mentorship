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
            $data['user_id'] = $this->user->id;
            HssAssessment::create($data);
            $this->response(SUCCESS_RESPONSE_CODE, 'Assessment created successfully! 👍');
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

}