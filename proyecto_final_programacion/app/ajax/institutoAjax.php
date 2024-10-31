<?php

    require_once "../../config/app.php";
    require_once "../views/inc/session_start.php";
    require_once "../../autoload.php";

    use app\controllers\institutoController;

    if (isset($_POST['modulo_instituto'])) {
        
        $insInstituto = new institutoController();

        if ($_POST['modulo_instituto']=="registrar") {
            echo $insInstituto->registrarInstitutoControlador();
        }
        if ($_POST['modulo_instituto']=="eliminar") {
            echo $insInstituto->eliminarInstitutoControlador();
        }
    } else {
        session_destroy();
        header("Location: ".APP_URL."login/");
    }
    