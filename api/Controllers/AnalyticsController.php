<?php

namespace Umb\Mentorship\Controllers;

use Exception;
use Umb\Mentorship\Controllers\Utils\Utility;
use Illuminate\Database\Capsule\Manager as DB;
use Umb\Mentorship\Models\Analytic;
use Umb\Mentorship\Models\AnalyticQuestion;
use Umb\Mentorship\Models\AnalyticRun;
use Umb\Mentorship\Models\AnalyticRunLine;

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
        try {
            $attributes = ['name', 'description', 'analytic_type', 'checklist_id', 'qn_type', 'question_ids'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $questions = explode(',', $data['question_ids']);
            unset($data['question_ids']);
            $anal = Analytic::find($id);
            if ($anal == null) throw new \Exception("Analytic not found.", -1);
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

    public function runAnalytic($data)
    {
        try {
            $attributes = ['analytic_id', 'facility_ids', 'end_date'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            /*
            get analytic
            create run
            get questions
            loop the 
            ?*/
            $analytic = Analytic::find($data['analytic_id']);
            if ($analytic == null) throw new \Exception("Analytic not found...");
            if ($data['end_date'] == '') throw new \Exception("Invalid end date");
            if ($analytic->analytic_type == "Across Sites") {
                $data['start_date'] = $data['end_date'];
            }
            if ($data['start_date'] == '') throw new \Exception("Invalid start date...");
            if (date_create($data['start_date']) > date_create($data['end_date'])) throw new \Exception("Invalid dates...");
            extract($data);
            $facilityIds = implode(",", $facility_ids);
            // echo $facilityIds;
            DB::beginTransaction();
            $run = AnalyticRun::create([
                "analytic_id" => $analytic_id, "facility_ids" => $facilityIds, "end_date" => $end_date, "start_date" => $start_date, "created_by" => $this->user->id
            ]);
            if ($analytic->analytic_type == "Longitudinal") {
                /** @var AnalyticQuestion[] */
                $analyticQuestions = AnalyticQuestion::where('analytic_id', $analytic_id)->get();
                foreach ($analyticQuestions as $analyticQuestion) {
                    $question = $analyticQuestion->question();
                    $query = "select r.*, fv.facility_id, fv.visit_date, fv.facility_id from responses r left join facility_visits fv on fv.id = r.visit_id where r.question_id = {$question->id} and fv.visit_date BETWEEN '{$start_date}' and '{$end_date}' and fv.facility_id in ({$facilityIds});";
                    // echo $query;
                    $responses = DB::select($query);
                    // print_r($responses);
                    foreach ($responses as $response) {
                        if ($question->type == "number_opt") {
                            $line = AnalyticRunLine::create([
                                "analytic_run_id" => $run->id,
                                "facility_id" => $response->facility_id,
                                "question_id" => $response->question_id,
                                "answer" => $response->answer,
                                "answer_value" => $response->answer,
                                "visit_date" => $response->visit_date
                            ]);
                            // print_r($line);
                        }
                        // TODO for the rest
                    }
                }
            } else if ($analytic->analytic_type == "Across Sites") {
            }
            DB::commit();
            self::response(SUCCESS_RESPONSE_CODE, "Successful");
        } catch (\Throwable $th) {
            DB::rollback();
            Utility::logError($th->getCode(), $th->getMessage());
            self::response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function deleteAnalyticRun($data){
        try{
            $attributes = ['id'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $run = AnalyticRun::find($data['id']);
            if($run != null){
                $run->delete();
            }
            self::response(SUCCESS_RESPONSE_CODE, "Deleted successfuly.");
        } catch(\Throwable $th){
            Utility::logError($th->getCode(), $th->getMessage());
            self::response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }
}
