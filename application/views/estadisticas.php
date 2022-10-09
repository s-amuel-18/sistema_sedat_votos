<?php 
	$this->load->view("template/head.php");
?>

<style>
	.b-red {
		border: 2px solid red;
	}
</style>

<div class="container pt-5">
  <h2 class="mb-4">
    <?php echo $tituloPrincipal?>
    <?php if( isset($parroquiaFilter)):?>
          <a href="<?php echo site_url()?>/hoja_de_calculo/estadisticas" class="btn btn-outline-primary"><i class="fas fa-arrow-left"></i></a>
    <?php endif?>
  </h2>


  
  <div class="row mb-5">
    <div class="py-2 col-12 col-md-4">

      <label for="">filtrar por Instituciones </label>
        
      <select id="filtro_votantes" class="mb-3 form-control">

        <option value="-1">--MOSTRAR TODOS--</option>
        
        <?php 
        foreach ($estadistica_votantes as $key => $value):
          ?>
          <option value="<?php echo $key?>"><?php echo $value->NOMBRE_INSTITUCIONES?></option>
      
        <?php 
          endforeach;
        ?>
        
      </select>


    </div>


    <div class="py-2 col-12 col-md-4">

      <form id="form_filtro_parroquia" action="<?php echo site_url()?>/hoja_de_calculo/filtroPorParroquia" method="POST">
        <label for="">filtrar por Parroquias </label>
          
        <select name="parroquia" id="filtro_parroquia" class="mb-3 form-control">
    
          <!-- <option value="-1">--MOSTRAR TODOS--</option> -->
          
          <?php 
          foreach ($parroquias as $key => $value):
            ?>
            <option
              <?php  echo (isset($parroquiaFilter) and $parroquiaFilter == $value) ? "selected" : ""?>
              value="<?php echo $value?>">
                <?php echo $value?>
            </option>
    
          <?php 
            endforeach;
          ?>
          
        </select>

      </form>
      


      </div>
    

    <div class="py-2 col-12 col-md-4 ">
 
          <!-- <form action="<?php echo site_url()?>/hoja_de_calculo/filtroPorParroquia" method="POST">
            <input type="hidden" name="parroquia" value="<?php echo $parroquias[0]?>">
            <button class="btn btn-primary mr-md-2" type="submit"><i class="fas fa-search"></i> filtrar parroquaia</button>
          </form> -->
          <h5>estadisticas generales</h5>

          <p>Votantes Registrados: <?php echo $sumaVotandesReg?></p>
          <div class="progress">
            <div class="progress-bar bg-primary" style="width: 100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">100%</div>
          </div>

          <br>
          
          <p>Votantes Confirmados: <?php echo $sumaVotandesConf?> (<?php echo $porcentajeSumaVotandesConf?>%)</p>
          <div class="progress">
            <div class="progress-bar bg-danger" style="width:  <?php echo $porcentajeSumaVotandesConf?>%"  role="progressbar" aria-valuenow="<?php echo $porcentajeSumaVotandesConf?>" aria-valuemin="0" aria-valuemax="100"><?php echo $porcentajeSumaVotandesConf?>%</div>
          </div>
    

      <!-- <a href="" class="btn btn-success btn-block"><i class="fas fa-file-excel"></i> descargar estadisticas</a> -->
    </div>
  </div>


  <!-- tabla instituciones de votacion -->
  <div class="w-100" style="overflow-x: auto;">
    <table class="table table-light">
      <thead class="thead-light">
        <tr>
          <!-- <th>#</th> -->
          <th style="min-width: 150px;">Cod UBCH</th>
          <!-- <th>Parroquia</th> -->
          <th style="min-width: 150px;">Nom Inst</th>
          <th style="min-width: 150px;">Votantes <br> Registrados</th>
          <th style="min-width: 150px;">Votantes <br> Confirmados</th>
          <th style="min-width: 150px;">% Votantes Confirmados</th>
          <th style="min-width: 150px;">cuader vs google</th>
        </tr>
      </thead>
      <tbody id="tbody_estadisticas">
    
      <?php

        foreach( $estadistica_votantes as $value ):
      ?>
        <tr>
          <td style="min-width: 150px;"><?php echo $value->COD_UBCH?></td>
          <td style="max-width: 100px;"><?php echo $value->NOMBRE_INSTITUCIONES?></td>
          <td style="min-width: 150px;"><?php echo $value->VOTANTES_REGISTRADOS?></td>
          <td style="min-width: 150px;"><?php echo $value->VOTANTES_CONFIRMADOS?></td>
          <td style="min-width: 150px;"><?php echo $value->PORCENTAJE_VOT_CONFIRMADOS?>%</td>
    
          <td style="min-width: 150px;">
            <div class="progress">
              <div class="progress-bar bg-primary" style="width: 100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">100%</div>
            </div>
            <br>
            <div class="progress">
              <div class="progress-bar bg-danger" style="width: 50%" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">50</div>
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
  

</div>


<?php 
	$this->load->view("template/footer.php");
?>


<script>

  
  const filtro_votantes = document.getElementById("filtro_votantes");
  const filtro_parroquia = document.getElementById("filtro_parroquia");
  const form_filtro_parroquia = document.getElementById("form_filtro_parroquia");
  const estadisticas_row = document.querySelectorAll("#tbody_estadisticas tr");



  // funciones
  function displayStyle( /* elements */ elements, /* none */ none){

    for (let i = 0; i < elements.length; i++) {
      if( none != "-1" ){
        if( i != none ) {
          elements[i].style.display = "none";
        } else{
          elements[i].style.display = "table-row";
          
        }
      } else {
        elements[i].style.display = "table-row";

      }
    }

  }

  // console.log(estadisticas_row);

  filtro_parroquia.addEventListener("change", e => {
    form_filtro_parroquia.submit();
  });
  filtro_votantes.addEventListener("change", e => {
    const optionSelect = e.target.value;
    displayStyle(estadisticas_row, optionSelect);

  });
  
</script>