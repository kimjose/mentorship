<?php
namespace Umb\Mentorship\Controllers;

use Umb\Mentorship\Models\HptSection;
use Umb\Mentorship\Models\HptQuestion;
use Umb\Mentorship\Controllers\Utils\Utility;
use Illuminate\Database\Capsule\Manager as DB;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class HptController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // $this->setModel(\Umb\Mentorship\Models\Hpt::class);
    }

    public function getHptData()
    {
        // Example method to retrieve HPT data
        // return $this->model->getAllHptData();
    }

    /**
     * Retrieves all HPT sections.
     *
     * @return array
     */
    public function getSections(){
        try{
            $sections = HptSection::all();
            return $sections;
        } catch(\Throwable $th){
            Utility::logError($th->getCode(), $th->getMessage());
            return [];
        }
    }

    public function createSection($data){
        try{
            $attributes = ["name"];
            $missingAttributes = array_diff($attributes, array_keys($data));
            if (!empty($missingAttributes)) {
                throw new \Exception("Missing required attributes: " . implode(", ", $missingAttributes));
            }
            $section = new HptSection();
            $section->name = $data['name'];
            $section->linked_to_commodity = isset($data['linked_to_commodity']) ? (bool)$data['linked_to_commodity'] : false;
            $section->save();
            $this->response(200, "HPT Section created successfully", $section);
        } catch(\Throwable $th){
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }


    public function addQuestion($data)
    {
        try {
            if (!hasPermission(PERM_CHECKLIST_MANAGEMENT, $this->user)) throw new \Exception("Forbidden", 403);
            $attributes = ['question_text', 'question_type'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $question = $data['question_text'];
            $type = $data['question_type'];
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
            $q = HptQuestion::create([
                "question_text" => $question, "question_type" => $type, 'options' => $frmOption, "section_id" => $data['section_id']
            ]);
            // $attributes = ["question_text", "question_type", 'options', "section_id"];
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
            $attributes = ['question_text', 'question_type'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $q = $data['question_text'];
            $type = $data['question_type'];
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
            $question = HptQuestion::findOrFail($id);
            $question->update([
                "question_text" => $q, "question_type" => $type, 'options' => $frmOption, "section_id" => $data['section_id']
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
            /** @var HptQuestion $question */
            $question = HptQuestion::findOrFail($data['id']);
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
            /** @var HptSection */
            $section = HptSection::findOrFail($_POST['section_id']);

            // upload file to temp dir
            $tempDir = $_ENV['TEMP_DIR'];
            if (!is_dir($tempDir)) {
                mkdir($tempDir);
            }
            $filename = "sample_import_hpt_{$section->id}_" . time();
            $uploaded = Utility::uploadFile($filename, $tempDir);
            if ($uploaded === null) throw new \Exception("Could not upload file");

            // read uploaded file
            $attributes = ['question', 'type', 'options'];
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
            $answerTypes = ["text", "number", "single", "multiple"];
            $enumTypes = ['textfield_s', 'number_opt', 'radio_opt', 'check_opt'];
            foreach ($data as $datum) {
                $insert['section_id'] = $section->id;
                $question = $datum['question'];
                $type = $datum['type'];
                $optionText = '';
                if (!in_array($type, $answerTypes)) throw new \Exception("Invalid answer type for question {$question}.", 403);
                $insert['question_text'] = $question;
                $insert['question_type'] = $enumTypes[array_search($type, $answerTypes)];
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
                $insert['options'] = $optionText;
                // echo json_encode($insert);
                HptQuestion::create($insert);
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

}
