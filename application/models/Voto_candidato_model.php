<?php
class Voto_candidato_model extends CI_Model
{
   public function __construct()
   {
      parent::__construct();
   }

   public function estadistica_candidato()
   {
      $sql = "SELECT * 
         FROM ( 
         SELECT vc.candidato_id,c.nombre_y_apellido, c.cargo, c.partido, SUM(vc.votos) AS cant_votos,
         (SELECT SUM(vca.votos) AS cant_votos
         FROM votos_candidatos vca
         INNER JOIN candidatos ca ON ca.id = vca.candidato_id
         WHERE ca.cargo = c.cargo 
         GROUP BY ca.cargo
         )AS total_general,
         NULL AS porcentaje
         FROM votos_candidatos vc
                    INNER JOIN candidatos c ON c.id = vc.candidato_id
                    GROUP BY c.nombre_y_apellido, c.cargo, c.partido,vc.candidato_id
         ) datos
         ORDER BY cargo ASC, candidato_id ASC";
      return $this->db->query($sql)->result();
   }

   public function votos_totales_por_cargo()
   {
      $sql = "SELECT c.cargo, SUM(vca.votos) AS 'total_general'
      FROM votos_candidatos vca
      INNER JOIN candidatos c ON vca.candidato_id = c.id
      WHERE c.cargo = c.cargo
      GROUP BY c.cargo";

      return $this->db->query($sql)->result();
   }

   public function estadistica_candidato_por_parroquia($parroquia)
   {
      $sql = "SELECT * 
      FROM ( 
      SELECT vc.candidato_id,c.nombre_y_apellido, c.cargo, c.partido, SUM(vc.votos) AS cant_votos,
      (SELECT SUM(vca.votos) AS cant_votos
      FROM votos_candidatos vca
      INNER JOIN candidatos ca ON ca.id = vca.candidato_id
      WHERE ca.cargo = c.cargo AND vca.parroquia = '$parroquia'
      GROUP BY ca.cargo
      )AS total_general,
      NULL AS porcentaje
      FROM votos_candidatos vc
                 INNER JOIN candidatos c ON c.id = vc.candidato_id
                 WHERE vc.parroquia = '$parroquia'
                 GROUP BY c.nombre_y_apellido, c.cargo, c.partido,vc.candidato_id
      ) datos
      ORDER BY cargo ASC, candidato_id ASC";
      // $sql = "SELECT * 
      // FROM ( 
      // SELECT vc.candidato_id,c.nombre_y_apellido, c.cargo, c.partido, SUM(vc.votos) AS cant_votos,
      // (SELECT SUM(vca.votos) AS cant_votos
      // FROM votos_candidatos vca
      // INNER JOIN candidatos ca ON ca.id = vca.candidato_id
      // WHERE ca.cargo = c.cargo 
      // GROUP BY ca.cargo
      // )AS total_general,
      // NULL AS porcentaje
      // FROM votos_candidatos vc
      //            INNER JOIN candidatos c ON c.id = vc.candidato_id
      //            WHERE vc.parroquia = '$parroquia'
      //            GROUP BY c.nombre_y_apellido, c.cargo, c.partido,vc.candidato_id
      // ) datos
      // ORDER BY cargo ASC, candidato_id ASC";
      return $this->db->query($sql)->result();
   }

   public function estadistica_candidato_por_ubch($id_ubch)
   {
      $sql = "SELECT vc.candidato_id,c.nombre_y_apellido, c.cargo, c.partido, SUM(vc.votos) AS cant_votos,
      (SELECT SUM(vca.votos) AS cant_votos
      FROM votos_candidatos vca
      INNER JOIN candidatos ca ON ca.id = vca.candidato_id
      WHERE ca.cargo = c.cargo AND vca.centro_votacion_id = '$id_ubch'
      GROUP BY ca.cargo
      )AS total_general,
      NULL AS porcentaje
      FROM votos_candidatos vc
                    INNER JOIN candidatos c ON c.id = vc.candidato_id
                    WHERE vc.centro_votacion_id = $id_ubch
                    GROUP BY c.nombre_y_apellido, c.cargo, c.partido,vc.candidato_id
                    ORDER BY cargo ASC, candidato_id ASC";
      return $this->db->query($sql)->result();
   }

   public function totalizacion_votos(){
      $sql = "SELECT SUM(vca.votos)
              FROM votos_candidatos vca";
   }

   public function votos_totales_por_cargo_y_parroquia($parroquia)
   {
      $sql = "SELECT c.cargo, SUM(vca.votos) AS 'total_general'
      FROM votos_candidatos vca
      INNER JOIN candidatos c ON vca.candidato_id = c.id
      WHERE c.cargo = c.cargo AND vca.parroquia = '$parroquia'
      GROUP BY c.cargo";

      return $this->db->query($sql)->result();
   }

   public function votos_totales_por_cargo_y_ubch($id_ubch)
   {
      $sql = "SELECT c.cargo, SUM(vca.votos) AS 'total_general'
      FROM votos_candidatos vca
      INNER JOIN candidatos c ON vca.candidato_id = c.id
      WHERE c.cargo = c.cargo AND vca.centro_votacion_id = $id_ubch
      GROUP BY c.cargo";

      return $this->db->query($sql)->result();
   }

   public function candidatos_por_mesas($cv, $num_mesa)
   {
      $sql = "SELECT can.*, vc.votos
              FROM votos_candidatos vc
              inner JOIN candidatos can ON can.id = vc.candidato_id
              WHERE vc.centro_votacion_id = $cv AND vc.numero_mesa = $num_mesa
              GROUP BY vc.id
              ORDER BY can.cargo ASC, can.id ASC
";
      return $this->db->query($sql)->result();
   }

   public function update_votos($id_ubch, $num_mesa, $candidato_id, $cant_votos)
   {
      $sql = "UPDATE `votos_candidatos`SET votos = $cant_votos
      WHERE centro_votacion_id = $id_ubch AND numero_mesa = $num_mesa AND candidato_id = $candidato_id";

return $this->db->query($sql);
   }

   public function candidato_voto_par($pr)
   {
      $sql = "SELECT SUM(vc.votos) AS votos, c.nombre_y_apellido, c.cargo
              FROM candidatos c
              INNER JOIN votos_candidatos vc ON vc.candidato_id = c.id
              WHERE vc.parroquia = '$pr'
              GROUP BY c.id
              ORDER BY c.cargo ASC, vc.candidato_id ASC";
      return $this->db->query($sql)->result();
   }


   public function reporte_votos_por_par_candidato_excel()
   {

      $sql = "SELECT vc.parroquia,
      (SELECT SUM(votos) FROM votos_candidatos WHERE parroquia = vc.parroquia)'total_general',
      (SELECT SUM(votos) FROM votos_candidatos WHERE parroquia = vc.parroquia AND candidato_id IN (1,2,3,4))'TOTAL_GOBERNADOR',
      (SELECT SUM(votos) FROM votos_candidatos WHERE parroquia = vc.parroquia AND candidato_id IN (23,24,25,26,27))'TOTAL_ALCALDE',
      (SELECT SUM(votos) FROM votos_candidatos WHERE parroquia = vc.parroquia AND candidato_id = 1)'HECTOR_RODRIGUEZ',
      (SELECT SUM(votos) FROM votos_candidatos WHERE parroquia = vc.parroquia AND candidato_id = 2)'DAVID_UZCATEGUI',
      (SELECT SUM(votos) FROM votos_candidatos WHERE parroquia = vc.parroquia AND candidato_id = 3)'OTROS_GOBERNADORES',
      (SELECT SUM(votos) FROM votos_candidatos WHERE parroquia = vc.parroquia AND candidato_id = 4)'NULO_GOBERNADOR',
      (SELECT SUM(votos) FROM votos_candidatos WHERE parroquia = vc.parroquia AND candidato_id = 23)'JOSE_RANGEL',
      (SELECT SUM(votos) FROM votos_candidatos WHERE parroquia = vc.parroquia AND candidato_id = 24)'ANDRES_SCHLOETER',
      (SELECT SUM(votos) FROM votos_candidatos WHERE parroquia = vc.parroquia AND candidato_id = 26)'ROSIRIS_TORO',
      (SELECT SUM(votos) FROM votos_candidatos WHERE parroquia = vc.parroquia AND candidato_id = 27)'NULO_ALCALDE',
      (SELECT SUM(dgc.POBLACION_VOTANTE) FROM data_general_cuadernillos dgc 
      WHERE vc.parroquia = dgc.PARROQUIA) AS POBLACION_VOTANTE,
      (SELECT SUM(votos) FROM votos_candidatos WHERE parroquia = vc.parroquia AND candidato_id = 25)'OTROS_ALCALDES'
      FROM votos_candidatos vc
      GROUP BY parroquia";
      
      
      // $sql = "SELECT vc.parroquia,
      // (SELECT ROUND(SUM(votos) / 2) FROM votos_candidatos WHERE parroquia = vc.parroquia)'total_general',
      // (SELECT SUM(votos) FROM votos_candidatos WHERE parroquia = vc.parroquia AND candidato_id = 1)'HECTOR_RODRIGUEZ',
      // (SELECT SUM(votos) FROM votos_candidatos WHERE parroquia = vc.parroquia AND candidato_id = 2)'DAVID_UZCATEGUI',
      // (SELECT SUM(votos) FROM votos_candidatos WHERE parroquia = vc.parroquia AND candidato_id = 3)'OTROS_GOBERNADORES',
      // (SELECT SUM(votos) FROM votos_candidatos WHERE parroquia = vc.parroquia AND candidato_id = 23)'JOSE_RANGEL',
      // (SELECT SUM(votos) FROM votos_candidatos WHERE parroquia = vc.parroquia AND candidato_id = 24)'ANDRES_SCHLOETER',
      // (SELECT SUM(votos) FROM votos_candidatos WHERE parroquia = vc.parroquia AND candidato_id = 26)'ROSIRIS_TORO',
      // (SELECT SUM(votos) FROM votos_candidatos WHERE parroquia = vc.parroquia AND candidato_id = 25)'OTROS_ALCALDES',
      // (SELECT SUM(dgc.POBLACION_VOTANTE) FROM data_general_cuadernillos dgc WHERE vc.parroquia = dgc.PARROQUIA) 'poblacion_votante'
      // FROM votos_candidatos vc
      // GROUP BY parroquia";

      return $this->db->query($sql)->result();
   }

   public function reporte_votos_ubch_candidato_excel($condicional = null)
   {
      $condicional = $condicional ? $condicional : "";
      $sql = "SELECT dgc.id,dgc.PARROQUIA,dgc.COD_UBCH,dgc.NOMBRE_INSTITUCIONES,dgc.POBLACION_VOTANTE,
      (SELECT SUM(votos) FROM votos_candidatos WHERE centro_votacion_id = dgc.id)'TOTAL_GENERAL',
      (SELECT SUM(votos) FROM votos_candidatos WHERE centro_votacion_id = dgc.id AND candidato_id IN (1,2,3,4))'TOTAL_GOBERNADOR',
      (SELECT SUM(votos) FROM votos_candidatos WHERE centro_votacion_id = dgc.id AND candidato_id IN (23,24,25,26,27))'TOTAL_ALCALDE',
      (SELECT SUM(votos) FROM votos_candidatos WHERE centro_votacion_id = dgc.id AND candidato_id = 1)'HECTOR_RODRIGUEZ',
      (SELECT SUM(votos) FROM votos_candidatos WHERE centro_votacion_id = dgc.id AND candidato_id = 2)'DAVID_UZCATEGUI',
      (SELECT SUM(votos) FROM votos_candidatos WHERE centro_votacion_id = dgc.id AND candidato_id = 3)'OTROS_GOBERNADORES',
      (SELECT SUM(votos) FROM votos_candidatos WHERE centro_votacion_id = dgc.id AND candidato_id = 4)'NULO_GOBERNADOR',
      (SELECT SUM(votos) FROM votos_candidatos WHERE centro_votacion_id = dgc.id AND candidato_id = 23)'JOSE_RANGEL',
      (SELECT SUM(votos) FROM votos_candidatos WHERE centro_votacion_id = dgc.id AND candidato_id = 24)'ANDRES_SCHLOETER',
      (SELECT SUM(votos) FROM votos_candidatos WHERE centro_votacion_id = dgc.id AND candidato_id = 26)'ROSIRIS_TORO',
      (SELECT SUM(votos) FROM votos_candidatos WHERE centro_votacion_id = dgc.id AND candidato_id = 25)'OTROS_ALCALDES',
      (SELECT SUM(votos) FROM votos_candidatos WHERE centro_votacion_id = dgc.id AND candidato_id = 27)'NULO_ALCALDE'
      FROM data_general_cuadernillos dgc 
      $condicional
      ORDER BY PARROQUIA ASC";
      
      
      // $sql = "SELECT 
      //    dgc.id,
      //    dgc.PARROQUIA,
      //    dgc.COD_UBCH,
      //    dgc.NOMBRE_INSTITUCIONES,
      //    dgc.POBLACION_VOTANTE 'poblacion_votante',
      // (SELECT ROUND(SUM(votos) / 2) FROM votos_candidatos WHERE centro_votacion_id = dgc.id)'total_general',
      // (SELECT SUM(votos) FROM votos_candidatos WHERE centro_votacion_id = dgc.id AND candidato_id = 1)'HECTOR_RODRIGUEZ',
      // (SELECT SUM(votos) FROM votos_candidatos WHERE centro_votacion_id = dgc.id AND candidato_id = 2)'DAVID_UZCATEGUI',
      // (SELECT SUM(votos) FROM votos_candidatos WHERE centro_votacion_id = dgc.id AND candidato_id = 3)'OTROS_GOBERNADORES',
      // (SELECT SUM(votos) FROM votos_candidatos WHERE centro_votacion_id = dgc.id AND candidato_id = 23)'JOSE_RANGEL',
      // (SELECT SUM(votos) FROM votos_candidatos WHERE centro_votacion_id = dgc.id AND candidato_id = 24)'ANDRES_SCHLOETER',
      // (SELECT SUM(votos) FROM votos_candidatos WHERE centro_votacion_id = dgc.id AND candidato_id = 26)'ROSIRIS_TORO',
      // (SELECT SUM(votos) FROM votos_candidatos WHERE centro_votacion_id = dgc.id AND candidato_id = 25)'OTROS_ALCALDES'
      // FROM data_general_cuadernillos dgc
      // $condicional";

      return $this->db->query($sql)->result();
   }
}

// "SELECT 
// FROM votos_candidatos vc
// INNER JOIN mesas m ON m.numero_mesa = vc.numero_mesa AND m.centro_votacion_id = vc.centro_votacion_id
// WHERE vc.candidato_id = 26
// "


// "SELECT 
// (SELECT SUM(votos) / 2 FROM votos_candidatos)'total_general',
// FROM votos_candidatos vc
// GROUP BY parroquia"