<?php 
function media_aritmetica($total, $data_porcentaje) {
    
    return $total == 0 ? 0 :  ($data_porcentaje * 100) / $total;
} 