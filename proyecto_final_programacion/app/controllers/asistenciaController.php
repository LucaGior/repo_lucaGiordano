<?php

namespace app\controllers;
use app\models\mainModel;

class asistenciaController extends mainModel{

    /*----------  Controlador registrar asistencias  ----------*/
    public function registrarAsistenciaControlador() {
        # Almacenando datos #
        $id_materia = $this->limpiarCadena($_POST['materia_id']);
        $id_inscripcion = $_POST['asistencia']; // Asumiendo que esto es un array con los estados de asistencia
        $fecha = date('Y-m-d'); // O puedes obtenerla del formulario si deseas

        # Verificando campos obligatorios #
        if (empty($id_materia) || empty($id_inscripcion)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No has llenado todos los campos que son obligatorios",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }

        # Procesar las asistencias #
        foreach ($id_inscripcion as $id => $estado) {
            $data = [
                [
                    "campo_nombre" => "id_materia",
                    "campo_marcador" => ":id_materia",
                    "campo_valor" => $id_materia
                ],
                [
                    "campo_nombre" => "id_inscripcion",
                    "campo_marcador" => ":id_inscripcion",
                    "campo_valor" => $id
                ],
                [
                    "campo_nombre" => "fecha",
                    "campo_marcador" => ":fecha",
                    "campo_valor" => $fecha
                ],
                [
                    "campo_nombre" => "estado",
                    "campo_marcador" => ":estado",
                    "campo_valor" => $estado
                ]
            ];

            $this->guardarDatos("asistencias", $data);
        }

        $alerta = [
            "tipo" => "simple",
            "titulo" => "Éxito en registrar",
            "texto" => "Las asistencias se registraron correctamente",
            "icono" => "success"
        ];

        return json_encode($alerta);
    }

    
}
