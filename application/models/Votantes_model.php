<?php
class Votantes_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  public function leerVotantesCuadernillos()
  {
    $sql = "SELECT * FROM data_general_cuadernillos";

    return $this->db->query($sql)->result();
  }

  public function leerCentrosVotacion()
  {
    $sql = "SELECT id, NOMBRE_INSTITUCIONES_CON_CODIGO FROM data_general_cuadernillos";

    return $this->db->query($sql)->result();
  }

  public function validateLengthCentroVotacion($idCentro)
  {
    $votantes = $this->db->get_where("votantes", ["centro_votacion_id" => $idCentro])->num_rows();

    $max_votantes = $this->db->select("POBLACION_VOTANTE")->get_where("data_general_cuadernillos", ["id" => $idCentro])->result()[0]->POBLACION_VOTANTE;

    if ($votantes >= $max_votantes) return false;

    //  $lengthCentroVotacion = $this->db->get_where("centro_votacion_id")->num_rows();
    return true;
  }

  public function parroquias()
  {
    $sql = "SELECT DISTINCT dgc.PARROQUIA
       FROM data_general_cuadernillos dgc";
    $parroquias = $this->db->query($sql)->result();

    foreach ($parroquias as $key => $value) {
      $par[$key] = $value->PARROQUIA;
    }
    return $par;
  }

  public function getPaginate($limit, $offset, $tabla = "data_general_cuadernillos")
  {
    $sql = $this->db->get($tabla, $limit, $offset);
    return $sql->result();
  }

  public function sumVotanteRegistrados($parroquia = false)
  {

    if ($parroquia) {
      $sql = "SELECT SUM(POBLACION_VOTANTE)  as 'total' FROM data_general_cuadernillos WHERE PARROQUIA = '$parroquia'";
    } else {
      $sql = "SELECT SUM(POBLACION_VOTANTE)  as 'total' FROM data_general_cuadernillos";
    }



    return $this->db->query($sql)->row();
  }

  public function leerParroquiaConCentroVotacion($idCentro = 1)
  {
    $sql = "SELECT PARROQUIA FROM data_general_cuadernillos WHERE 	id = '$idCentro' LIMIT 1";

    return $this->db->query($sql)->result()[0];
  }

  public function leerConCentroVotacionConParroquia($idCentro = 1)
  {
    $sql = "SELECT 	NOMBRE_INSTITUCIONES_CON_CODIGO FROM data_general_cuadernillos WHERE 	id = '$idCentro' LIMIT 1";

    return $this->db->query($sql)->result()[0];
  }

  public function searchVotante($busqueda = "28000001")
  {
    $this->db->like("cedula", $busqueda);
    $query = $this->db->get("votantes")->result();
    return $query;
  }

  public function insertRandomVotantes()
  {
    $max = 198;
    $aux = 1;

    for ($i = 1; $i < 20000; $i++) {
      $this->db->insert("votantes", [
        "cedula" => 28000000 + $i,
        "centro_votacion_id" => $aux,
        "parroquia" => $this->votantes_model->leerParroquiaConCentroVotacion($aux)->PARROQUIA,
      ]);

      $aux = $aux === 198 ? 1 : $aux + 1;
    }
  }

  public function votantes($limit, $offset)
  {
    $sql = "SELECT dgc.NOMBRE_INSTITUCIONES_CON_CODIGO, dgc.PARROQUIA, vt.id, vt.cedula
      FROM votantes vt 
      INNER JOIN data_general_cuadernillos dgc ON vt.centro_votacion_id = dgc.id
      GROUP BY vt.id, vt.cedula
      ORDER BY vt.fecha_creacion DESC
      LIMIT $limit
      OFFSET $offset";

    return $this->db->query($sql)->result();
  }

  public function votantes_x_ubch($limit, $offset, $id_ubch)
  {
    $sql = "SELECT dgc.NOMBRE_INSTITUCIONES_CON_CODIGO, dgc.PARROQUIA, vt.id, vt.cedula
      FROM votantes vt 
      INNER JOIN data_general_cuadernillos dgc ON vt.centro_votacion_id = dgc.id
      WHERE vt.centro_votacion_id = {$id_ubch}
      GROUP BY vt.id, vt.cedula
      ORDER BY vt.fecha_creacion DESC
      LIMIT $limit
      OFFSET $offset";

    $result =  $this->db->query($sql)->result();
    return $result;
  }

  public function votantes_x_parroquia($limit, $offset, $parroquia)
  {
    $sql = "SELECT dgc.NOMBRE_INSTITUCIONES_CON_CODIGO, dgc.PARROQUIA, vt.id, vt.cedula
      FROM votantes vt 
      INNER JOIN data_general_cuadernillos dgc ON vt.centro_votacion_id = dgc.id
      WHERE vt.parroquia = '{$parroquia}'
      GROUP BY vt.id, vt.cedula
      ORDER BY vt.fecha_creacion DESC
      LIMIT $limit
      OFFSET $offset";

    return $this->db->query($sql)->result();
  }

  public function centroVotacionPorid($id)
  {
    $query = $this->db->select("NOMBRE_INSTITUCIONES")->get_where("data_general_cuadernillos", ["id" => $id])->result();
    return $query[0]->NOMBRE_INSTITUCIONES;
  }

  public function pr($parroquia)
  {
    $sql = "SELECT dgc.NOMBRE_INSTITUCIONES, vtn.id
              FROM data_general_cuadernillos dgc
              INNER JOIN votantes vtn
              ON dgc.id = 1";

    return $this->db->query($sql)->result();
  }



  public function estadisticas_gnerales($limit, $offset)
  {
    $sql = "SELECT 
    dgc.id,
      dgc.COD_UBCH,
        dgc.NOMBRE_INSTITUCIONES_CON_CODIGO,
        FORMAT(dgc.POBLACION_VOTANTE, 0) AS POBLACION_VOTANTE,
        dgc.PARROQUIA,
        FORMAT(COUNT(vt.centro_votacion_id), 0) AS total_general,
        FORMAT(COALESCE( COUNT(vt.centro_votacion_id) * 100 / dgc.POBLACION_VOTANTE),2) AS porcentaje
    FROM data_general_cuadernillos dgc
    LEFT JOIN votantes vt ON  vt.centro_votacion_id = dgc.id 
    GROUP BY dgc.id
    ORDER BY total_general DESC
    LIMiT $limit
    OFFSET $offset";

    return $this->db->query($sql)->result();
  }

  public function estadisticas_por_parroquia($parroquia)
  {
    $sql = "SELECT 
        dgc.id,
        dgc.COD_UBCH,
        dgc.NOMBRE_INSTITUCIONES_CON_CODIGO,
        FORMAT(dgc.POBLACION_VOTANTE, 0) AS POBLACION_VOTANTE,
        dgc.PARROQUIA,
        FORMAT(COUNT(vt.centro_votacion_id), 0) AS total_general,
        FORMAT(COALESCE( COUNT(vt.centro_votacion_id) * 100 / dgc.POBLACION_VOTANTE),2) AS porcentaje
    FROM data_general_cuadernillos dgc
    INNER JOIN votantes vt ON  vt.centro_votacion_id = dgc.id 
    WHERE dgc.PARROQUIA = '$parroquia'
    GROUP BY vt.centro_votacion_id";


    $consulta = $this->db->query($sql)->result();

    if (count($consulta) === 0) {
      $sql = "SELECT * , 0 AS total_general, 0 AS porcentaje
            FROM data_general_cuadernillos 
            WHERE PARROQUIA = '$parroquia'";
      $consulta = $this->db->query($sql)->result();
    }

    return $consulta;
  }

  public function estadisticas_por_ubch($id_ubch)
  {
    $sql = "SELECT 
    dgc.id,
        dgc.COD_UBCH,
        dgc.NOMBRE_INSTITUCIONES_CON_CODIGO,
        FORMAT(dgc.POBLACION_VOTANTE, 0) AS POBLACION_VOTANTE,
        dgc.PARROQUIA,
        FORMAT(COUNT(vt.centro_votacion_id), 0) AS total_general,
        FORMAT(COALESCE( COUNT(vt.centro_votacion_id) * 100 / dgc.POBLACION_VOTANTE),2) AS porcentaje
    FROM data_general_cuadernillos dgc
    INNER JOIN votantes vt ON  vt.centro_votacion_id = dgc.id 
    WHERE dgc.id = $id_ubch
    GROUP BY vt.centro_votacion_id";

    $consulta = $this->db->query($sql)->result();

    if (count($consulta) === 0) {
      $sql = "SELECT * , 0 AS total_general, 0 AS porcentaje
              FROM data_general_cuadernillos 
              WHERE id = $id_ubch";
      $consulta = $this->db->query($sql)->result();
    }

    return $consulta;
  }


  public function cant_votantes_parroquia($parroquia)
  {
    $sql = "SELECT COUNT(*) AS cantidad
              FROM votantes
              WHERE PARROQUIA = '{$parroquia}' AND LENGTH(cedula) >= 7";

    return $this->db->query($sql)->row("cantidad");
  }

  public function det_votantes_parroquia($parroquia = NULL)
  {

    $where_parroquia = (!empty($parroquia)) ? "AND parroquia = '{$parroquia}'" : NULL;

    $sql = "SELECT id, parroquia, cedula, fecha_creacion, importado
              FROM votantes
              WHERE LENGTH(cedula) >= 7 {$where_parroquia} AND importado = 0";

    return $this->db->query($sql)->result();
  }

  public function act_votantes_importados($ids)
  {
    $this->db->set('importado', 1);
    $this->db->where_in('id', $ids);
    return $this->db->update('votantes');
  }
}
