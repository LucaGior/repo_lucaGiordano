<?php

    require_once "../../config/app.php";
    require_once "../views/inc/session_start.php";
    require_once "../../autoload.php";

    use app\controllers\alumnoController;
    $insAlumno = new alumnoController();
    



    // Comprobar si es una solicitud GET para obtener materias
    if (isset($_GET['materia_id'])) {
                
        $materia_id = $_GET['materia_id'];
        
        // Obtener materias por institución y devolver en formato JSON
        header('Content-Type: application/json');
        echo json_encode($insAlumno->obtenerAlumnosPorMateria($materia_id));
        exit;
    }

    if (isset($_POST['modulo_alumno'])) {
        
        
        

        if ($_POST['modulo_alumno']=="registrar") {
            echo $insAlumno->registrarAlumnoControlador();
        }
        if ($_POST['modulo_alumno']=="eliminar") {
            echo $insAlumno->eliminarAlumnoControlador();
        }
    } else {
        session_destroy();
        header("Location: ".APP_URL."login/");
    }