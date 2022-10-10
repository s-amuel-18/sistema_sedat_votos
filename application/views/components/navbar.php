<nav class="navbar navbar-expand-lg navbar-light bg-light">

  <div class="container">

    <?php
    if ($this->session->userdata("rol") === "administrador") echo '<a class="navbar-brand" href="' . site_url("dashboard/index") . '">Dashboard</a>';
    ?>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ml-auto">
        <?php if( $this->session->userdata("rol") === "administrador" ):?>
          <!-- datos de votantes -->
        <li class="nav-item">
          <a class="nav-link" href="<?php echo site_url("votante") ?>">Votantes</a>
        </li>
          
        <!-- estadisticas de los candidatos -->
        <li class="nav-item">
          <a class="nav-link" href="<?php echo site_url("candidatos_estadistica") ?>">Candidatos</a>
        </li>

        <!-- estatus mesa -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="# id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            mesa
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="<?php echo site_url("/mesa_status") ?>">Estatus mesa</a>
            <a class="dropdown-item" href="<?php echo site_url("/mesa_estadisticas") ?>">Estadisticas</a>
          </div>
        </li>

        <?php endif?>
        

        <!-- generacion de votos -->
        <li class="nav-item">
          <a class="nav-link" href="<?php echo site_url("/") ?>">Generar voto </a>
        </li>

        <!-- cerrar sesion -->
        <li class="nav-item">
          <a class="nav-link text-danger" href="<?php echo site_url("/auth/logout") ?>">Cerrar sesion</a>
        </li>


      </ul>
    </div>



  </div>

</nav>