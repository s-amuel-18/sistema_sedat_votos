<?php 
	$this->load->view("template/head.php");
?>

<!-- navbar -->
<?php $this->load->view("components/navbar");?>
<!-- endnavbar -->



<div class="container pt-5">

  <h2 class="mb-4">
    <?php echo $tituloPrincipal?>

  </h2>



  <div class="py-3">
  <form id="form_search" class="d-flex align-items-end my-2 my-lg-0" action="<?php echo site_url("dashboard/searchVotante")?>" method="POST">
  <span>
    <label for="">buscar votante por cedula</label>
      <input name="search" class="form-control mr-sm-2" type="number" placeholder="Search" aria-label="Search">

  </span>
      <button class="btn btn-primary my-2 my-sm-0" type="submit">buscar</button>
    </form>
  </div>


    <!-- tabla instituciones de votacion -->
  <div class="w-100" style="overflow-x: auto;">
    <table class="table table-light">
      <thead class="thead-light">
        <tr>
          <!-- <th>#</th> -->
          <th style="min-width: 150px;">cedula</th>
          <!-- <th>Parroquia</th> -->
          <th style="min-width: 150px;">Nom Inst</th>
          <th style="min-width: 150px;">fechas de registro</th>
        </tr>
      </thead>
      <tbody id="tbody_estadisticas">
    
      <?php

        foreach( $votantes as $value ):
      ?>
        <tr>
          <td style="min-width: 150px;"><?php echo $value->cedula?></td>
          <td style="max-width: 200px;"><?php echo $value->centro_votacion_id?></td>
          <td style="min-width: 150px;"><?php echo $value->fecha_creacion?></td>
    
        </tr>
    
    
      
      <?php
        endforeach;
      ?>

      <?php 
        if( count($votantes) < 1 ) echo "<tr>
          <td >no hay votantes</td>
        </tr>"
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
    <?php echo $this->pagination->create_links()?>

  </div>

</div>

<script>
  const form_search = document.getElementById("form_search");

  form_search.addEventListener("submit", e => {
    const search = e.target.search;

    if( search.value.length < 7 ) {
      e.preventDefault()
      alert("debes ingresar un numero de cedula valido");
    }
  });
</script>



<?php 
	$this->load->view("template/footer.php");
?>


