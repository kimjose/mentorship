<?php

namespace Umb\Mentorship\Controllers;

use Umb\Mentorship\Controllers\Utils\Constants;
use Umb\Mentorship\Controllers\Utils\Utility;
use Umb\Mentorship\Models\Program;

class ProgramsController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getPrograms(){
        $this->response(Constants::SUCCESS_RESPONSE_CODE, "Here you go...", Program::all());
    }

    public function createProgram($data){
        try {
            $attributes = ['name', 'created_by'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            $exists = Program::where('name', $data['name'])->get();
            if(sizeof($exists) > 0 ) throw new \Exception("Program already exists", -2);
            if (isset($_FILES['upload_file'])){
                $logo = Utility::uploadFile($data['name'] . '_logo');
                $data['logo'] = $logo;
            }
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            Program::create($data);
            $this->getPrograms();
        } catch (\Throwable $th){
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(Constants::PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function updateProgram($id, $data){
        try {
            $attributes = ['name'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $program = Program::findOrFail($id);
            $exists = Program::where('name', $data['name'])->where('id', '!=', $program->id )->get();
            if(sizeof($exists) > 0 ) throw new \Exception("Program already exists", -2);
            if (isset($_FILES['upload_file']) && $_FILES['upload_file']['name'] != ''){
                if(file_exists($_ENV['PUBLIC_DIR'] . $program->logo)) unlink($_ENV['PUBLIC_DIR'] . $program->logo);
                $logo = Utility::uploadFile($data['name'] . '_logo');
                $data['logo'] = $logo;
            }
            $program->update($data);
            $this->getPrograms();
        } catch (\Throwable $th){
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(Constants::PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

}
