<?php

use Bramus\Router\Router;
use Umb\Mentorship\Controllers\Controller;
use Umb\Mentorship\Controllers\QuestionsBuilder;
use Umb\Mentorship\Controllers\FacilitiesController;
use Umb\Mentorship\Controllers\FacilityVisitsController;
use Umb\Mentorship\Models\VisitSection;

require_once __DIR__ . "/vendor/autoload.php";

$router = new Router();

// Custom 404 Handler
$router->set404(function () {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    $notFound = file_get_contents("404.html");
    echo $notFound;
});
$router->post("/api/checklist", function () {
    $builder = new QuestionsBuilder();
    $data = json_decode(file_get_contents('php://input'), true);
    $builder->createChecklist($data);
});
$router->post("/api/checklist/{id}", function ($id) {
    $builder = new QuestionsBuilder();
    $data = json_decode(file_get_contents('php://input'), true);
    $builder->updateChecklist($id, $data);
});
$router->post("/api/section", function () {
    $builder = new QuestionsBuilder();
    $data = json_decode(file_get_contents('php://input'), true);
    $builder->addSection($data);
});
$router->post("/api/section/{id}", function ($id) {
    $builder = new QuestionsBuilder();
    $data = json_decode(file_get_contents('php://input'), true);
    $builder->updateSection($id, $data);
});
$router->post("/api/question", function () {
    $builder = new QuestionsBuilder();
    $builder->addQuestion($_POST);
});
$router->post("/api/question/{id}", function ($id) {
    $builder = new QuestionsBuilder();
    $data = json_decode(file_get_contents('php://input'), true);
    $builder->updateQuestion($id, $data);
});


$router->post('/api/facility', function () {
    $data = json_decode(file_get_contents('php://input'), true);
    $controller = new FacilitiesController();
    $controller->addFacility($data);
});
$router->post('/api/facility/{id}', function ($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    $controller = new FacilitiesController();
    $controller->updateFacility($id, $data);
});
$router->get('/api/visits', function () {
    $controller = new FacilityVisitsController();
    Controller::response(SUCCESS_RESPONSE_CODE, '', $controller->getVisits());
});
$router->post('/api/visit', function () {
    $data = json_decode(file_get_contents('php://input'), true);
    $controller = new FacilityVisitsController();
    $controller->createVisit($data);
});

$router->post('/api/visit/{id}', function ($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    $controller = new FacilityVisitsController();
    $controller->updateVisit($id, $data);
});
$router->post('/api/open_visit_section', function () {
    $data = json_decode(file_get_contents('php://input'), true);
    $controller = new FacilityVisitsController();
    $controller->openVisitSection($data);
});

$router->post('/api/response/submit', function () {
    $controller = new FacilityVisitsController();
    $controller->submitResponse($_POST);
});
$router->post('/api/visit_section', function () {
    try {
        VisitSection::create([
            'visit_id' => 3, 'section_id' => 1, 'user_id' => 1
        ]);
    } catch (\Throwable $th) {
        echo $th->getMessage();
    }
});
$router->get('/api/visit_sections', function () {
    try {
        Controller::response(SUCCESS_RESPONSE_CODE, 'Voala', VisitSection::find([3, 1]));
    } catch (\Throwable $th) {
        echo $th->getMessage();
    }
});

// Thunderbirds are go!
$router->run();
