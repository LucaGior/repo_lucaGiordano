<div class="container is-fluid mb-6">
	<h1 class="title">MATERIAS</h1>
	<h2 class="subtitle">Lista de Materias</h2>
</div>
<div class="container pb-6 pt-6">

	<?php
		use app\controllers\materiaController;

		$insMateria= new materiaController();

		echo $insMateria->listarMateriaControlador($url[1],15,$url[0],"");


	?>	

</div>