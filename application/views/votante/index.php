<?php
$this->load->view("template/head.php");
?>

<!-- navbar -->
<?php $this->load->view("components/navbar"); ?>
<!-- endnavbar -->

<div class="container pt-5">

  <div class="">Votantes</div>

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
            <input type="hidden" name="url" value="votante/filter_ubch/">
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
                  ? '<?php echo site_url('votante/filter_ubch/') ?>' + this.value
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
              <a class="dropdown-item" href="<?php echo site_url("votante/filter_parroquia/") . str_replace(" ", "-", $value) ?>"><?php echo $value ?></a>


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




  </div>


  <!-- tabla instituciones de votacion -->
  <div class="w-100" style="overflow-x: auto;">

    <?php if (count($votantes) > 0) : ?>

      <table class="table table-light">
        <thead class="thead-light">
          <tr>
            <!-- <th>#</th> -->
            <th style="min-width: 150px;">#</th>
            <th style="min-width: 150px;">Parroquia</th>
            <!-- <th>Parroquia</th> -->
            <th style="min-width: 150px;">Centro <br>votacion</th>
            <th style="min-width: 150px;">Cedula</th>
            <th style="min-width: 150px;">accion</th>
          </tr>
        </thead>
        <tbody id="tbody_estadisticas">

          <?php

          foreach ($votantes as $i => $votantes) :
          ?>
            <tr>
              <td style="min-width: 150px;"><?php echo $i + 1 ?></td>
              <td style="min-width: 150px;"><?php echo $votantes->PARROQUIA ?></td>
              <td style="max-width: 100px;"><?php echo $votantes->NOMBRE_INSTITUCIONES_CON_CODIGO ?></td>
              <td style="min-width: 150px;"><?php echo $votantes->cedula ?></td>
              <td style="min-width: 150px;">
                <form onsubmit="return confirm('estas seguro de querer eliminar este elemento')" action="<?php echo site_url("votante/delete") ?>" method="POST">
                  <input type="hidden" name="id" value="<?php echo $votantes->id ?>">
                  <input type="submit" value="Eliminar" class="btn btn-danger">
                </form>
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
    <?php else : ?>
      <div class="alert alert-secondary" role="alert">
        No hay votantes registrados
      </div>
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

<?php if (isset($_SESSION["alert"])) : ?>
  <script>
    alert("<?php echo $_SESSION["alert"] ?>")
  </script>
<?php
  unset($_SESSION["alert"]);
endif;
?>

<?php
$this->load->view("template/footer.php");
?>





