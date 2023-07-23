<?php

namespace Umb\Mentorship\Controllers;

use Box\Spout\Common\Entity\Cell;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
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
            if (!hasPermission(PERM_CHECKLIST_MANAGEMENT, $this->user)) throw new \Exception("Forbidden", 403);
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
            if (!hasPermission(PERM_CHECKLIST_MANAGEMENT, $this->user)) throw new \Exception("Forbidden", 403);
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
            if (!hasPermission(PERM_CHECKLIST_MANAGEMENT, $this->user)) throw new \Exception("Forbidden", 403);
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
            if (!hasPermission(PERM_CHECKLIST_MANAGEMENT, $this->user)) throw new \Exception("Forbidden", 403);
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
                'status' => 'retired',  'retired_at' => date('Y-m-d H:i:s'), 'retired_by' => $this->user->id
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
            if (!hasPermission(PERM_CHECKLIST_MANAGEMENT, $this->user)) throw new \Exception("Forbidden", 403);
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
            if (!hasPermission(PERM_CHECKLIST_MANAGEMENT, $this->user)) throw new \Exception("Forbidden", 403);
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
            if (!hasPermission(PERM_CHECKLIST_MANAGEMENT, $this->user)) throw new \Exception("Forbidden", 403);
            $attributes = ['question', 'type', 'category'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $question = $data['question'];
            $type = $data['type'];
            $labels = $data['label'];
            $category = $data['category'];
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
                "question" => $question, 'category' => $category, "frequency_id" => $data['frequency_id'], "type" => $type, 'frm_option' => $frmOption, "section_id" => $data['section_id'], "created_by" => $this->user->id
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
            if (!hasPermission(PERM_CHECKLIST_MANAGEMENT, $this->user)) throw new \Exception("Forbidden", 403);
            $attributes = ['question', 'type', 'category'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $q = $data['question'];
            $type = $data['type'];
            $labels = $data['label'];
            $category = $data['category'];
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
                "question" => $q, 'category' => $category, "frequency_id" => $data['frequency_id'], "type" => $type, 'frm_option' => $frmOption, "section_id" => $data['section_id'], "created_by" => $this->user->id
            ]);
            // echo "Here here";
            self::response(SUCCESS_RESPONSE_CODE, "Question updated successfully.");
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function deleteQuestion($data)
    {
        try {
            if (!hasPermission(PERM_CHECKLIST_MANAGEMENT, $this->user)) throw new \Exception("Forbidden", 403);
            $attributes = ['id'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            /** @var Question $question */
            $question = Question::findOrFail($data['id']);
            $section = $question->section();
            $checklist = $section->checklist();
            if ($checklist->status != 'draft') throw new \Exception("Delete not allowed", -1);
            $question->delete();
            self::response(SUCCESS_RESPONSE_CODE, "Question deleted successfully.");
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function importQuestions()
    {
        try {
            if (!hasPermission(PERM_CHECKLIST_MANAGEMENT, $this->user)) throw new \Exception("Forbidden", 403);
            $missing = Utility::checkMissingAttributes($_POST, ["section_id"]);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $missing = Utility::checkMissingAttributes($_FILES, ["upload_file"]);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));

            // get section and checklist --  run validations(check if the checklist is draft)
            /** @var Section */
            $section = Section::findOrFail($_POST['section_id']);
            $checklist = $section->checklist();
            if ($checklist->status != 'draft') throw new \Exception("The checklist has already been published.", 403);

            // upload file to temp dir
            $tempDir = $_ENV['TEMP_DIR'];
            if (!is_dir($tempDir)) {
                mkdir($tempDir);
            }
            $filename = "sample_import_{$section->id}_" . time();
            $uploaded = Utility::uploadFile($filename, $tempDir);
            if ($uploaded === null) throw new \Exception("Could not upload file");

            // read uploaded file
            $attributes = ['question', 'category', 'frequency', 'type', 'options'];
            $data = [];
            $reader = ReaderEntityFactory::createReaderFromFile($tempDir . $uploaded);
            $reader->open($tempDir . $uploaded);
            foreach ($reader->getSheetIterator() as $sheet) {
                $k = 0;
                foreach ($sheet->getRowIterator() as $row) {
                    // do stuff with the row
                    if ($k > 0) {
                        $datum = [];
                        $cells = $row->getCells();
                        for ($i = 0; $i < sizeof($cells); $i++) {
                            $cell = $cells[$i];
                            $datum[$attributes[$i]] = $cell->getValue();
                        }
                        $data[] = $datum;
                    }
                    $k++;
                }
            }
            // store results to database
            DB::beginTransaction();
            $categories = ["individual", "facility", "sdp"];
            $frequencies = ["regular", "monthly", "quarterly", "semi-annual", "annual"];
            $answerTypes = ["text", "number", "single", "multiple"];
            $enumTypes = ['textfield_s', 'number_opt', 'radio_opt', 'check_opt'];
            foreach ($data as $datum) {
                $insert['section_id'] = $section->id;
                $question = $datum['question'];
                $category = $datum['category'];
                $frequency = $datum['frequency'];
                $type = $datum['type'];
                $optionText = '';
                if (!in_array($category, $categories)) throw new \Exception("Invalid category for question {$question}.", 403);
                if (!in_array($frequency, $frequencies)) throw new \Exception("Invalid frequency for question {$question}.", 403);
                if (!in_array($type, $answerTypes)) throw new \Exception("Invalid answer type for question {$question}.", 403);
                $insert['question'] = $question;
                $insert['category'] = $category;
                switch ($frequency) {
                    case $frequencies[0]:
                        $frequencyId = 1;
                        break;
                    case $frequencies[1]:
                        $frequencyId = 2;
                        break;
                    case $frequencies[2]:
                        $frequencyId = 3;
                        break;
                    case $frequencies[3]:
                        $frequencyId = 4;
                        break;
                    case $frequencies[4]:
                        $frequencyId = 5;
                        break;
                    default:
                        $frequencyId = 1;
                        break;
                }
                $insert['frequency_id'] = $frequencyId;
                $insert['date_created'] = date('Y-m-d');
                $insert['created_by'] = $this->user->id;
                $insert['type'] = $enumTypes[array_search($type, $answerTypes)];
                if ($type == $answerTypes[2] || $type == $answerTypes[3]) { // Dropdowns
                    $options = explode(',', $datum['options']);
                    $optionArr = array();
                    foreach ($options as $option) {
                        $i = 0;
                        while ($i == 0) {
                            $k = substr(str_shuffle(str_repeat($x = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(5 / strlen($x)))), 1, 5);
                            if (!isset($arr[$k]))
                                $i = 1;
                        }
                        $optionArr[$k] = $option;
                    }
                    $optionText = json_encode($optionArr);
                }
                $insert['frm_option'] = $optionText;
                // echo json_encode($insert);
                Question::create($insert);
            }
            unlink($tempDir . $uploaded);
            DB::commit();
            self::response(SUCCESS_RESPONSE_CODE, "Import successfull.");
        } catch (\Throwable $th) {
            DB::rollback();
            Utility::logError($th->getCode(), $th->getMessage());
            self::response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function exportChecklistToJson($checklistId)
    {
        try {
            // Get checklist
            // Get sections
            // Get questions
            // Load to file and expose.
            $checklist = Checklist::find($checklistId);
            if ($checklist == null) throw new \Exception("Checklist not found.");
            $data = array();
            $data['title'] = $checklist->title;
            $data['abbr'] = $checklist->abbr;
            $data['description'] = $checklist->description;
            $data['status'] = $checklist->status;
            $sections = Section::where('checklist_id', $checklist->id)->get();
            $sectionData = array();
            foreach ($sections as $section) {
                $sectionDatum  = array();
                $sectionDatum['title'] = $section->title;
                $sectionDatum['abbr'] = $section->abbr;
                $questions = Question::where('section_id', $section->id)->get();
                $questionData = array();
                foreach ($questions as $question) {
                    $questionDatum = array();
                    $questionDatum['question'] = $question->question;
                    $questionDatum['category'] = $question->category;
                    $questionDatum['frequency_id'] = $question->frequency_id;
                    $questionDatum['type'] = $question->type;
                    $questionDatum['order_by'] = $question->order_by;
                    $questionDatum['frm_option'] = $question->frm_option;
                    $questionData[] = $questionDatum;
                }
                $sectionDatum['questions'] = $questionData;
                $sectionData[] = $sectionDatum;
            }
            $data['sections'] = $sectionData;
            $filename = $_ENV['TEMP_DIR'] . "checklist_export_" . $checklistId . ".json";
            $handle = fopen($filename, 'w');
            fwrite($handle, json_encode($data));
            fclose($handle);
            $this->returnFile($filename);
            // echo json_encode($data);
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            self::response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function importChecklistFromJson()
    {
        try {
            $tempDir = $_ENV['TEMP_DIR'];
            if (!is_dir($tempDir)) {
                mkdir($tempDir);
            }
            $uploaded = Utility::uploadFile("", $tempDir);
            if ($uploaded === null) throw new \Exception("Could not upload file");
            $handle = fopen($tempDir . $uploaded, 'r');
            $data = fread($handle, filesize($tempDir . $uploaded));
            $checklistData = json_decode($data, false);
            DB::beginTransaction();
            // create checklist
            $checklist = Checklist::create([
                "title" => $checklistData->title,
                "abbr" => $checklistData->abbr,
                "description" => $checklistData->description,
                "created_by" => $this->user->id
            ]);
            // create sections
            $sections = $checklistData->sections;
            foreach ($sections as $sectionData) {
                $section = Section::create([
                    "title" => $sectionData->title,
                    "abbr" => $sectionData->abbr,
                    "created_by" => $this->user->id,
                    "checklist_id" => $checklist->id
                ]);
                // Create questions
                $questions = $sectionData->questions;
                foreach($questions as $questionData){
                    $question = Question::create([
                        "question" => $questionData->question,
                        "category" => $questionData->category,
                        "frequency_id" => $questionData->frequency_id,
                        "type" => $questionData->type,
                        "order_by" => $questionData->order_by,
                        "frm_option" => $questionData->frm_option,
                        "created_by" => $this->user->id,
                        "section_id" => $section->id
                    ]);
                }
            }
            DB::commit();
            self::response(SUCCESS_RESPONSE_CODE, "Checklist inported successfully");
        } catch (\Throwable $th) {
            DB::rollback();
            Utility::logError($th->getCode(), $th->getMessage());
            self::response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }
}
