<?php

namespace Umb\Mentorship\Controllers;

use Umb\Mentorship\Controllers\Utils\Utility;
use Umb\Mentoship\Models\Checklist;
use Umb\Mentoship\Models\Section;

class QuestionsBulider extends Controller
{

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

    public function createQuestion($data)
    {
        try {
            $attributes = ["question", "type", 'options', "order", "frequency", "section_id"];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $data['created_by'] = $this->user->id;
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }
}
