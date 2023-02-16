<?php

use Bramus\Router\Router;
use Umb\Mentorship\Controllers\QuestionsBulider;

require_once __DIR__ . "/vendor/autoload.php";

$router = new Router();

// Custom 404 Handler
$router->set404(function () {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    $notFound = file_get_contents("404.html");
    echo $notFound;
});
$router->post("/api/checklist", function(){
    $builder = new QuestionsBulider();
    $data = json_decode(file_get_contents('php://input'), true);
    $builder->createChecklist($data);
});
$router->post("/api/checklist/{id}", function($id){
    $builder = new QuestionsBulider();
    $data = json_decode(file_get_contents('php://input'), true);
    $builder->updateChecklist($id, $data);
});
$router->post("/api/section", function(){
    $builder = new QuestionsBulider();
    $data = json_decode(file_get_contents('php://input'), true);
    $builder->addSection($data);
});
$router->post("/api/section/{id}", function($id){
    $builder = new QuestionsBulider();
    $data = json_decode(file_get_contents('php://input'), true);
    $builder->updateSection($id, $data);
});
$router->post("/api/question", function(){
    $builder = new QuestionsBulider();
    $data = json_decode(file_get_contents('php://input'), true);
    $builder->addQuestion($data);
});
$router->post("/api/question/{id}", function($id){
    $builder = new QuestionsBulider();
    $data = json_decode(file_get_contents('php://input'), true);
    $builder->updateQuestion($id, $data);
});


// Thunderbirds are go!
$router->run();
