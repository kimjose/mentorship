<?php

use Bramus\Router\Router;
use Umb\Mentorship\Controllers\FacilitiesController;
use Umb\Mentorship\Controllers\QuestionsBuilder;

require_once __DIR__ . "/vendor/autoload.php";

$router = new Router();

// Custom 404 Handler
$router->set404(function () {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    $notFound = file_get_contents("404.html");
    echo $notFound;
});
$router->post("/api/checklist", function(){
    $builder = new QuestionsBuilder();
    $data = json_decode(file_get_contents('php://input'), true);
    $builder->createChecklist($data);
});
$router->post("/api/checklist/{id}", function($id){
    $builder = new QuestionsBuilder();
    $data = json_decode(file_get_contents('php://input'), true);
    $builder->updateChecklist($id, $data);
});
$router->post("/api/section", function(){
    $builder = new QuestionsBuilder();
    $data = json_decode(file_get_contents('php://input'), true);
    $builder->addSection($data);
});
$router->post("/api/section/{id}", function($id){
    $builder = new QuestionsBuilder();
    $data = json_decode(file_get_contents('php://input'), true);
    $builder->updateSection($id, $data);
});
$router->post("/api/question", function(){
    $builder = new QuestionsBuilder();
    $builder->addQuestion($_POST);
});
$router->post("/api/question/{id}", function($id){
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
// Thunderbirds are go!
$router->run();
