<?php

    namespace app\controllers;
    use app\models\mainModel;

    class materiaController extends mainModel{

		/*----------  Controlador registrar usuario  ----------*/
		public function registrarMateriaControlador(){

			# Almacenando datos#
		    $nombre=$this->limpiarCadena($_POST['materia_nombre']);
		    $id_institucion=$this->limpiarCadena($_POST['instituto_id']);
			


		    # Verificando campos obligatorios #
		    if($nombre=="" || $id_institucion==""){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No has llenado todos los campos que son obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }
			#verificando integridad de los datos#

			if ($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ 0-9]{3,40}",$nombre)) {
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El nombre no coincide con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
			}
			if ($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ 0-9]{0,40}",$id_institucion)) {
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"".$id_institucion." no coincide con el formato solicitado",
					"icono"=>"error"
					
				];
				return json_encode($alerta);
		        exit();
				}
			# Verificar si la materia ya existe en la institución #
		
			# Verificar si la materia ya existe en la institución #
			$consulta_existencia = $this->ejecutarConsulta("SELECT nombre FROM materias 
														WHERE nombre = '$nombre' AND id_institucion = '$id_institucion'");
		
			if ($consulta_existencia->rowCount() > 0) {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "La materia ya existe en esta institución",
					"icono" => "error"
				];
				return json_encode($alerta);
				exit();
			}

			$materias_datos_reg=[
				[
					"campo_nombre"=>"nombre",
					"campo_marcador"=>":nombre",
					"campo_valor"=>$nombre
				],
				[
					"campo_nombre"=>"id_institucion",
					"campo_marcador"=>":id_institucion",
					"campo_valor"=>$id_institucion
				]
				

			];

			$registrar_materias=$this->guardarDatos("materias",$materias_datos_reg);

			if ($registrar_materias->rowCount()==1) {
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Exito en registrar",
					"texto"=>"La materia ".$nombre." se registro correctamente",
					"icono"=>"success"
				];
				
			} else {
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No se pudo registrar el instituto",
					"icono"=>"error"
				];
			}
			
        	return json_encode($alerta);
		}
		# listar usuarios#
		public function listarMateriaControlador($pagina,$registros,$url,$busqueda){
			$pagina=$this->limpiarCadena($pagina);
			$registros=$this->limpiarCadena($registros);
			$url=$this->limpiarCadena($url);
			$url=APP_URL.$url."/";

			$busqueda=$this->limpiarCadena($busqueda);
			$tabla="";


			$pagina= (isset($pagina) && $pagina>0) ? (int) $pagina: 1;
			$inicio = ($pagina>0) ? (($pagina*$registros)-$registros): 0;

			if (isset($busqueda) && $busqueda!="") {
				$consulta_datos="SELECT * from materias 
				WHERE (nombre LIKE '%$busqueda%')
				ORDER BY nombre ASC LIMIT $inicio,$registros";


				$consulta_total="SELECT COUNT(id) from materias 
				WHERE (nombre LIKE '%$busqueda%')";

			} else {
				
				$consulta_datos="SELECT Materias.id, Materias.nombre AS nombre_materia,
                                Instituciones.nombre AS nombre_institucion
                                FROM Materias
                                JOIN Instituciones ON Materias.id_institucion = Instituciones.id";


				$consulta_total="SELECT COUNT(Materias.id)
                                FROM Materias
                                JOIN Instituciones ON Materias.id_institucion = Instituciones.id";
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
								<th class="has-text-centered">materia</th>
								<th class="has-text-centered">institucion</th>
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
							<td>'.$rows['nombre_materia'].'</td>
							<td>'.$rows['nombre_institucion'].'</td>
							<td>
								<form class="FormularioAjax" action="'.APP_URL.'app/ajax/materiaAjax.php" method="POST" autocomplete="off">

									<input type="hidden" name="modulo_materia" value="eliminar">
									<input type="hidden" name="materia_id" value="'.$rows['id'].'">

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
					<p class="has-text-right">Mostrando institutos 
						<strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un 
						<strong>total de '.$total.'</strong>
					</p>

				';

				$tabla.=$this->paginadorTablas($pagina,$numeroPaginas,$url,10);
			}

			return $tabla;
		}

		#eliminar instituto#
		public function eliminarMateriaControlador(){
			$id=$this->limpiarCadena($_POST['materia_id']);

			$datos=$this->ejecutarConsulta("SELECT * FROM materias WHERE id='$id'");

			if ($datos->rowCount()<=0) {
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos encontrado el usuario en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
			} else {
				$datos=$datos->fetch();
			}

			$eliminarInstituto=$this->eliminarRegistro("materias","id", $id);

			if ($eliminarInstituto->rowCount()==1) {
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Exito en eliminar",
					"texto"=>"El instituto ".$datos['nombre']." se registro elimino correctamente",
					"icono"=>"success"
				];
			} else {
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"fallo",
					"texto"=>"No se pudo eliminar el instituto, ".$datos['nombre'],
					"icono"=>"success"
				];
			}
			return json_encode($alerta);
		
		}
		
		// Función que obtendrá las materias de una institución
		public function obtenerMateriasPorInstitucion($institucionId) {
			// Consulta para obtener materias
			$consulta = "SELECT id, nombre FROM materias WHERE id_institucion = '$institucionId'";
			
			try {
				$materias = $this->ejecutarConsulta($consulta);
				
				
				// Retornar los resultados en formato array
				return $materias->fetchAll(\PDO::FETCH_ASSOC);
				
			} catch (\PDOException $e) {
				return ['error' => $e->getMessage()];
			}
		}
		
			
	}
	