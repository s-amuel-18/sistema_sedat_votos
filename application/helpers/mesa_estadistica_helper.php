<?php 
function mesa_estadisticas($data, $db_class) {
  
  foreach ($data as $i => $value) {
    $new_data[$i]["id"] =  $value->id;
    $new_data[$i]["COD_UBCH"] =  $value->COD_UBCH;
    $new_data[$i]["NOMBRE_INSTITUCIONES_CON_CODIGO"] =  $value->NOMBRE_INSTITUCIONES_CON_CODIGO;
    $new_data[$i]["PARROQUIA"] =  $value->PARROQUIA;

    $mesas_instaladas = $db_class->get_where("mesas_instalacion", ["centro_votacion_id" => $value->id])->num_rows();
    $new_data[$i]["mesas_instaladas"] =  $mesas_instaladas;

    $mesas_constituidas = $db_class->get_where("mesas_constitucion", ["centro_votacion_id" => $value->id])->num_rows();
    $new_data[$i]["mesas_constituidas"] =  $mesas_constituidas;

    $new_data[$i]["mesas_cerradas"] =  $db_class->get_where("mesas_cierre", ["centro_votacion_id" => $value->id])->num_rows();

  }
  return $new_data;
}

function reporte_parroquia_data($thiss) {
  $parroquias = $thiss->votantes_model->parroquias();

  foreach ($parroquias as $i =>$value) {
    $data_reporte[$i]["PARROQUIA"] = $value;

    $m_instaldas = $thiss->db->get_where("mesas_instalacion", ["parroquia" => $value, "averiado" => 0]);
    $data_reporte[$i]["mesas_instaladas"] = $m_instaldas->num_rows();

    $total_cotillones = $thiss->db->get_where("entrega_cotillon", ["parroquia" => $value, "entregado" => 1])->num_rows();
    $data_reporte[$i]["total_cotillones_entregados"] = $total_cotillones;
    
    $total_cotillones_sin = $thiss->db->get_where("entrega_cotillon", ["parroquia" => $value, "entregado" => 0])->num_rows();
    $data_reporte[$i]["total_cotillones_sin_entregar"] = $total_cotillones_sin;

    $m_constitucion = $thiss->db->get_where("mesas_constitucion", ["parroquia" => $value]);
    $data_reporte[$i]["mesas_constituidas"] = $m_constitucion->num_rows();

    $mesas_totales = $thiss->mesa_model->count_mesas_por_parroquia($value);
    $data_reporte[$i]["cant_mesas_total"] = $mesas_totales;

    $ubch_por_parroquia = $thiss->db->get_where(
      "data_general_cuadernillos",
      [
        "PARROQUIA" => $value
      ]
    )->num_rows();
    $data_reporte[$i]["ubch_por_parroquia"] = $ubch_por_parroquia;
    
    $m_cerradas = $thiss->db->get_where("mesas_cierre", ["parroquia" => $value]);
    $data_reporte[$i]["mesas_cerradas"] = $m_cerradas->num_rows();

  }
  
  return $data_reporte;
}

function create_data_cotillon($thiss, $parroquias) {

  foreach ($parroquias as $parroquia) {
    $dgcs = $thiss->db->get_where("data_general_cuadernillos", [
      "PARROQUIA" => $parroquia
    ])->result();

    foreach ($dgcs as $dgc) {
      $thiss->db->insert(
        "entrega_cotillon",
        [
          "centro_votacion_id" => $dgc->id,
          "user_id" => 2,
          "parroquia" => $dgc->PARROQUIA,
          "entregado" => 1
        ]
      );
    }

  }
  
  // var_dump($dgc);die();

}