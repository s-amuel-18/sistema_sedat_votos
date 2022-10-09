<?php
$this->load->view("template/head.php");
?>
<!-- <meta http-equiv="refresh" content="60" > -->
<!-- navbar -->
<?php $this->load->view("components/navbar"); ?>
<!-- endnavbar -->

<div class="container pt-5">
  <!-- titulo -->
  <div class="">Mesa Estadisticas</div>
  <h2 class="mb-4">
    <?php echo $tituloPrincipal ?>
  </h2>

  <div class="row mb-5">
    <div class="py-2 col-12 col-md-4">
      <div class="form-group search_methods">
        <form id="form_search" class="" method="GET" action="<?php echo site_url("votacion/searchUbch") ?>">
          <label for="search">buscar Ubch </label>
          <div class="form-group form-inline">
            <input id="search" class="form-control" type="search" name="search" placeholder="centro de votacion">
            <input type="hidden" name="url" value="mesa_estadisticas/filtro_cv/">
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
        <label for="my-select">filtrar Ubch </label>

        <select onchange="
                window.location.href = this.value.length != 0 
                  ? '<?php echo site_url('mesa_estadisticas/filtro_cv/') ?>' + this.value
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

    <div class="py-2 col-12 col-md-3">

      <form id="form_filtro_parroquia" action="<?php echo site_url() ?>/dashboard/filterParroquia" method="POST">
        <label for="">filtrar por Parroquias </label>


        <div class="dropdown show">
          <a class="btn btn-light " href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            filtrar por parroquia
          </a>

          <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <?php
              foreach ($this->parroquias as $key => $value) :
            ?>
              <a class="dropdown-item" href="<?php echo site_url("mesa_estadisticas/filtro_pr/") . url_title($value, "-") ?>"><?php echo $value ?></a>
            <?php
              endforeach;
            ?>
          </div>
        </div>
      </form>

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

    <div class="py-2 col-12 col-md-5 ">
      <h5><?php echo $titulo_grafica ?>`</h5>

      <div class="row">
        <?php foreach ($grafica_estadisticas as $value) : ?>

          <div class="col-md-6 mb-3">
            <p class="mb-2"><?php echo $value["titulo"] ?>: <?php echo $value["total"] ?></p>
            <div class="progress">
              <div class="progress-bar bg-<?php echo $value["color"] ?>" style="width: <?php echo $value["porcentaje"] ?>%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"><?php echo number_format($value["porcentaje"], 2) ?>%</div>
            </div>
          </div>
        <?php endforeach ?>
        <div class="col-md-12">
          <p><?php echo $cotillones_entregados ?></p>

        </div>


      </div>


    </div>
  </div>

  <!-- tabla estadistica de mesas -->
  <div class="pb-3">
    <a href="<?php echo site_url("mesa_estadisticas/reporte_mesas_estadisticas_parroquias")?>" class="float-right btn btn-success">Exportar reporte</a>
  </div>
  <br><br>
  <div class="w-100" style="overflow-x: auto;">
    <table class="table table-light">
      <thead class="thead-light">
        <tr>
          <th style="min-width: 150px;">Parroquia</th>
          <th style="min-width: 150px;">ubchs</th>
          <!-- <th style="min-width: 150px;">Materiales electorales <br>entregados</th> -->
          <!-- <th style="min-width: 150px;">Materiales electorales <br>registrados sin entregar</th> -->
          <th style="min-width: 150px;">Mesas <br>Totales</th>
          <!-- <th style="min-width: 150px;">Mesas <br> Instaladas</th> -->
          <!-- <th style="min-width: 150px;">%<br> Instaladas</th> -->
          <th style="min-width: 150px;">Mesas <br>  Constituidas</th>
          <th style="min-width: 150px;">%<br>  Constituidas</th>
          <th style="min-width: 150px;">Mesas Cerradas</th>
          <th style="min-width: 150px;">%<br> Cerradas</th>
          <th style="min-width: 150px;">Mas <br> detalles</th>
        </tr>
      </thead>
      <tbody id="tbody_estadisticas">

        <?php foreach ($table_estadisticas as $i => $value) : ?>
          <tr>
            <td><?php echo $value["PARROQUIA"] ?></td>
            <td><?php echo $value["ubch_por_parroquia"] ?></td>
            <!-- <td><?php echo $value["total_cotillones_entregados"] ?></td>
            <td><?php echo $value["total_cotillones_sin_entregar"] ?></td> -->
            <td><?php echo $value["cant_mesas_total"] ?></td>
            <!-- <td><?php echo $value["mesas_instaladas"] ?></td>
            <td><?php echo media_aritmetica($value["cant_mesas_total"], $value["mesas_instaladas"]) ?></td> -->

            <td><?php echo $value["mesas_constituidas"] ?></td>
            <td><?php echo number_format(media_aritmetica($value["cant_mesas_total"], $value["mesas_constituidas"]), 2) ?></td>

            <td><?php echo $value["mesas_cerradas"] ?></td>
            <td><?php echo number_format(media_aritmetica($value["cant_mesas_total"], $value["mesas_cerradas"]), 2) ?></td>
            <td>
              <a href="<?php echo site_url("mesa_estadisticas/filtro_pr/".url_title($value["PARROQUIA"], "-", true)) ?>">Ver mas</a>
            </td>
          </tr>
        <?php endforeach ?>

      </tbody>
    </table>
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