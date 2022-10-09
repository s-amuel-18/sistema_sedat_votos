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

      <div class="py-3">
        <a href="<?php echo site_url("mesa_estadisticas/reporte_parroquia") ?>" class="btn btn-outline-primary">Reporte de parroquias</a>
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

  <div class="">
    <?php if (isset($mesas_cerradas) and count($mesas_cerradas) > 0) : ?>
      <div class="dropdown">
        <button id="my-dropdown" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Mesas Cerradas</button>
        <div class="dropdown-menu" aria-labelledby="my-dropdown">

          <?php
          foreach ($mesas_cerradas as $me) {
            $url = site_url("mesa_status/actualizar_mesa_cerrada?numero_mesa=$me->numero_mesa&cv_id=$me->centro_votacion_id");
            echo "<a class='dropdown-item' href='{$url}'>Mesa Numero {$me->numero_mesa}</a>";
          }
          ?>
        </div>
      </div>
    <?php endif ?>
  </div>

  <!-- tabla estadistica de mesas -->

  <!-- 
  <?php if (isset($parroquia)) : ?>
    <div class="pb-3">
      <a href="<?php echo site_url("mesa_estadisticas/reporte_mesas_estadisticas_filtro_pr/") . url_title($parroquia, "-", true) ?>" class="float-right btn btn-success">Exportar reporte <?php echo $parroquia ?></a>
    </div>
  <?php else : ?>
    <div class="pb-3">
      <a href="<?php echo site_url("mesa_estadisticas/reporte_mesas_estadisticas") ?>" class="float-right btn btn-success">Exportar reporte</a>
    </div>
  <?php endif ?> -->




  <?php if (isset($parroquia)) : ?>

    <div class="pb-3 float-right">
      <a href="<?php echo site_url("mesa_estadisticas/reporte_mesas_estadisticas_filtro_pr/") . url_title($parroquia, "-", true) ?>" class="btn btn-success">Exportar reporte <?php echo $parroquia ?></a>

      <a href="<?php echo site_url("mesa_estadisticas/reporte_votos_candidatos_por_mesa?parroquia=") . url_title($parroquia, "-", true) ?>" class=" btn btn-info">Exportar reporte por mesa <?php echo $parroquia ?></a>
    </div>
  <?php elseif (isset($id_ubch)) : ?>
    <div class="pb-3 float-right">
      <a href="<?php echo site_url("mesa_estadisticas/reporte_mesas_estadisticas") ?>" class=" btn btn-success">Exportar reporte</a>
      <a href="<?php echo site_url("mesa_estadisticas/reporte_votos_candidatos_por_mesa?id_ubch=") . $id_ubch ?>" class=" btn btn-info">Exportar reporte por mesa ubch</a>
    </div>

  <?php else : ?>
    <div class="pb-3 float-right">
      <a href="<?php echo site_url("mesa_estadisticas/reporte_mesas_estadisticas") ?>" class=" btn btn-success">Exportar reporte</a>
      <a href="<?php echo site_url("mesa_estadisticas/reporte_votos_candidatos_por_mesa") ?>" class=" btn btn-info">Exportar reporte por mesa </a>
    </div>
  <?php endif ?>



  <br><br>
  <div class="w-100 wrapper" style="overflow-x: scroll; overflow-y: hidden;">
    <table id="estadisticas_mesa" class="table display table-light">
      <thead class="thead-light">
        <tr>
          <!-- <th style="min-width: 150px;">Cod UBCH</th> -->
          <th style="min-width: 150px;">Nom Inst</th>
          <th style="min-width: 150px;">Parroquia</th>
          <!-- <th style="min-width: 150px;">Entrega <br> materiales electorales</th> -->
          <th style="min-width: 150px;">Mesas</th>
          <!-- <th style="min-width: 150px;">Mesas <br> Averiadas</th> -->
          <!-- <th style="min-width: 150px;">Mesas <br> Instaladas</th> -->
          <!-- <th style="min-width: 150px;">%<br> Instaladas</th> -->
          <th style="min-width: 150px;">Mesas <br> Constituidas</th>
          <th style="min-width: 150px;">%<br> Constituidas</th>
          <th style="min-width: 150px;">Mesas Cerradas</th>
          <th style="min-width: 150px;">%<br> Cerradas</th>
          <th style="min-width: 150px;">Detalles</th>
        </tr>
      </thead>
      <tbody id="tbody_estadisticas">

        <?php foreach ($table_estadisticas as $value) : ?>
          <tr>
            <!-- <td><?php echo $value->cod_ubch ?></td> -->
            <td><?php echo $value->nombre_instituciones_con_codigo ?></td>
            <td><?php echo $value->parroquia ?></td>
            <!-- <td>
              <?php if ($value->entrega_cotillon >= 0 and $value->entrega_cotillon != NULL) : ?>
                <i class="
                  <?php echo $value->entrega_cotillon == 1 ? "fas fa-check" : 'far fa-times-circle' ?>
                "></i>
                <?php echo $value->entrega_cotillon == 1 ? "Entregado" : "No entregado" ?>
              <?php else : ?>
                sin Registrar
              <?php endif ?>
            </td> -->
            <td><?php echo $value->total_mesas ?></td>
            <!-- <td><?php echo $value->mesas_averiadas ?></td> -->
            <!-- <td><?php echo $value->mesas_instaladas ?></td> -->
            <!-- <td><?php echo media_aritmetica($value->total_mesas, $value->mesas_instaladas) ?></td> -->
            <td><?php echo $value->mesas_constituidas ?></td>
            <td><?php echo number_format(media_aritmetica($value->total_mesas, $value->mesas_constituidas), 2) ?></td>
            <td>
              <a href="<?php echo site_url("mesa_estadisticas/filtro_cv/" . $value->id) ?>"><?php echo $value->mesas_cerradas ?></a>
            </td>
            <td><?php echo number_format(media_aritmetica($value->total_mesas, $value->mesas_cerradas), 2) ?></td>
            <td>
              <button data-id_ubch="<?php echo $value->id ?>" class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#my-modal"><i class="far fa-eye"></i></button>
            </td>
          </tr>
        <?php endforeach ?>

      </tbody>
    </table>
  </div>

  <!-- <div class="py-3">
    <?php echo $this->pagination->create_links() ?>
  </div> -->

</div>

<div id="my-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="my-modal-title">Detalles</h5>
        <button class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="inner_info">

        </div>
        <div id="load_modal">
          <div style="height: 300px;" class="d-flex justify-content-center align-items-center">
            CARGANDO...
          </div>

        </div>
      </div>
    </div>
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


<script>
  const site_url = "<?php echo site_url() ?>/"

  function template_mesas(arr, centro_id = null, accion = null) {
    let tem = `
    <table class="table table-light">
      <thead class="thead-light">
        <tr>
          <th>Numero de Mesa</th>
          <th>Fecha</th>
          ${
            accion ? "<th>accion</th>" : "" 
            
          }
        </tr>
      </thead>
      <tbody>
    `
    arr.forEach(el => {
      tem += `
      <tr>
        <td>${el.numero_mesa}</td> 
        <td>${moment(el.fecha).format("LLL")}</td>
        ${
          accion ? `<td>
          <a href="${site_url + "mesa_status/actualizar_mesa_cerrada?numero_mesa=" + el.numero_mesa + "&cv_id=" + centro_id}" class="btn btn-success"><i class="far fa-edit"></i></a>
          <a onclick="return confirm('desas eliminar el registro de mesa cerrada, al eliminar la mesa cerrada se eliminaran los votos que se ingresaron en ella.')" href="${site_url + "mesa_status/delete_mesa_cerrada?numero_mesa=" + el.numero_mesa + "&cv_id=" + centro_id}" class="btn btn-danger"><i class="far fa-trash-alt"></i></a>
          
          </td>` : "" 
        }

      </tr>
      `;
    });

    tem += `
    </tbody>
    </table>
    `;
    return arr.length > 0 ? tem : "";
  }

  function template_mesas_averiadas(arr) {
    let tem = ``
    arr.forEach(el => {
      tem += `
      <div class="card">
        <h5>Mesa Numero ${el.numero_mesa}</h5>
        <p>
        ${el.observacion}
        </p>
      </div>
      `;
    });

    return tem
  }

  function template_mesas_info(data) {
    let template = `
    <div>
      <div class="alert alert-info mb-3" role="alert">
              <h4 class="alert-heading text-info"> <i class="fas fa-check"></i> material electoral entregado ${moment(data.cotillon.fecha_creacion).format("LLL")}</h4>
            </div>
  
            
            <div class="mb-3">
              <div class="alert alert-secondary mb-3" role="alert">
                Mesas averiadas: ${data.mesas_averiadas.length}
              </div>
  
              <div class="">
                ${template_mesas_averiadas(data.mesas_averiadas)}
              </div>
  
            </div>
  
            <div class="mb-3">
              <div class="alert alert-primary mb-3" role="alert">
                Mesas Instaladas: ${data.mesas_instaladas.length}
              </div>
  
              ${template_mesas(data.mesas_instaladas)}

            </div>
  
            <div class="mb-3">
              <div class="alert alert-success mb-3" role="alert">
                Mesas constituidas: ${data.mesas_constituidas.length}
              </div>
  
              ${template_mesas(data.mesas_constituidas)}
  
            </div>
            
            <div class="mb-3">
              <div class="alert alert-danger mb-3" role="alert">
                Mesas Cerradas: ${data.mesas_cerradas.length}
              </div>
  
              ${template_mesas(data.mesas_cerradas, data.centro_votacion_id, "cierre")}
  
            </div>
  
          </div>
    `;
    return template;
  }

  function cotillon_sin_entregar(data) {
    return `
    <div class="">
      <div class="alert alert-danger"alert">
        <h4 class="alert-heading text-danger">
          <i class="far fa-times-circle"></i>
          material electoral sin entregar
        </h4>
        
        <p>
          ${data.observacion}
        </p>
      </div>
    </div>`;
  }

  function alert_secondary(text) {
    return `<div class="alert alert-secondary" role="alert">
              ${text}
            </div>`;
  }


  const inner_info = document.getElementById("inner_info");
  const load_modal = document.getElementById("load_modal");

  $('#my-modal').on('show.bs.modal', function(e) {
    const btn_target = e.relatedTarget;
    const id_ubch = btn_target.dataset.id_ubch;
    const url = "<?php echo site_url("mesa_estadisticas/info_mesas_por_ubch/") ?>" + id_ubch;

    axios.get(url)
      .then(res => {
        let data = res.data;
        console.log(data.cotillon)
        if (data.cotillon) {
          if (data.cotillon.entregado == 1) {
            inner_info.innerHTML = template_mesas_info(data);

          } else {
            inner_info.innerHTML = cotillon_sin_entregar(data.cotillon);
          }
        } else {
          inner_info.innerHTML = alert_secondary("Sin registro de entrega de materiales");
        }

        load_modal.style.display = "none";

      })
  });


  $(document).ready(function() {
    $('#estadisticas_mesa').DataTable();
  });
</script>