<div class="container is-fluid mb-6">
    <h1 class="title">Asistencias</h1>
    <h2 class="subtitle">Toma de asistencias</h2>
    <h3 class="subtitle">Seleccionar institucion y materia</h3>
</div>

<div class="container pb-6 pt-6">
    <form class="FormularioAjax" action="<?php echo APP_URL;?>app/ajax/asistenciaAjax.php" method="POST" autocomplete="off">

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
                            <select name="materia_id" id="materia_id" >
                                <option value="">--seleccione una opcion--</option>
                            </select>
                        </div>
                        <span class="icon is-medium is-left">
                            <i class="fas fa-globe"></i>
                        </span>
                    </div>
                </div>
            </div>

            <div class="column">
                <p class="has-text-centered">
                    <button type="reset" class="button is-link is-light is-rounded">Limpiar</button>
                    <button type="button" class="button is-info is-rounded" onclick="cargarAlumnos()">Listar alumnos</button>
                </p>
            </div>
        </div>
    <!-- Aquí comienza la tabla de alumnos -->
        <input type="hidden" name="modulo_asistencia" value="registrar">
        <h3 class="subtitle">Lista de Alumnos</h3>
        <div class="table-container">
            <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                <thead>
                    <tr>
                        <th class="has-text-centered">#</th>
                        <th class="has-text-centered">Alumno</th>
                        <th class="has-text-centered">Apellido</th>
                        <th class="has-text-centered">Presente/Ausente</th>
                    </tr>
                </thead>
                <tbody id="alumnosMateria">
                    
        
            

        
                </tbody>
            </table>
        </div>
            <!-- Botón para guardar las asistencias -->
        <div class="has-text-centered">
            <button type="submit" class="button is-info is-rounded">Guardar Asistencias</button>
        </div>
    </form>
</div>

<script>
function cargarMaterias() {
    const institucionId = document.getElementById('institucion_id').value;
    const alumnosTabla = document.getElementById('materia_id');

    if (!institucionId) {
        alumnosTabla.innerHTML = '<option value="">Seleccione una materia</option>';
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
            alumnosTabla.innerHTML = '<option value="">Seleccione una materia</option>';

            if (data.length > 0) {
                data.forEach(materia => {
                    alumnosTabla.innerHTML += `<option value="${materia.id}">${materia.nombre}</option>`;
                });
            } else {
                alumnosTabla.innerHTML = '<option value="">No hay materias disponibles</option>';
            }
        })
        .catch(error => console.error('Error al cargar materias', error));
}
function cargarAlumnos(event) {
    
    const materia_id = document.getElementById('materia_id').value;
    const alumnosTabla = document.getElementById('alumnosMateria');

    if (!materia_id) {
        alumnosTabla.innerHTML = "<tr><td colspan='4' class='has-text-centered'>Seleccione una materia</td></tr>";
        return;
    }

    // La URL completa con el parámetro en la query string
    const url = '<?php echo APP_URL;?>app/ajax/alumnoAjax.php?materia_id=' + materia_id;

    fetch(url) // Sin ningún cuerpo en la solicitud
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener alumnos');
            }
            return response.json();
        })
        .then(data => {
            alumnosTabla.innerHTML = ""; // Limpiar contenido previo

            if (data.length > 0) {
                data.forEach((alumno, index) => {
                    alumnosTabla.innerHTML += `
                        <tr class="has-text-centered">
                            <td>${index + 1}</td>
                            <td>${alumno.nombre}</td>
                            <td>${alumno.apellido}</td>
                            <td>
                                <select name="asistencia[${alumno.id_inscripcion}]" class="select is-small">
                                    <option value="presente">Presente</option>
                                    <option value="ausente">Ausente</option>
                                </select>
                            </td>
                        </tr>`;
                });
            } else {
                alumnosTabla.innerHTML = '<tr><td colspan="4" class="has-text-centered">No hay alumnos disponibles</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error al cargar alumnos:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error al cargar alumnos',
                text: error.message,
            });
        });
}
</script>
