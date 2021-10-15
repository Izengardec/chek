<?php
// Данные для отправки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include_once '../config/database.php';
include_once '../objects/create_test.php';
require_once  '../vendor/autoload.php';

$database = new Database();
$db = $database->getConnection();

// инициализируем объект
$create_test = new CreateTest($db);
$data = json_decode($_GET['data']);
if (
    !empty($data->subj_id) &&
    !empty($data->title) &&
    !empty($data->user_id) &&
    is_int($data->subj_id) &&
    is_int($data->user_id)
) {
    //подключаем xss защиту
    $config = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);
    // устанавливаем значения свойств
    $create_test->subj_id = $data->subj_id;
    $create_test->title = $purifier->purify($data->title);
    $create_test->user_id = $data->user_id;
    if($create_test->create()){

        // установим код ответа - 201 создано
        http_response_code(201);
        if ($create_test->get_last_id()){
            echo json_encode(array("success" => true,'ins_id' => $create_test->ins_id), JSON_UNESCAPED_UNICODE);
        }
        else{
            // установим код ответа - 503 сервис недоступен
            http_response_code(503);
            // сообщим пользователю
            echo json_encode(array("success" => false,'reason' => 'Невозможно создать запись1.'), JSON_UNESCAPED_UNICODE);
        }
    }
    else {
        // установим код ответа - 503 сервис недоступен
        http_response_code(503);
        // сообщим пользователю
        echo json_encode(array("success" => false,'reason' => 'Невозможно создать запись2.'), JSON_UNESCAPED_UNICODE);
    }
}

// сообщим пользователю что данные неполные
else {

    // установим код ответа - 400 неверный запрос
    http_response_code(400);

    // сообщим пользователю
    echo json_encode(array("success" => false,'reason' => 'Невозможно создать запись. Данные неполные.'), JSON_UNESCAPED_UNICODE);
}

?>