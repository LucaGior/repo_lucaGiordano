<?php

    namespace app\controllers;
    use app\models\mainModel;

    class alumnoController extends mainModel{

		/*----------  Controlador registrar alumno  ----------*/
		public function registrarAlumnoControlador() {
			# Almacenando datos
			$nombre = $this->limpiarCadena($_POST['alumno_nombre']);
			$apellido = $this->limpiarCadena($_POST['alumno_apellido']);
			$email = $this->limpiarCadena($_POST['alumno_email']);
			$telefono = $this->limpiarCadena($_POST['alumno_telefono']);
			$fecha_nac = $this->limpiarCadena($_POST['alumno_nac']);
			$dni = $this->limpiarCadena($_POST['alumno_dni']);
			$materia_id = $this->limpiarCadena($_POST['materia_id']);
		
			# Verificando campos obligatorios
			if ($nombre == "" || $apellido == "" || $fecha_nac == "" || $materia_id == "" || $dni == "") {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "No has llenado todos los campos que son obligatorios",
					"icono" => "error"
				];
				return json_encode($alerta);
				exit();
			}
		
			# Verificar si el alumno ya existe por su DNI
			$consulta_dni = "SELECT id FROM alumnos WHERE dni = '$dni'";
			$verificar_alumno = $this->ejecutarConsulta($consulta_dni);
		
			if ($verificar_alumno->rowCount() == 1) {
				$datos = $verificar_alumno->fetch();
				$id_alumno = $datos['id'];
		
				# Verificar si ya está inscrito en la materia
				$consulta_materia = "SELECT * FROM inscripciones WHERE id_alumno = '$id_alumno' AND id_materia = '$materia_id'";
				$verificar_materia = $this->ejecutarConsulta($consulta_materia);
		
				if ($verificar_materia->rowCount() > 0) {
					$alerta = [
						"tipo" => "simple",
						"titulo" => "Ocurrió un error inesperado",
						"texto" => "El alumno ya está inscrito en esa materia",
						"icono" => "error"
					];
					return json_encode($alerta);
					exit();
				}
		
				# Si no está inscrito en la materia, registrar la inscripción
				$inscripcion_datos_reg = [
					["campo_nombre" => "id_materia", "campo_marcador" => ":id_materia", "campo_valor" => $materia_id],
					["campo_nombre" => "id_alumno", "campo_marcador" => ":id_alumno", "campo_valor" => $id_alumno]
				];
		
				$inscribir_alumno = $this->guardarDatos("inscripciones", $inscripcion_datos_reg);
		
				if ($inscribir_alumno->rowCount() == 1) {
					$alerta = [
						"tipo" => "simple",
						"titulo" => "Éxito en registrar",
						"texto" => "El alumno $nombre se inscribió correctamente en la materia.",
						"icono" => "success"
					];
				}
			} else {
				# Registro del nuevo alumno
				$alumno_datos_reg = [
					["campo_nombre" => "nombre", "campo_marcador" => ":nombre", "campo_valor" => $nombre],
					["campo_nombre" => "apellido", "campo_marcador" => ":apellido", "campo_valor" => $apellido],
					["campo_nombre" => "email", "campo_marcador" => ":email", "campo_valor" => $email],
					["campo_nombre" => "telefono", "campo_marcador" => ":telefono", "campo_valor" => $telefono],
					["campo_nombre" => "fecha_nac", "campo_marcador" => ":fecha_nac", "campo_valor" => $fecha_nac],
					["campo_nombre" => "dni", "campo_marcador" => ":dni", "campo_valor" => $dni]
				];
		
				$registrar_alumno = $this->guardarDatos("alumnos", $alumno_datos_reg);
		
				if ($registrar_alumno->rowCount() == 1) {
					# Obtener el id del alumno recién registrado
					$datos = $this->ejecutarConsulta($consulta_dni)->fetch();
					$id_alumno = $datos['id'];
		
					# Insertar la inscripción
					$inscripcion_datos_reg = [
						["campo_nombre" => "id_materia", "campo_marcador" => ":id_materia", "campo_valor" => $materia_id],
						["campo_nombre" => "id_alumno", "campo_marcador" => ":id_alumno", "campo_valor" => $id_alumno]
					];
		
					$inscribir_alumno = $this->guardarDatos("inscripciones", $inscripcion_datos_reg);
		
					if ($inscribir_alumno->rowCount() == 1) {
						$alerta = [
							"tipo" => "simple",
							"titulo" => "Éxito en registrar",
							"texto" => "El alumno $nombre se registró e inscribió correctamente.",
							"icono" => "success"
						];
					}
				}
			}
		
			return json_encode($alerta);
		}
		

		# listar alumno#
		public function listarAlumnoControlador($pagina,$registros,$url,$busqueda){
			$pagina=$this->limpiarCadena($pagina);
			$registros=$this->limpiarCadena($registros);
			$url=$this->limpiarCadena($url);
			$url=APP_URL.$url."/";

			$busqueda=$this->limpiarCadena($busqueda);
			$tabla="";


			$pagina= (isset($pagina) && $pagina>0) ? (int) $pagina: 1;
			$inicio = ($pagina>0) ? (($pagina*$registros)-$registros): 0;

			if (isset($busqueda) && $busqueda!="") {
				$consulta_datos="SELECT * from alumnos 
				WHERE (nombre LIKE '%$busqueda%') OR (apellido LIKE '%$direccion%')
				ORDER BY nombre ASC LIMIT $inicio,$registros";


				$consulta_total="SELECT COUNT(id) from alumnos 
				WHERE (nombre LIKE '%$busqueda%') OR (apellido LIKE '%$direccion%')";

			} else {
				
				$consulta_datos="SELECT * from alumnos ORDER BY nombre ASC LIMIT $inicio,$registros";


				$consulta_total="SELECT COUNT(id) from alumnos ";
			}

			$datos = $this->ejecutarConsulta($consulta_datos);	
			$datos = $datos->fetchAll();
			
			$total = $this->ejecutarConsulta($consulta_total);
			$total = (int) $total->fetchColumn();

			$numeroPaginas=ceil($total/$registros);

		    $tabla.='
				<div class="table-container">
					<table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
						<thead>
							<tr>
								<th class="has-text-centered">#</th>
								<th class="has-text-centered">Alumno</th>
								<th class="has-text-centered">Apellido</th>
								<th class="has-text-centered">DNI</th>
								<th class="has-text-centered">Telefono</th>
								<th class="has-text-centered"></th>
							</tr>
						</thead>
					<tbody>
			';

			if ($total>=1 && $pagina<=$numeroPaginas) {
				$contador=$inicio+1;
				$pag_inicio=$inicio+1;

				foreach ($datos as $rows) {
					$tabla.='
						<tr class="has-text-centered">
							<td>'.$contador.'</td>
							<td>'.$rows['nombre'].'</td>
							<td>'.$rows['apellido'].'</td>
							<td>'.$rows['dni'].'</td>
							<td>'.$rows['telefono'].'</td>
							<td>
								<form class="FormularioAjax" action="'.APP_URL.'app/ajax/alumnoAjax.php" method="POST" autocomplete="off">

									<input type="hidden" name="modulo_alumno" value="eliminar">
									<input type="hidden" name="alumno_id" value="'.$rows['id'].'">

									<button type="submit" class="button is-danger is-rounded is-small">Eliminar</button>
								</form>
							</td>
						</tr>
					';
					$contador++;
				}

				$pag_final=$contador-1;
			} else {
				if ($total>=1) {
					$tabla.='
					<tr class="has-text-centered" >
						<td colspan="7">
							<a href="'.$url.'1/" class="button is-link is-rounded is-small mt-4 mb-4">
								Haga clic acá para recargar el listado
							</a>
						</td>
					</tr>
					';
				} else {
					$tabla.='
					<tr class="has-text-centered" >
						<td colspan="7">
							No hay registros en el sistema
						</td>
					</tr>
					';
				}
				
			}
			


			$tabla.='
						</tbody>
				</table>
			</div>
			';

			if ($total>=1 && $pagina<=$numeroPaginas) {
				$tabla.='
					<p class="has-text-right">Mostrando instituciones 
						<strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un 
						<strong>total de '.$total.'</strong>
					</p>

				';

				$tabla.=$this->paginadorTablas($pagina,$numeroPaginas,$url,10);
			}

			return $tabla;
		}

		#eliminar alumno#
		public function eliminarAlumnoControlador(){
			$id=$this->limpiarCadena($_POST['alumno_id']);

			$datos=$this->ejecutarConsulta("SELECT * FROM alumnos WHERE id ='$id'");

			if ($datos->rowCount()<=0) {
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos encontrado el alumno en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
			} else {
				$datos=$datos->fetch();
			}

			$eliminarAlumno=$this->eliminarRegistro("alumnos","id", $id);

			if ($eliminarAlumno->rowCount()==1) {
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Exito en eliminar",
					"texto"=>"El alumno ".$datos['nombre']." se elimino correctamente",
					"icono"=>"success"
				];
			} else {
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"fallo",
					"texto"=>"No se pudo eliminar el alumno, ".$datos['nombre'],
					"icono"=>"success"
				];
			}
			return json_encode($alerta);
		
		}

		
		public function obtenerAlumnosPorMateria($materia_id) {
			// Consulta para obtener materias
			$consulta = "SELECT a.id, a.nombre, a.apellido, i.id
							FROM alumnos AS a
							JOIN inscripciones AS i ON a.id = i.id_alumno
							WHERE i.id_materia = '$materia_id'";
			
			try {
				$alumnos = $this->ejecutarConsulta($consulta);
				
				
				// Retornar los resultados en formato array
				return $alumnos->fetchAll(\PDO::FETCH_ASSOC);
				
			} catch (\PDOException $e) {
				return ['error' => $e->getMessage()];
			}
		}
		
		
		

		
	}