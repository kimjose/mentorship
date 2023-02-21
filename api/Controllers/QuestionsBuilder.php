<?php

namespace Umb\Mentorship\Controllers;

use Umb\Mentorship\Models\Section;
use Umb\Mentorship\Models\Question;
use Umb\Mentorship\Models\Checklist;
use Umb\Mentorship\Controllers\Controller;
use Umb\Mentorship\Controllers\Utils\Utility;

class QuestionsBuilder extends Controller
{

    /**
     * 
     * @return []
     */
    public function getChecklists()
    {
        try {
            $checklists = Checklist::all();
            foreach ($checklists as $checklist) {
                $sections = $checklist->getSections();
                foreach ($sections as $section) {
                    $questions = $section->getQuestions();
                    $section->questions = $questions;
                }
                $checklist->sections = $sections;
            }
            return $checklists;
        } catch (\Throwable $th) {
            echo $th->getMessage();
            Utility::logError($th->getCode(), $th->getMessage());
            return [];
        }
    }

    public function createChecklist($data)
    {
        try {
            $attributes = ['title', 'description', 'abbr'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $data['created_by'] = $this->user->id;
            Checklist::create($data);
            self::response(SUCCESS_RESPONSE_CODE, 'Checklist created successfully');
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function updateChecklist($id, $data)
    {
        try {
            $attributes = ['title', 'description', 'abbr'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $checklist = Checklist::findOrFail($id);
            $checklist->update($data);
            self::response(SUCCESS_RESPONSE_CODE, 'Checklist updated successfully');
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function addSection($data)
    {
        try {
            $attributes = ['title', 'abbr', 'checklist_id'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $data['created_by'] = $this->user->id;
            Section::create($data);
            self::response(SUCCESS_RESPONSE_CODE, "Section created successfuly");
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function updateSection($id, $data)
    {
        try {
            $attributes = ['title', 'abbr', 'checklist_id'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $section = Section::findOrFail($id);
            $section->update($data);
            self::response(SUCCESS_RESPONSE_CODE, "Section updated successfuly");
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function addQuestion($data)
    {
        try {
            $question = $data['question'];
            $type = $data['type'];
            $labels = $data['label'];
            $frmOption = '';
            echo 'Labels are,....' . json_encode($labels);
            if ($type != 'textfield_s') {
                $arr = array();
                foreach ($labels as $k => $v) {
                    $i = 0;
                    while ($i == 0) {
                        $k = substr(str_shuffle(str_repeat($x = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(5 / strlen($x)))), 1, 5);
                        if (!isset($arr[$k]))
                            $i = 1;
                    }
                    $arr[$k] = $v;
                }
                
                $frmOption = json_encode($arr);
            }
            $q = Question::create([
                "question" => $question, "type" => $type, 'frm_option' => $frmOption, "section_id" => $data['section_id'], "created_by" => $this->user->id
            ]);
            // $attributes = ["question", "type", 'options', "order", "frequency", "section_id"];
            // $missing = Utility::checkMissingAttributes($data, $attributes);
            // throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            // $data['created_by'] = $this->user->id;
            // Question::create($data);
            if($q == null) throw new \Exception("Error Processing Request", 1);
            
            self::response(SUCCESS_RESPONSE_CODE, "Question added successfully");
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function updateQuestion($id, $data)
    {
        try {
            $attributes = ["question", "type", 'options', "order", "frequency", "section_id"];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $question = Question::findOrFail($id);
            $question->update($data);
            self::response(SUCCESS_RESPONSE_CODE, "Question updated successfully.");
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }
}
