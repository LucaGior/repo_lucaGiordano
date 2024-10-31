<?php

    namespace app\controllers;
    use app\models\mainModel;

    class institutoController extends mainModel{

		/*----------  Controlador registrar usuario  ----------*/
		public function registrarInstitutoControlador() {
			# Almacenando datos
			$nombre = $this->limpiarCadena($_POST['instituto_nombre']);
			$direccion = $this->limpiarCadena($_POST['instituto_direccion']);
		
			# Verificando campos obligatorios
			if ($nombre == "" || $direccion == "") {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "No has llenado todos los campos que son obligatorios",
					"icono" => "error"
				];
				return json_encode($alerta);
			}
		
			# Verificando integridad de los datos
			if ($this->verificarDatos("[0-9 a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $nombre)) {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "El nombre no coincide con el formato solicitado",
					"icono" => "error"
				];
				return json_encode($alerta);
			}
			if ($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ 0-9]{3,40}", $direccion)) {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "La dirección no coincide con el formato solicitado",
					"icono" => "error"
				];
				return json_encode($alerta);
			}
		
			# Verificando si el instituto ya existe
			$consulta = "SELECT id FROM instituciones WHERE nombre = '$nombre'"; 
			$consulta_instituto = $this->ejecutarConsulta($consulta);
		
			if ($consulta_instituto->rowCount() > 0) {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Instituto existente",
					"texto" => "El instituto ya está registrado en el sistema.",
					"icono" => "warning"
				];
				return json_encode($alerta);
			}
		
			# Preparando datos para el registro
			$instituto_datos_reg = [
				[
					"campo_nombre" => "nombre",
					"campo_marcador" => ":nombre",
					"campo_valor" => $nombre
				],
				[
					"campo_nombre" => "direccion",
					"campo_marcador" => ":direccion",
					"campo_valor" => $direccion
				]
			];
		
			$registrar_instituto = $this->guardarDatos("instituciones", $instituto_datos_reg);
		
			if ($registrar_instituto->rowCount() == 1) {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Éxito en registrar",
					"texto" => "El instituto " . $nombre . " se registró correctamente",
					"icono" => "success"
				];
			} else {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "No se pudo registrar el instituto",
					"icono" => "error"
				];
			}
		
			return json_encode($alerta);
		}

		# listar usuarios#
		public function listarInstitutoControlador($pagina,$registros,$url,$busqueda){
			$pagina=$this->limpiarCadena($pagina);
			$registros=$this->limpiarCadena($registros);
			$url=$this->limpiarCadena($url);
			$url=APP_URL.$url."/";

			$busqueda=$this->limpiarCadena($busqueda);
			$tabla="";


			$pagina= (isset($pagina) && $pagina>0) ? (int) $pagina: 1;
			$inicio = ($pagina>0) ? (($pagina*$registros)-$registros): 0;

			if (isset($busqueda) && $busqueda!="") {
				$consulta_datos="SELECT * from instituciones 
				WHERE (nombre LIKE '%$busqueda%') OR (direccion LIKE '%$direccion%')
				ORDER BY nombre ASC LIMIT $inicio,$registros";


				$consulta_total="SELECT COUNT(id) from instituciones 
				WHERE (nombre LIKE '%$busqueda%') OR (direccion LIKE '%$direccion%')";

			} else {
				
				$consulta_datos="SELECT * from instituciones ORDER BY nombre ASC LIMIT $inicio,$registros";


				$consulta_total="SELECT COUNT(id) from instituciones ";
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
								<th class="has-text-centered">Instituto</th>
								<th class="has-text-centered">Direccion</th>
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
							<td>'.$rows['direccion'].'</td>
							<td>
								<form class="FormularioAjax" action="'.APP_URL.'app/ajax/institutoAjax.php" method="POST" autocomplete="off">

									<input type="hidden" name="modulo_instituto" value="eliminar">
									<input type="hidden" name="instituto_id" value="'.$rows['id'].'">

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
		public function eliminarInstitutoControlador(){
			$id=$this->limpiarCadena($_POST['instituto_id']);

			$datos=$this->ejecutarConsulta("SELECT * FROM instituciones WHERE id='$id'");

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

			$eliminarInstituto=$this->eliminarRegistro("instituciones","id", $id);

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

		public function selectInstitutoControlador() {
			$consulta_datos="SELECT * from instituciones";
			$datos = $this->ejecutarConsulta($consulta_datos);	
			$datos = $datos->fetchAll();


		    $tabla='';
				foreach ($datos as $rows) {
					$tabla.='
						<option value="'.$rows['id'].'">'.$rows['nombre'].'</option>
					';
				}
			return $tabla;
		}
    }