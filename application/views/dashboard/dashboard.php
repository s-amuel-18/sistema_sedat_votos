<?php
$this->load->view("template/head.php");
?>

<!-- navbar -->
<?php $this->load->view("components/navbar"); ?>
<!-- endnavbar -->

<div class="container pt-5">

  <div class="">votantes estadisticas</div>

  <h2 class="mb-4">
    <?php echo $tituloPrincipal ?>
  </h2>

  <div class="row mb-5">
    <div class="py-2 col-12 col-md-4">


      <div class="form-group search_methods">
        <form id="form_search" class="" method="GET" action="<?php echo site_url("votacion/searchUbch") ?>">
          <label for="search">Buscar Ubch</label>
          <div class="form-group form-inline">
            <input id="search" class="form-control" type="search" name="search" placeholder="centro de votacion">
            <input type="hidden" name="url" value="dashboard/filtrar_ubch/">
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
                  ? '<?php echo site_url('dashboard/filtrar_ubch/') ?>' + this.value
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
              <a class="dropdown-item" href="<?php echo site_url("dashboard/filterParroquia/") . str_replace(" ", "-", $value) ?>"><?php echo $value ?></a>


            <?php
            endforeach;
            ?>


            <!-- <a class="dropdown-item" href="#">Another action</a>
            <a class="dropdown-item" href="#">Something else here</a> -->
          </div>
        </div>

      </form>

      <div class="py-3">
        <a href="<?php echo site_url("dashboard/reporteParroquias") ?>" class="btn btn-outline-primary">Reporte de parroquias</a>
      </div>

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

      <h5>estadisticas generales</h5>

      <p>Votantes Registrados: <?php echo $countVotantesRegistrados ?></p>
      <div class="progress">
        <div class="progress-bar bg-primary" style="width: 100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">100%</div>
      </div>

      <br>

      <p>Votantes Confirmados: <?php echo $countVotantesConfirmados ?> (<?php echo $porcentajeSumaVotandesConf ?>%)</p>
      <div class="progress">
        <div class="progress-bar bg-danger" style="width:  <?php echo $porcentajeSumaVotandesConf ?>%" role="progressbar" aria-valuenow="<?php echo $porcentajeSumaVotandesConf ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $porcentajeSumaVotandesConf ?>%</div>
      </div>


      <!-- <a href="" class="btn btn-success btn-block"><i class="fas fa-file-excel"></i> descargar estadisticas</a> -->
    </div>

  </div>


  <!-- tabla instituciones de votacion -->
  <?php if( isset($parroquiaFilter) ):?>
    <div class="pb-3">
      <a href="<?php echo site_url("dashboard/reporte_votantes_pr/").url_title($parroquiaFilter, "-", true) ?>" class="float-right btn btn-success">Exportar reporte por parroquia</a>
    </div>
  <?php else: ?>
    <div class="pb-3">
      <a href="<?php echo site_url("dashboard/reporte_votantes") ?>" class="float-right btn btn-success">Exportar reporte</a>
    </div>
  <?php endif?>



  <div class="w-100" style="overflow-x: auto;">
    <table class="table table-light">
      <thead class="thead-light">
        <tr>
          <th style="min-width: 150px;">Cod UBCH</th>
          <th style="min-width: 150px;">Parroquia</th>
          <th style="min-width: 150px;">Nom Inst</th>
          <th style="min-width: 150px;">Votantes <br> Registrados</th>
          <th style="min-width: 150px;">Votantes <br> Confirmados</th>
          <th style="min-width: 150px;">% Votantes <br> Confirmados</th>
          <th style="min-width: 150px;">cuader vs google</th>
        </tr>
      </thead>
      <tbody id="tbody_estadisticas">

        <?php

        foreach ($dataEstadisticas as $value) :
        ?>
          <tr>
            <td style="min-width: 150px;"><?php echo $value->COD_UBCH ?></td>
            <td style="min-width: 150px;"><?php echo $value->PARROQUIA ?></td>
            <td style="max-width: 100px;"><?php echo $value->NOMBRE_INSTITUCIONES_CON_CODIGO ?></td>
            <td style="min-width: 150px;"><?php echo $value->POBLACION_VOTANTE ?></td>
            <td style="min-width: 150px;">
              <a href="<?php echo site_url("votante/filter_ubch/" . $value->id) ?>"><?php echo $value->total_general ?></a>
            </td>
            <td style="min-width: 150px;"><?php echo $value->porcentaje ?>%</td>

            <td style="min-width: 150px;">
              <div class="progress">
                <div class="progress-bar bg-primary" style="width: 100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">100%</div>
              </div>
              <br>
              <div class="progress">
                <div class="progress-bar bg-<?php echo $value->porcentaje < 100 ? "danger" : "success" ?>" style="width: <?php echo $value->porcentaje ?>%" role="progressbar" aria-valuenow="<?php echo $value->porcentaje ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $value->porcentaje ?>%</div>
              </div>
            </td>
          </tr>



        <?php
        endforeach;
        ?>

      </tbody>
      <!-- <tfoot>
        <tr>
          <th>#</th>
        </tr>
      </tfoot> -->
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