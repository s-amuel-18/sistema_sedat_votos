<?php
class Mesa_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = "mesas";
    }

    public function count_mesas_por_parroquia($parroquia)
    {
        // SELECT *
        // FROM mesas me
        // LEFT JOIN data_general_cuadernillos dgc
        // ON dgc.COD_UBCH = me.COD_UBCH
        // ORDER BY dgc.id

        $sql = "SELECT SUM(me.MESAS) AS cantidad_mesas
        FROM mesas me
        INNER JOIN data_general_cuadernillos dgc
        ON dgc.COD_UBCH = me.COD_UBCH
        WHERE dgc.PARROQUIA = '{$parroquia}'";

        return $this->db->query($sql)->row("cantidad_mesas");
    }

    public function data_mesas_votos($condicional = null)
    {
        $condicional = $condicional ? $condicional : "";
        $sql = "SELECT mc.numero_mesa, dgc.COD_UBCH, dgc.NOMBRE_INSTITUCIONES_CON_CODIGO AS NOMBRE_INSTITUCIONES, dgc.PARROQUIA, 
        
                (SELECT SUM(votos) FROM votos_candidatos vc INNER JOIN data_general_cuadernillos dgc ON vc.centro_votacion_id = dgc.id  WHERE mc.numero_mesa = vc.numero_mesa AND candidato_id IN (1,2,3,4) AND dgc.id = mc.centro_votacion_id)'TOTAL_GOBERNADOR',
                (SELECT SUM(votos) FROM votos_candidatos vc INNER JOIN data_general_cuadernillos dgc ON vc.centro_votacion_id = dgc.id  WHERE mc.numero_mesa = vc.numero_mesa AND candidato_id IN (23,24,25,26) AND dgc.id = mc.centro_votacion_id)'TOTAL_ALCALDE',
                (SELECT votos FROM votos_candidatos vc INNER JOIN data_general_cuadernillos dgc ON vc.centro_votacion_id = dgc.id WHERE mc.numero_mesa = vc.numero_mesa AND candidato_id = 1 AND dgc.id = mc.centro_votacion_id)'HECTOR_RODRIGUEZ',
                (SELECT votos FROM votos_candidatos vc INNER JOIN data_general_cuadernillos dgc ON vc.centro_votacion_id = dgc.id WHERE mc.numero_mesa = vc.numero_mesa AND candidato_id = 2 AND dgc.id = mc.centro_votacion_id)'DAVID_UZCATEGUI',
                (SELECT votos FROM votos_candidatos vc INNER JOIN data_general_cuadernillos dgc ON vc.centro_votacion_id = dgc.id WHERE mc.numero_mesa = vc.numero_mesa AND candidato_id = 3 AND dgc.id = mc.centro_votacion_id)'OTROS_GOBERNADORES',
                (SELECT votos FROM votos_candidatos vc INNER JOIN data_general_cuadernillos dgc ON vc.centro_votacion_id = dgc.id WHERE mc.numero_mesa = vc.numero_mesa AND candidato_id = 4 AND dgc.id = mc.centro_votacion_id)'NULO_GOBERNADOR',
                (SELECT votos FROM votos_candidatos vc INNER JOIN data_general_cuadernillos dgc ON vc.centro_votacion_id = dgc.id WHERE mc.numero_mesa = vc.numero_mesa AND candidato_id = 23 AND dgc.id = mc.centro_votacion_id)'JOSE_RANGEL',
                (SELECT votos FROM votos_candidatos vc INNER JOIN data_general_cuadernillos dgc ON vc.centro_votacion_id = dgc.id WHERE mc.numero_mesa = vc.numero_mesa AND candidato_id = 24 AND dgc.id = mc.centro_votacion_id)'ANDRES_SCHLOETER',
                (SELECT votos FROM votos_candidatos vc INNER JOIN data_general_cuadernillos dgc ON vc.centro_votacion_id = dgc.id WHERE mc.numero_mesa = vc.numero_mesa AND candidato_id = 26 AND dgc.id = mc.centro_votacion_id)'ROSIRIS_TORO',
                (SELECT votos FROM votos_candidatos vc INNER JOIN data_general_cuadernillos dgc ON vc.centro_votacion_id = dgc.id WHERE mc.numero_mesa = vc.numero_mesa AND candidato_id = 27 AND dgc.id = mc.centro_votacion_id)'NULO_ALCALDE',
                (SELECT votos FROM votos_candidatos vc INNER JOIN data_general_cuadernillos dgc ON vc.centro_votacion_id = dgc.id WHERE mc.numero_mesa = vc.numero_mesa AND candidato_id = 25 AND dgc.id = mc.centro_votacion_id)'OTROS_ALCALDES'
                FROM mesas_cierre mc
                INNER JOIN data_general_cuadernillos dgc ON mc.centro_votacion_id = dgc.id 
                $condicional
                ORDER BY dgc.NOMBRE_INSTITUCIONES_CON_CODIGO, mc.numero_mesa";

        return $this->db->query($sql)->result();
    }
}
