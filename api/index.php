<?php

//use Controllers\MainController;

include "Db.php";
include "Models/BaseModel.php";
include "Controllers/MainController.php";

$controller = new Controller();

if (isset($_GET['action']) && !empty($_GET['action'])) {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $controller->{$_GET['action']}($data);
}
