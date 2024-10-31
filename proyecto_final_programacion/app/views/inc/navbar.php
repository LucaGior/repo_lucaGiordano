<nav class="navbar" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a class="navbar-item" href="<?php echo APP_URL; ?>dashboard/">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-list-details" width="36" height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#00b341" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M13 5h8" />
            <path d="M13 9h5" />
            <path d="M13 15h8" />
            <path d="M13 19h5" />
            <path d="M3 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
            <path d="M3 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
        </svg>

        </a>

        <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>

    <div id="navbarBasicExample" class="navbar-menu">
        <div class="navbar-start">
            
            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">Instituto</a>

                <div class="navbar-dropdown">
                    <a class="navbar-item" href="<?php echo APP_URL; ?>institutoNew/">Nuevo</a>
                    <a class="navbar-item" href="<?php echo APP_URL; ?>institutoList/">Lista</a>
                </div>
            </div>

            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">Materia</a>

                <div class="navbar-dropdown">
                    <a class="navbar-item" href="<?php echo APP_URL; ?>materiaNew/">Nueva</a>
                    <a class="navbar-item" href="<?php echo APP_URL; ?>materiaList/">Lista</a>
                </div>
            </div>

            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">Alumnos</a>

                <div class="navbar-dropdown">
                    <a class="navbar-item" href="<?php echo APP_URL; ?>alumnoNew/">Nuevo</a>
                    <a class="navbar-item" href="<?php echo APP_URL; ?>alumnoList/">Lista</a>
                    <a class="navbar-item" href="<?php echo APP_URL; ?>alumnoAsistencia/">Asistencia</a>
                </div>
            </div>
            
            
        </div>

        <div class="navbar-end">
            <div class="navbar-item">
                <div class="buttons">
                    <h3>Profesor</h3>
                    <a class="button is-primary is-rounded" href="<?php echo APP_URL; ?>userUpdate/">Mi Cuenta</a>
                    <a class="button is-link is-rounded" href="<?php echo APP_URL; ?>logOut/">Salir</a>
                </div>
            </div>
        </div>
    </div>
</nav>