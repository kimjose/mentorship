<?php

namespace Umb\Mentorship\Controllers;

use Umb\Mentorship\Controllers\Utils\Utility;
use Umb\Mentorship\Models\Program;

class ProgramsController extends Controller
{

    public function getPrograms()
    {
        $this->response(SUCCESS_RESPONSE_CODE, "Here you go...", Program::all());
    }

    public function createProgram($data)
    {
        try {
            if (!hasPermission(PERM_USER_MANAGEMENT, $this->user)) throw new \Exception("Forbidden", 403);
            $attributes = ['name'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $exists = Program::where('name', $data['name'])->get();
            if (sizeof($exists) > 0) throw new \Exception("Program already exists", -2);
            $data['created_by'] = $this->user->id;
            $program = Program::create($data);
            self::response(SUCCESS_RESPONSE_CODE, "Program created successfully.", $program);
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function updateProgram($id, $data)
    {
        try {
            if (!hasPermission(PERM_USER_MANAGEMENT, $this->user)) throw new \Exception("Forbidden", 403);
            $attributes = ['name'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $data['created_by'] = $this->user->id;
            $program = Program::findOrFail();
            $program->name = $data['name'];
            $program->save;
            self::response(SUCCESS_RESPONSE_CODE, "User created successfully.", $program);
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }
}
