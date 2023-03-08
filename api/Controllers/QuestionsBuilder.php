<?php

namespace Umb\Mentorship\Controllers;

use Umb\Mentorship\Models\Section;
use Umb\Mentorship\Models\Question;
use Umb\Mentorship\Models\Checklist;
use Umb\Mentorship\Controllers\Controller;
use Umb\Mentorship\Controllers\Utils\Utility;
use Illuminate\Database\Capsule\Manager as DB;

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

    public function publishChecklist($data)
    {
        try {
            $attributes = ['id'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $checklist = Checklist::find($data['id']);
            if ($checklist == null) throw new \Exception("Checklist not found");
            if ($checklist->status != 'draft') throw new \Exception("Checklist has already been published");
            $checklist->update([
                'status' => 'published',  'published_at' => date('Y-m-d H:i:s'), 'published_by' => $this->user->id
            ]);
            $this->response(SUCCESS_RESPONSE_CODE, 'Checklist published successfully');
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function retireChecklist($data)
    {
        try {
            $attributes = ['id', 'recreate'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $checklist = Checklist::find($data['id']);
            if ($checklist == null) throw new \Exception("Checklist not found");
            if ($checklist->status == 'draft') throw new \Exception("Checklist has already not been published");
            DB::beginTransaction();
            // Retire current checklist
            // if recreate get sections & question
            //        Recreate the checklist as draft under different id
            //        create sections and questions for new checklist
            // else  end.
            $checklist->update([
                'status' => 'retired',  'published_at' => date('Y-m-d H:i:s'), 'retired_by' => $this->user->id
            ]);
            $recreate = $data['recreate'];
            if ($recreate === true) {
                $newChk = Checklist::create([
                    'title' => $checklist->title, 'abbr' => $checklist->abbr,
                    'description' => $checklist->description, 'created_by' => $this->user->id
                ]);
                /** @var Section[] $sections */
                $sections = Section::where('checklist_id', $checklist->id)->get();
                foreach ($sections as $section) {
                    $questions = $section->getQuestions();
                    $sect = Section::create(['title' => $section->title, 'abbr' => $section->abbr, 'checklist_id' => $newChk->id, 'created_by' => $this->user->id]);
                    foreach ($questions as $question) {
                        $q = Question::create([
                            'question' => $question->question, 'frequency_id' => $question->frequency_id, 'frm_option' => $question->frm_option,
                            'type' => $question->type, 'order_by' => $question->order_by, 'section_id' => $sect->id, 'created_by' => $this->user->id
                        ]);
                    }
                }
            }
            DB::commit();
            self::response(SUCCESS_RESPONSE_CODE, 'Checklist retired successfully.');
        } catch (\Throwable $th) {
            DB::rollback();
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
            // echo 'Labels are,....' . json_encode($labels);
            if ($type != 'textfield_s' && $type != 'number_opt') {
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
                "question" => $question, "frequency_id" => $data['frequency_id'], "type" => $type, 'frm_option' => $frmOption, "section_id" => $data['section_id'], "created_by" => $this->user->id
            ]);
            // $attributes = ["question", "type", 'options', "order", "frequency", "section_id"];
            // $missing = Utility::checkMissingAttributes($data, $attributes);
            // throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            // $data['created_by'] = $this->user->id;
            // Question::create($data);
            if ($q == null) throw new \Exception("Error Processing Request", 1);

            self::response(SUCCESS_RESPONSE_CODE, "Question added successfully");
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function updateQuestion($id, $data)
    {
        try {
            $question = $data['question'];
            $type = $data['type'];
            $labels = $data['label'];
            $frmOption = '';
            // echo 'Labels are,....' . json_encode($labels);
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
            $question = Question::findOrFail($id);
            $question->update([
                "question" => $question, "frequency_id" => $data['frequency_id'], "type" => $type, 'frm_option' => $frmOption, "section_id" => $data['section_id'], "created_by" => $this->user->id
            ]);
            self::response(SUCCESS_RESPONSE_CODE, "Question updated successfully.");
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }
}
