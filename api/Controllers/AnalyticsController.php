<?php

namespace Umb\Mentorship\Controllers;

use Umb\Mentorship\Controllers\Utils\Utility;
use Illuminate\Database\Capsule\Manager as DB;
use Umb\Mentorship\Models\Analytic;
use Umb\Mentorship\Models\AnalyticQuestion;

class AnalyticsController extends Controller
{

    public function getAnalytics()
    {
    }

    public function createAnalytic($data)
    {
        try {
            //{"name":"Test","description":"Test","analytic_type":"Longitudinal","checklist_id":"1","qn_type":"radio_opt","question_ids":"4"}: 
            $attributes = ['name', 'description', 'analytic_type', 'checklist_id', 'qn_type', 'question_ids'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $questions = explode(',', $data['question_ids']);
            $data['created_by'] = $this->user->id;
            unset($data['question_ids']);
            DB::beginTransaction();
            $anal = Analytic::create($data);
            for ($i = 0; $i < sizeof($questions); $i++) {
                $question = $questions[$i];
                AnalyticQuestion::create([
                    'analytic_id' => $anal->id,
                    'question_id' => $question,
                    'order_by' => $i + 1
                ]);
            }
            DB::commit();
            self::response(SUCCESS_RESPONSE_CODE, "Analytic saved.");
        } catch (\Throwable $th) {
            DB::rollback();
            Utility::logError($th->getCode(), $th->getMessage());
            self::response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function updateAnalytic($id, $data)
    {
        try{
            $attributes = ['name', 'description', 'analytic_type', 'checklist_id', 'qn_type', 'question_ids'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $questions = explode(',', $data['question_ids']);
            unset($data['question_ids']);
            $anal = Analytic::find($id);
            if($anal == null) throw new \Exception("Analytic not found.", -1);
            DB::beginTransaction();
            $anal->update($data);
            AnalyticQuestion::where('analytic_id', $anal->id)->delete();
            for ($i = 0; $i < sizeof($questions); $i++) {
                $question = $questions[$i];
                AnalyticQuestion::create([
                    'analytic_id' => $anal->id,
                    'question_id' => $question,
                    'order_id' => $i + 1
                ]);
            }
            DB::commit();
            self::response(SUCCESS_RESPONSE_CODE, "Analytic saved.");
        } catch (\Throwable $th) {
            DB::rollback();
            Utility::logError($th->getCode(), $th->getMessage());
            self::response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }
}
