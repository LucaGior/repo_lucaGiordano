<div class="container is-fluid mb-6">
	<h1 class="title">Materia</h1>
	<h2 class="subtitle">Nueva Materia</h2>
</div>

<div class="container pb-6 pt-6">

	<form class="FormularioAjax" action="<?php echo APP_URL;?>app/ajax/materiaAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data" >

		<input type="hidden" name="modulo_materia" value="registrar">

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Instituto</label>
                    <div class="control has-icons-left">
						<div class="select is-medium">
							<div class="table-container">
								<select name="instituto_id" id="instituciones">
									<option value="0">--seleccione una opcion--</option>
									<?php
										use app\controllers\institutoController;

										$selectInstituto= new institutoController();

										echo $selectInstituto->selectInstitutoControlador();
									?>
								</select>
							</div>
						</div>
						<span class="icon is-medium is-left">
							<i class="fas fa-globe"></i>
						</span>
					</div>	
				</div>
		  	</div>
		  	
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Nombre</label>
				  	<input class="input" type="text" name="materia_nombre" maxlength="70" require>
				</div>
		  	</div>
		</div>
		
		
		<p class="has-text-centered">
			<button type="reset" class="button is-link is-light is-rounded">Limpiar</button>
			<button type="submit" class="button is-info is-rounded">Guardar</button>
		</p>
	</form>
</div>