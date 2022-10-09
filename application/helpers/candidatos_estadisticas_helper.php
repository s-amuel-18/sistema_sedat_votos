<?php 

function candidatos_estadisticas($data_candidatos) {
    
    foreach ($data_candidatos as $i => $value) {
        $porcentaje_voto = ($value->total_general > 0) 
            ? $value->cant_votos / $value->total_general * 100 
            : 0;
        $value->porcentaje = $porcentaje_voto;
    }

    return $data_candidatos;
    
}