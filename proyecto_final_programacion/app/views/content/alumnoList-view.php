<div class="container is-fluid mb-6">
	<h1 class="title">Alumnos</h1>
	<h2 class="subtitle">Lista de Alumnos</h2>
</div>
<div class="container pb-6 pt-6">

	<?php
		use app\controllers\alumnoController;

		$insAlumno= new alumnoController();

		echo $insAlumno->listarAlumnoControlador($url[1],15,$url[0],"");



	?>	

</div>