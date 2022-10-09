<?php
$this->load->view("template/head.php");
?>

<!-- navbar -->
<?php $this->load->view("components/navbar"); ?>
<!-- endnavbar -->

<div style="background-color: #ececec;">
  <div class="container">
    <div class="d-flex align-items-center" style="height: 100vh;">
      <div class="text-center shadow col-12 col-sm-8 offset-sm-1 col-md-6 offset-md-3 bg-white d-flex align-items-center justify-content-center flex-column p-4" style="border-radius: 20px; min-height: 400px;">

        <h1 class="mb-5"><?php echo $title_page ?></h1>

        <?php if (validation_errors() or isset($error_message)) : ?>
          <div class="alert alert-danger text-danger" role="alert">
            <?php echo validation_errors("<div>", "*</div>") ?>
            <?php if (isset($error_message)) : ?>
              <?php echo $error_message ?>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <div class="form-group search_methods" style="display: none;">
          <form id="form_search" class="form-inline" method="GET" action="<?php echo site_url("votacion/searchUbch") ?>">
            <div class="form-group">
              <input id="search" class="form-control" type="search" autofocus name="search" placeholder="centro de votacion">
              <input type="hidden" name="url" value="mesa_status/status_mesa/">
              <button class="btn btn-primary" type="submit">buscar</button>
            </div>
          </form>
        </div>

        <div class="form-group search_methods">

          <div class="form-group">
            <label for="select_parroquia">Seleccionar Parroquia</label>
            <select class="form-control" id="select_parroquia">
              <option value="all">TODOS</option>
              <?php foreach ($parroquias as $pr) : ?>
                <option value="<?php echo url_title($pr, "-", true) ?>">
                  <?php echo $pr ?>
                </option>
              <?php endforeach ?>
            </select>
          </div>

          <div class="form-group">
            <label for="cedula">Nombre de Centro de Votación</label>
            <select onchange="document.getElementById('link_next').href = '<?php echo site_url('mesa_status/status_mesa/') ?>' + this.value" class="form-control" name="centro_votacion" id="centro_votacion">

              <?php foreach ($this->ubch as $value) : ?>
                <option class="<?php echo url_title($value->PARROQUIA, "-", true) ?> select_ubch" value="<?php echo $value->id ?>">
                  <?php echo $value->NOMBRE_INSTITUCIONES_CON_CODIGO ?>
                </option>

              <?php endforeach ?>

            </select>

          </div>


          <br>


          <a href="mesa_status/status_mesa/1" id="link_next" class="btn btn-primary">Siguiente</a>
        </div>




        <div class="">
          <button onclick="mostrarMetodoBusqueda(this.dataset.index)" data-index="0" class="btn btn-outline-primary btn-sm" type="button"><i class="fas fa-search"></i></button>

          <button onclick="mostrarMetodoBusqueda(this.dataset.index)" data-index="1" class="btn btn-outline-primary btn-sm " type="button"><i class="fas fa-list-ul"></i></button>

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

<script>
  const form_search = document.getElementById("form_search");
  const search = document.getElementById("search");
  form_search.addEventListener("submit", e => {
    if (search.value.replace(" ", "").length == 0) e.preventDefault()
  });
</script>

<script>
  const select_parroquia = document.getElementById("select_parroquia");
  const select_ubch = document.querySelectorAll(".select_ubch");

  select_parroquia.addEventListener("change", e => {
    const pr_seleccionadad = e.target.value;

    for (let i = 0; i < select_ubch.length; i++) {
      select_ubch[i].style.display = "none";
    }

    if (pr_seleccionadad == "all") {
      for (let i = 0; i < select_ubch.length; i++) {
        select_ubch[i].style.display = "block";
      }
    } else {
      const elements_block = document.querySelectorAll("." + pr_seleccionadad);
      for (let i = 0; i < elements_block.length; i++) {
        elements_block[i].style.display = "block";
      }
    }

  })
</script>

<?php
$this->load->view("template/footer.php");
?>