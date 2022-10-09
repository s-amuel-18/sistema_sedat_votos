<?php
$this->load->view("template/head.php");
?>

<!-- navbar -->
<?php $this->load->view("components/navbar"); ?>
<!-- endnavbar -->

<div class="container pt-5">
  <!-- titulo -->
  <div class="">Candidatos estadistica</div>
  <h2 class="mb-4">
    <?php echo $tituloPrincipal ?>
  </h2>

  <div class="row mb-5">
    <div class="py-2 col-12 col-md-4">
      <div class="form-group search_methods">
        <form id="form_search" class="" method="GET" action="<?php echo site_url("votacion/searchUbch") ?>">
          <label for="search">buscar Ubch</label>
          <div class="form-group form-inline">
            <input id="search" class="form-control" type="search" name="search" placeholder="centro de votacion">
            <input type="hidden" name="url" value="candidatos_estadistica/filtro_cv/">
            <button class="btn btn-primary" type="submit">buscar</button>
          </div>
        </form>
      </div>

      <script>
        const form_search = document.getElementById("form_search");
        const search = document.getElementById("search");
        form_search.addEventListener("submit", e => {
          if (search.value.replace(" ", "").length == 0) e.preventDefault()
        });
      </script>

      <div class="form-group search_methods" style="display: none;">
        <label for="my-select">filtrar Ubch</label>

        <select onchange="
                window.location.href = this.value.length != 0 
                  ? '<?php echo site_url('candidatos_estadistica/filtro_cv/') ?>' + this.value
                  : '#'
              " id="my-select" class="form-control">
          <option value="">elegir centro de votacion</option>
          <?php foreach ($this->ubch as $value) : ?>
            <option value="<?php echo $value->id ?>"><?php echo $value->NOMBRE_INSTITUCIONES_CON_CODIGO ?></option>
          <?php endforeach ?>
        </select>
      </div>

      <div class="">
        <button onclick="mostrarMetodoBusqueda(this.dataset.index)" data-index="0" class="btn btn-outline-primary btn-sm" type="button"><i class="fas fa-search"></i></button>

        <button onclick="mostrarMetodoBusqueda(this.dataset.index)" data-index="1" class="btn btn-outline-primary btn-sm " type="button"><i class="fas fa-list-ul"></i></button>
      </div>

    </div>

    <div class="py-2 col-12 col-md-4">

      <form id="form_filtro_parroquia" action="<?php echo site_url() ?>/dashboard/filterParroquia" method="POST" class="mb-3">
        <label for="">filtrar por Parroquias </label>


        <div class="dropdown show">
          <a class="btn btn-light " href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            filtrar por parroquia
          </a>

          <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <?php
            foreach ($this->parroquias as $key => $value) :
            ?>
              <a class="dropdown-item" href="<?php echo site_url("candidatos_estadistica/filtro_pr/") . url_title($value, "-") ?>"><?php echo $value ?></a>
            <?php
            endforeach;
            ?>
          </div>
        </div>
      </form>

      <div class="">
        <a class="btn btn-outline-primary" href="<?php echo site_url("candidatos_estadistica/por_parroquia") ?>">Reporte por parroquia</a>
      </div>
      <!-- <div class="py-3">
        <a href="<?php echo site_url("mesa_estadisticas/reporte_parroquia") ?>" class="btn btn-outline-primary">Reporte de parroquias</a>
      </div> -->

      <script>
        function enviarForm(target, formId) {
          const form = document.querySelector(formId);

          if (target.value.length > 0) {

            document.getElementById('form_filtro_parroquia').submit()

          }
        }
        // const selectFilter = document.getElementById("filtro_parroquia");
      </script>


    </div>

    <div class="py-2 col-12 col-md-4 ">
      <!-- <h5><?php echo $titulo_grafica ?>`</h5> -->

      <?php if (empty($votos_por_cargo)) : ?>
        <div class="alert alert-secondary" role="alert">
          No Hay Votos Registrados en <?php echo $filtro ?>
        </div>
      <?php else : ?>
        <?php foreach ($votos_por_cargo as $value) : ?>

          <p><?php echo $value->cargo ?>: <?php echo number_format($value->total_general, 0) ?></p>

        <?php endforeach ?>
      <?php endif ?>

    </div>
  </div>

  <?php if (isset($_SESSION["success_message"])) : ?>
    <div class="alert alert-success" role="alert">
      <?php echo $_SESSION["success_message"] ?>
    </div>
  <?php
    unset($_SESSION["success_message"]);
  endif
  ?>

  <!-- tabla estadistica de mesas -->
  <div class="w-100" style="overflow-x: auto;">

    <?php if (empty($votos_por_cargo)) : ?>
      <tr>
        <td>
          <div class="alert alert-secondary" role="alert">
            No Hay Votos Registrados en <?php echo $filtro ?>
          </div>
        </td>
      </tr>
    <?php else : ?>

      <div class="float-left">
        <a href="<?php echo site_url("candidatos_estadistica/candidatos_all")?>" class="btn btn-primary">Candidatos detalle</a>
      </div>
      
      <?php if (isset($parroquia)) : ?>
        
        <div class="pb-3 float-right">
          <a href="<?php echo site_url("candidatos_estadistica/reporte_candidatos_parroquia/") . url_title($parroquia, "-", true) ?>" class="btn btn-success">Exportar reporte <?php echo $parroquia ?></a>
          <a href="<?php echo site_url("candidatos_estadistica/reporte_candidatos_por_ubch?parroquia=") . url_title($parroquia, "-", true) ?>" class=" btn btn-info">Exportar reporte 2 <?php echo $parroquia ?></a>
        </div>
      <?php elseif( isset($id_ubch) ): ?>
        <div class="pb-3 float-right">
          <a href="<?php echo site_url("candidatos_estadistica/reporte_candidatos_ubch/").$id_ubch ?>" class=" btn btn-success">Exportar reporte ubch</a>
          <a href="<?php echo site_url("candidatos_estadistica/reporte_candidatos_por_ubch?id_ubch=").$id_ubch ?>" class=" btn btn-info">Exportar reporte 2 ubch</a>
        </div>

      <?php else: ?>
        <div class="pb-3 float-right">
          <a href="<?php echo site_url("candidatos_estadistica/reporte_candidatos") ?>" class=" btn btn-success">Exportar reporte</a>
          <a href="<?php echo site_url("candidatos_estadistica/reporte_candidatos_por_ubch") ?>" class=" btn btn-info">Exportar reporte 2 </a>
        </div>
      <?php endif ?>
      <br><br>
      <table class="table table-light">
        <thead class="thead-light">
          <tr>
            <th style="min-width: 150px;">Nombre</th>
            <th style="min-width: 150px;">Cargo</th>
            <th style="min-width: 150px;">Partido</th>
            <th style="min-width: 150px;">Votos</th>
            <th style="min-width: 150px;">Votos <br> Totales</th>
            <th style="min-width: 150px;">%</th>
            <th style="min-width: 150px;">Gráfico</th>
          </tr>
        </thead>
        <tbody id="tbody_estadisticas">


          <?php foreach ($candidatos_estadistica as $value) : ?>
            <tr>
              <td><?php echo $value->nombre_y_apellido ?></td>
              <td><?php echo $value->cargo ?></td>
              <td><?php echo $value->partido ?></td>
              <td><?php echo number_format($value->cant_votos, 0) ?></td>
              <td><?php echo number_format($value->total_general, 0) ?></td>
              <td><?php echo number_format($value->porcentaje, 2) ?>%</td>

              <td style="min-width: 150px;">
                <div class="progress">
                  <div class="progress-bar bg-primary" style="width: 100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">100%</div>
                </div>
                <br>
                <div class="progress">
                  <div class="progress-bar bg-<?php echo $value->porcentaje < 100 ? "danger" : "success" ?>" style="width: <?php echo number_format($value->porcentaje, 2) ?>%" role="progressbar" aria-valuenow="<?php echo number_format($value->porcentaje, 2) ?>" aria-valuemin="0" aria-valuemax="100"><?php echo number_format($value->porcentaje, 2) ?>%</div>
                </div>
              </td>
            </tr>
          <?php endforeach ?>

        </tbody>
      </table>
    <?php endif ?>
  </div>

  <div class="py-3">
    <?php echo $this->pagination->create_links() ?>
  </div>

</div>


<script>
  function mostrarMetodoBusqueda(index) {
    const methods = document.querySelectorAll(".search_methods");

    for (let i = 0; i < methods.length; i++) {
      methods[i].style.display = "none";
    }

    methods[index].style.display = "block";
    console.log(methods[index])
  }
</script>

<?php
$this->load->view("template/footer.php");
?>