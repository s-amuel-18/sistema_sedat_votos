<?php

function mesas_estadisticas($table_estadisticas)
{
  $t_cotillones_entregados = 0;
  $t_mesas_totales = 0;
  $t_mesas_const = 0;
  $t_mesas_inst = 0;
  $t_mesas_cerra = 0;

  $template = "<table>
    <thead>
      <tr>
        <th>Nom Inst</th>
        <th>Parroquia</th>
        <th>Entrega <br> material electoral</th>
        <th>Mesas Totales</th>
        <th>Mesas Instaladas</th>
        <th>% Instaladas</th>
        <th>Mesas Constituidas</th>
        <th>% Constituidas</th>
        <th>Mesas Cerradas</th>
        <th>% Cerradas</th>
        
      </tr>
    </thead>
    <tbody>";

  foreach ($table_estadisticas as $value) {
    $t_mesas = $value->total_mesas;
    $por_inst = $value->mesas_instaladas  * 100 / $t_mesas;
    $por_const = $value->mesas_constituidas  * 100 / $t_mesas;
    $por_cerra = $value->mesas_cerradas  * 100 / $t_mesas;

    $t_mesas_totales += $t_mesas;
    $t_mesas_inst += $value->mesas_instaladas;
    $t_mesas_const += $value->mesas_constituidas;
    $t_mesas_cerra += $value->mesas_cerradas;
    
    $template .= "
        <tr>
        <td>{$value->nombre_instituciones_con_codigo}</td>
        <td>{$value->parroquia}</td>
        <td>

        ";

    if ($value->entrega_cotillon >= 0 and $value->entrega_cotillon != NULL) {
      $entregado_valid = $value->entrega_cotillon == 1 ? "Entregado" : "No entregado";
      $t_cotillones_entregados += $value->entrega_cotillon;

      $template .= "$entregado_valid";
    } else {
      $template .= "Sin registrar";
    }
    $template .= "      </td>
        <td>{$value->total_mesas}</td>
        <td>{$value->mesas_instaladas}</td>
        <td>{$por_inst}</td>
        <td>{$value->mesas_constituidas}</td>
        <td>{$por_const}</td>
        <td>{$value->mesas_cerradas}</td>
        <td>{$por_cerra}</td>
      </tr>";
  }
  $p_mesas_inst = $t_mesas_inst * 100 / $t_mesas_totales;
  $p_mesas_const = $t_mesas_const * 100 / $t_mesas_totales;
  $p_mesas_cerra = $t_mesas_cerra * 100 / $t_mesas_totales;

  $template .= "
    <tr>
      <td>TOTAL</td>
      <td></td>
      <td>$t_cotillones_entregados</td>
      <td>$t_mesas_totales</td>
      <td>$t_mesas_inst</td>
      <td>$p_mesas_inst</td>
      <td>$t_mesas_const</td>
      <td>$p_mesas_const</td>
      <td>$t_mesas_cerra</td>
      <td>$p_mesas_cerra</td>
    </tr>
    
  ";

  $template .= "</tbody>
    </table>";
  $file = "reporte.xls";
  $test = $template;

  header("Content-type: application/vnd.ms-Excel");
  header("Content-Disposition: attachment; filename=$file");
  echo $test;
}

function mesas_estadisticas_parroquias($table_estadisticas)
{
  $t_cotillones_entregados = 0;
  $t_cotillones_sin_entregar = 0;
  $t_mesas_totales = 0;
  $t_mesas_const = 0;
  $t_mesas_inst = 0;
  $t_mesas_cerra = 0;

  $template = "<table>
    <thead>
    <tr>
      <th>Parroquia</th>
      <th>Ubchs</th>
      <th>materiales electorales entregados</th>
      <th>materiales electorales registrados sin entregar</th>
      <th>Mesas Totales</th>
      <th>Mesas Instaladas</th>
      <th>% Instaladas</th>
      <th>Mesas Constituidas</th>
      <th>% Constituidas</th>
      <th>Mesas Cerradas</th>
      <th>% Cerradas</th>
    </tr>
    </thead>
    <tbody>";

  foreach ($table_estadisticas as $value) {
    $t_mesas = $value["cant_mesas_total"];
    $por_inst = number_format($value["mesas_instaladas"]  * 100 / $t_mesas, 2);
    $por_const = number_format($value["mesas_constituidas"]  * 100 / $t_mesas, 2);
    $por_cerra = number_format($value["mesas_cerradas"]  * 100 / $t_mesas, 2);

    $t_cotillones_entregados += $value["total_cotillones_entregados"];
    $t_cotillones_sin_entregar += $value["total_cotillones_sin_entregar"];
    $t_mesas_totales += $t_mesas;
    $t_mesas_inst += $value["mesas_instaladas"];
    $t_mesas_const += $value["mesas_constituidas"];
    $t_mesas_cerra += $value["mesas_cerradas"];
    
    $template .= "      
      <tr>
        <td> {$value["PARROQUIA"]}</td>
        <td> {$value["ubch_por_parroquia"]}</td>
        <td> {$value["total_cotillones_entregados"]}</td>
        <td> {$value["total_cotillones_sin_entregar"]}</td>
        <td> {$value["cant_mesas_total"]}</td>
        <td> {$value["mesas_constituidas"]}</td>
        <td> {$por_const}</td>
        <td> {$value["mesas_instaladas"]}</td>
        <td> {$por_inst}</td>
        <td> {$value["mesas_cerradas"]}</td>
        <td> {$por_cerra}</td>
      </tr>";
  }

  $p_mesas_inst = $t_mesas_inst * 100 / $t_mesas_totales;
  $p_mesas_const = $t_mesas_const * 100 / $t_mesas_totales;
  $p_mesas_cerra = $t_mesas_cerra * 100 / $t_mesas_totales;

  $template .= "
    <tr>
      <td>TOTAL</td>
      <td></td>
      <td>$t_cotillones_entregados</td>
      <td>$t_cotillones_sin_entregar</td>
      <td>$t_mesas_totales</td>
      <td>$t_mesas_inst</td>
      <td>$p_mesas_inst</td>
      <td>$t_mesas_const</td>
      <td>$p_mesas_const</td>
      <td>$t_mesas_cerra</td>
      <td>$p_mesas_cerra</td>
    </tr>
    
  ";

  $template .= "</tbody>
    </table>";
  $file = "reporte.xls";
  $test = $template;

  header("Content-type: application/vnd.ms-Excel");
  header("Content-Disposition: attachment; filename=$file");
  echo $test;
}

function candidatos($candidatos_estadistica)
{
  $t = "

  <table class='table table-light'>
  <thead class='thead-light'>
    <tr>
      <th style='min-width: 150px;'>Nombre</th>
      <th style='min-width: 150px;'>Cargo</th>
      <th style='min-width: 150px;'>Partido</th>
      <th style='min-width: 150px;'>Votos</th>
      <th style='min-width: 150px;'>Votos Totales</th>
      <th style='min-width: 150px;'>%</th>
    </tr>
  </thead>
  <tbody id='tbody_estadisticas'>
  ";

  $votos_totales = 0; 

  foreach ($candidatos_estadistica as $value) {

    $sg_cant_votos = number_format($value->cant_votos, 0);
    $sg_total_general = number_format($value->total_general, 0);
    $votos_totales += $sg_cant_votos;

    $t .= "
    <tr>
    <td>{$value->nombre_y_apellido} }</td>
    <td>{$value->cargo}</td>
    <td>{$value->partido}</td>
    <td>{$sg_cant_votos}</td>
    <td>{$sg_total_general}</td>
    <td>{$value->porcentaje}</td>
  </tr>
    ";
  }



  $t .= "
  <tr>
    <td>TOTAL</td>
    <td></td>
    <td></td>
    <td>$votos_totales</td>
    <td></td>
    <td></td>
  </tr>

  
  </tbody>
  </table>
  ";

  $file = "reporte.xls";
  $test = $t;

  header("Content-type: application/vnd.ms-Excel");
  header("Content-Disposition: attachment; filename=$file");
  echo $test;
}


function votantes($candidatos_estadistica)
{
  $t = "

  <table class='table table-light'>
  <thead class='thead-light'>
    <tr>
    <th style='min-width: 150px;'>Cod UBCH</th>
    <th style='min-width: 150px;'>Parroquia</th>
    <th style='min-width: 150px;'>Nom Inst</th>
    <th style='min-width: 150px;'>Votantes Registrados</th>
    <th style='min-width: 150px;'>Votantes Confirmados</th>
    <th style='min-width: 150px;'>% Votantes Confirmados</th>
    </tr>
  </thead>
  <tbody id='tbody_estadisticas'>
  ";

  foreach ($candidatos_estadistica as $value) {
    $t .= "
    <tr>
      <td style='min-width: 150px;'> {$value->COD_UBCH} </td>
      <td style='min-width: 150px;'> {$value->PARROQUIA} </td>
      <td style='max-width: 100px;'> {$value->NOMBRE_INSTITUCIONES_CON_CODIGO} </td>
      <td style='min-width: 150px;'> {$value->POBLACION_VOTANTE} </td>
      <td style='min-width: 150px;'> {$value->total_general} </td>
      <td style='min-width: 150px;'> {$value->porcentaje} %</td>
    </tr>
    ";
  }

  $t .= "
  </tbody>
  </table>
  ";

  $file = "reporte.xls";
  $test = $t;

  header("Content-type: application/vnd.ms-Excel");
  header("Content-Disposition: attachment; filename=$file");
  echo $test;
}


function votantes_parroquia($candidatos_estadistica)
{
  $t = "

  <table class='table table-light'>
  <thead class='thead-light'>
    <tr>
    <th >Parroquia</th>
    <th >Votantes Registrados</th>
    <th >Votantes Confirmados</th>
    <th % </th>
    </tr>
  </thead>
  <tbody id='tbody_estadisticas'>
  ";

  foreach ($candidatos_estadistica as $key => $value) {
    $t .= "<tr>
    <td>{$key }</td>
    <td>{$value["VOTANTES_REGISTRADOS"] }</td>
    <td>{$value["VOTANTES_CONFIRMADOS"] }</td>
    <td>{$value["PORCENTAJE_VOTANTES_CONFIRMADOS"] }%</td>
    </tr>
    ";
  }

  $t .= "
  </tbody>
  </table>
  ";

  $file = "reporte.xls";
  $test = $t;

  header("Content-type: application/vnd.ms-Excel");
  header("Content-Disposition: attachment; filename=$file");
  echo $test;
}
?>
