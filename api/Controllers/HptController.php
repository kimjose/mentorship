<?php
namespace Umb\Mentorship\Controllers;

use Umb\Mentorship\Controllers\Utils\Utility;
use Umb\Mentorship\Models\HptSection;

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
}
