<?php

use Bramus\Router\Router;
use Umb\Mentorship\Controllers\AnalyticsController;
use Umb\Mentorship\Controllers\Controller;
use Umb\Mentorship\Controllers\QuestionsBuilder;
use Umb\Mentorship\Controllers\FacilitiesController;
use Umb\Mentorship\Controllers\FacilityVisitsController;
use Umb\Mentorship\Controllers\UsersController;
use Umb\Mentorship\Models\VisitSection;

require_once __DIR__ . "/vendor/autoload.php";

$router = new Router();

// Custom 404 Handler
$router->set404(function () {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    $notFound = file_get_contents("404.html");
    echo $notFound;
});

$router->post("/api/user-rssequest-reset", function(){
    $data = json_decode(file_get_contents('php://input'), true);
    // UsersController::requestResetPassword($data);
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
$router->post("/api/checklist-publish", function () {
    $builder = new QuestionsBuilder();
    $data = json_decode(file_get_contents('php://input'), true);
    $builder->publishChecklist($data);
});

$router->post("/api/checklist-retire", function () {
    $builder = new QuestionsBuilder();
    $data = json_decode(file_get_contents('php://input'), true);
    $builder->retireChecklist($data);
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
    $builder->updateQuestion($id, $_POST);
});
$router->post("/api/delete_question", function () {
    $builder = new QuestionsBuilder();
    $data = json_decode(file_get_contents('php://input'), true);
    $builder->deleteQuestion($data);
});
$router->post("/api/import_questions", function () {
    $builder = new QuestionsBuilder();
    $builder->importQuestions();
});
$router->get("/api/export_checklist_to_json/{id}", function ($id) {
    $builder = new QuestionsBuilder();
    $builder->exportChecklistToJson($id);
});
$router->post("/api/import_checklist", function () {
    $builder = new QuestionsBuilder();
    $builder->importChecklistFromJson();
});
$router->post("/api/user", function () {
    $controller = new UsersController();
    $data = json_decode(file_get_contents('php://input'), true);
    $controller->createUser($data);
});
$router->post("/api/user/{id}", function ($id) {
    $controller = new UsersController();
    $data = json_decode(file_get_contents('php://input'), true);
    $controller->updateUser($id, $data);
});
$router->post("/api/user_profile_update/{id}", function($id){
    $controller = new UsersController();
    $data = json_decode(file_get_contents('php://input'), true);
    $controller->updateProfile($id, $data);
});
$router->post("/api/user_category", function () {
    $controller = new UsersController();
    $data = json_decode(file_get_contents('php://input'), true);
    $controller->createUserCategory($data);
});
$router->post("/api/user_category/{id}", function ($id) {
    $controller = new UsersController();
    $data = json_decode(file_get_contents('php://input'), true);
    $controller->updateUserCategory($id, $data);
});
$router->post("/api/user_category/{id}", function ($id) {
    $controller = new UsersController();
    $data = json_decode(file_get_contents('php://input'), true);
    $controller->updateUserCategory($id, $data);
});
$router->post("/api/user-request-reset", function(){
    $data = json_decode(file_get_contents('php://input'), true);
    UsersController::requestResetPassword($data);
});
$router->post("/api/reset-password", function(){
    $data = json_decode(file_get_contents('php://input'), true);
    UsersController::resetPassword($data);
});
$router->get('/api/notifications/{id}', function ($id) {
    $controller = new UsersController();
    $controller->getNotifications($id);
});
$router->post('/api/notification/read', function () {
    $data = json_decode(file_get_contents('php://input'), true);
    $controller = new UsersController();
    $controller->markAsRead($data);
});
$router->post('/api/notifications/read', function () {
    $data = json_decode(file_get_contents('php://input'), true);
    $controller = new UsersController();
    $controller->markAllAsRead($data);
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
$router->post('/api/team', function () {
    $data = json_decode(file_get_contents('php://input'), true);
    $controller = new FacilitiesController();
    $controller->createTeam($data);
});
$router->post('/api/team/{id}', function ($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    $controller = new FacilitiesController();
    $controller->updateTeam($id, $data);
});
$router->post('/api/add_facilities_to_team', function () {
    $controller = new FacilitiesController();
    $controller->addFacilitiesToTeam($_POST);
});
$router->post('/api/remove_facility_from_team', function () {
    $data = json_decode(file_get_contents('php://input'), true);
    $controller = new FacilitiesController();
    $controller->removeFacilityFromTeam($data);
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
$router->post('/api/approve_visit', function(){
    $data = json_decode(file_get_contents('php://input'), true);
    $controller = new FacilityVisitsController();
    $controller->approveVisit($data);
});
$router->post('/api/open_visit_section', function () {
    $data = json_decode(file_get_contents('php://input'), true);
    $controller = new FacilityVisitsController();
    $controller->openVisitSection($data);
});
$router->post('/api/response/save_draft', function () {
    $controller = new FacilityVisitsController();
    $_POST['submitted'] = 0;
    $controller->submitResponse($_POST);
});
$router->post('/api/response/submit', function () {
    $controller = new FacilityVisitsController();
    $_POST['submitted'] = 1;
    $controller->submitResponse($_POST);
});
$router->post('/api/visit_finding', function () {
    $data = json_decode(file_get_contents('php://input'), true);
    $controller = new FacilityVisitsController();
    $controller->createFinding($data);
});
$router->post('/api/visit_finding/{id}', function ($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    $controller = new FacilityVisitsController();
    $controller->updateFinding($id, $data);
});
$router->post('/api/chart_abstraction', function () {
    $controller = new FacilityVisitsController();
    $controller->createChartAbstraction($_POST);
});
$router->post('/api/action_point', function () {
    $controller = new FacilityVisitsController();
    $controller->createActionPoint($_POST);
});
$router->post('/api/action_point/{id}', function ($id) {
    $controller = new FacilityVisitsController();
    $controller->updateActionPoint($id, $_POST);
});
$router->post('/api/ap_comment', function () {
    $data = json_decode(file_get_contents('php://input'), true);
    $controller = new FacilityVisitsController();
    $controller->addApComment($data);
});
$router->post('/api/mark_as_done', function () {
    $data = json_decode(file_get_contents('php://input'), true);
    $controller = new FacilityVisitsController();
    $controller->markApAsDone($data);
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

$router->mount('/api/analytics', function () use ($router) {

    //GET
    $router->get('/', function () {
        $controller =  new AnalyticsController();
        $controller->getAnalytics();
    });
    //POST
    $data = json_decode(file_get_contents('php://input'), true);
    $router->post('/create', function() use($data) {
        $controller =  new AnalyticsController();
        $controller->createAnalytic($data);
    });
    $router->post('/update/{id}', function($id) use($data) {
        $controller =  new AnalyticsController();
        $controller->updateAnalytic($id, $data);
    });
    $router->post('/run', function(){
        $controller =  new AnalyticsController();
        $controller->runAnalytic($_POST);
    });
    $router->post('/delete-run', function() use ($data){
        $controller =  new AnalyticsController();
        $controller->deleteAnalyticRun($data);
    });
});


$router->all('/logout', function () {
    session_start();
    unset($_SESSION[$_ENV['SESSION_APP_NAME']]);
});

// Thunderbirds are go!
$router->run();
