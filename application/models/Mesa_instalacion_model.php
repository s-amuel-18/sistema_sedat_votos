<?php
class Mesa_instalacion_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table = "mesas_instalacion";
  }

  public function insertar($data)
  {
    //  $values_add = "";
    //  $data_add = "";
    //  if( isset($data["averiado"]) ) {
    //   $values_add = "averiado, observacion";
    //  }

    $t = $this->table;
    $sql = "INSERT INTO $t (centro_votacion_id, user_id, numero_mesa, parroquia)
             VALUE (
               $data[centro_votacion_id],
               $data[user_id],
               $data[numero_mesa],
               '$data[parroquia]')";

    return $this->db->query($sql);
  }

  public function validate_mesa($id_ubch, $numero_mesa)
  {
    // $consulta = "SELECT  dgc.COD_UBCH, dgc.PARROQUIA, dgc.NOMBRE_INSTITUCIONES, mco.numero_mesa
    // FROM mesas_constitucion mco
    // LEFT JOIN mesas_cierre mci ON mco.numero_mesa = mci.numero_mesa AND mco.centro_votacion_id = mci.centro_votacion_id
    // INNER JOIN data_general_cuadernillos dgc ON dgc.id = mco.centro_votacion_id 
    // WHERE mci.numero_mesa IS NULL
    // GROUP BY mci.id, mco.id
    // ORDER BY mci.id";
    
    $t = $this->table;

    $sql = "SELECT * FROM $t WHERE (centro_votacion_id = $id_ubch AND numero_mesa = $numero_mesa)";

    return $this->db->query($sql)->row();
  }

  public function mesas_instaladas_sin_averiar_y_constituir($id_ubch)
  {
    $mesas_instaladas = $this->db->get_where(
      "mesas_instalacion",
      [
        "centro_votacion_id" => $id_ubch,
        "averiado" => 0
      ]
    )->result();

    $return_mesas_data = [];

    foreach ($mesas_instaladas as  $mes_ins) {
      $mesas_constituida = $this->db->get_where(
        "mesas_constitucion",
        [
          "centro_votacion_id" => $mes_ins->centro_votacion_id,
          "numero_mesa" => $mes_ins->numero_mesa
        ]
      )->row();
      if (empty($mesas_constituida)) {
        $return_mesas_data[count($return_mesas_data)] = $mes_ins;
      }
    }

    // die();
    return $return_mesas_data;
    // var_dump("<pre>", $mesas_instaladas);die();
  }

  public function estadistica_mesas($limit = null, $offset = null, $condicional = null)
  {
    $lim = $limit ? "LIMIT $limit" : "";
    $off = $limit ? "OFFSET $offset" : "";
    $where = $condicional ? $condicional : "";

    $sql = "SELECT 
     dgc.id,  dgc.parroquia, dgc.nombre_instituciones_con_codigo, dgc.cod_ubch,
       (SELECT COUNT(mi.id) FROM mesas_instalacion mi WHERE mi.centro_votacion_id = dgc.id AND mi.averiado = 0) AS mesas_instaladas,
       (SELECT COUNT(mi.id) FROM mesas_instalacion mi WHERE mi.centro_votacion_id = dgc.id AND mi.averiado = 1) AS mesas_averiadas,
       (SELECT COUNT(mce.id) FROM mesas_cierre mce WHERE mce.centro_votacion_id = dgc.id) AS mesas_cerradas,
       (SELECT COUNT(mc.id) FROM mesas_constitucion mc WHERE mc.centro_votacion_id = dgc.id) AS mesas_constituidas,
       (SELECT m.MESAS FROM mesas m WHERE m.COD_UBCH = dgc.COD_UBCH LIMIT 1) AS total_mesas,
       (SELECT ec.entregado FROM entrega_cotillon ec WHERE dgc.id = ec.centro_votacion_id LIMIT 1) AS entrega_cotillon
      FROM data_general_cuadernillos dgc
      $where
      ORDER BY mesas_instaladas  DESC, entrega_cotillon DESC
      $lim
      $off
    ";

    return $this->db->query($sql)->result();
  }
}
