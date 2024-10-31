<div class="container is-fluid mb-6">
	<h1 class="title">Instituto</h1>
	<h2 class="subtitle">Nuevo Instituto</h2>
</div>

<div class="container pb-6 pt-6">

	<form class="FormularioAjax" action="<?php echo APP_URL;?>app/ajax/institutoAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data" >

		<input type="hidden" name="modulo_instituto" value="registrar">

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Nombre</label>
				  	<input class="input" type="text" name="instituto_nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ 0-9]{3,40}" maxlength="40" required >
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Direccion</label>
				  	<input class="input" type="text" name="instituto_direccion" maxlength="70" >
				</div>
		  	</div>
		</div>
	
		<p class="has-text-centered">
			<button type="reset" class="button is-link is-light is-rounded">Limpiar</button>
			<button type="submit" class="button is-info is-rounded">Guardar</button>
		</p>
	</form>
</div>