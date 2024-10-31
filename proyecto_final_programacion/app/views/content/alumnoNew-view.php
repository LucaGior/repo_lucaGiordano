
<div class="container is-fluid mb-6">
	<h1 class="title">Alumnos</h1>
	<h2 class="subtitle">Nuevo Alumnos</h2>
</div>

<div class="container pb-6 pt-6">

	<form class="FormularioAjax" action="<?php echo APP_URL;?>app/ajax/alumnoAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data" >

		<input type="hidden" name="modulo_alumno" value="registrar">

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Instituto</label>
					<div class="control has-icons-left">
						<div class="select is-medium">
							<div class="table-container">
								<select name="institucion_id" id="institucion_id" onchange="cargarMaterias()" required>
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
		  	<div class="column">
		    	<div class="control">
					<label>Materia</label>
					<div class="control has-icons-left">
						<div class="select is-medium">
							<select name="materia_id" id="materia_id">
								<option value="">--seleccione una opcion--</option>
								
							</select>
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
				  	<input class="input" type="text" name="alumno_nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Apellidos</label>
				  	<input class="input" type="text" name="alumno_apellido" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required >
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Email</label>
				  	<input class="input" type="email" name="alumno_email" maxlength="70" >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>DNI</label>
				  	<input class="input" type="text" name="alumno_dni" maxlength="70" >
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Telefono</label>
				  	<input class="input" type="tel" name="alumno_telefono" maxlength="70" >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Fecha de Nacimiento</label>
				  	<input class="input" type="date" name="alumno_nac" maxlength="70" >
				</div>
		  	</div>
		</div>
		
		<p class="has-text-centered">
			<button type="reset" class="button is-link is-light is-rounded">Limpiar</button>
			<button type="submit" class="button is-info is-rounded">Guardar</button>
		</p>
	</form>
</div>
<script>
	function cargarMaterias() {
    const institucionId = document.getElementById('institucion_id').value;
    const materiaSelect = document.getElementById('materia_id');

    if (!institucionId) {
        materiaSelect.innerHTML = '<option value="">Seleccione una materia</option>';
        return;
    }

    fetch('<?php echo APP_URL;?>app/ajax/materiaAjax.php?institucion_id=' + institucionId)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener materias');
            }
            return response.json();
        })
        .then(data => {
            materiaSelect.innerHTML = '<option value="">Seleccione una materia</option>';
            
            if (data.length > 0) {
                data.forEach(materia => {
                    materiaSelect.innerHTML += `<option value="${materia.id}">${materia.nombre}</option>`;
                });
            } else {
                materiaSelect.innerHTML = '<option value="">No hay materias disponibles</option>';
            }
        })
        .catch(error => console.error('Error al cargar materias', error));
}
</script>
