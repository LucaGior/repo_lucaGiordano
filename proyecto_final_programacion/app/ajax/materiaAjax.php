<?php

    require_once "../../config/app.php";
    require_once "../views/inc/session_start.php";
    require_once "../../autoload.php";

    use app\controllers\materiaController;
    $insMateria = new materiaController();
    // Comprobar si es una solicitud GET para obtener materias
        if (isset($_GET['institucion_id'])) {
            
            $institucionId = $_GET['institucion_id'];
            
            // Obtener materias por institución y devolver en formato JSON
            header('Content-Type: application/json');
            echo json_encode($insMateria->obtenerMateriasPorInstitucion($institucionId));
            exit;
        }

    if (isset($_POST['modulo_materia'])) {
        
        

        if ($_POST['modulo_materia']=="registrar") {
            echo $insMateria->registrarMateriaControlador();
        }
        if ($_POST['modulo_materia']=="eliminar") {
            echo $insMateria->eliminarMateriaControlador();
        }
        
    } else {
        session_destroy();
        header("Location: ".APP_URL."login/");
    }
    