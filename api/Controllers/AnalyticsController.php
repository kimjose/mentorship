<?php
namespace Umb\Mentorship\Controllers;

use Umb\Mentorship\Controllers\Utils\Utility;
use Illuminate\Database\Capsule\Manager as DB;

class AnalyticsController extends Controller{

    public function getAnalytics(){

    }

    public function createAnalytic($data){
        try {
            //code...
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            self::response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function updateAnalytic($id, $data){

    }

}


