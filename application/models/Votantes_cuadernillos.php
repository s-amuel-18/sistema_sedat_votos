<?php
class Votantes_cuadernillos extends CI_Model { 
   public function __construct() {
      parent::__construct();
   }

   public function leerVotantesCuadernillos() {
     $sql = "SELECT * FROM data_general_cuadernillos";

     return $this->db->query($sql)->result();
     
   }

   public function leerCentrosVotacion() {
     $sql = "SELECT NOMBRE_INSTITUCIONES, NOMBRE_INSTITUCIONES_CON_CODIGO FROM data_general_cuadernillos";

     return $this->db->query($sql)->result();
     
   }

   public function leerEstadisticas() {
     $sql = "SELECT * FROM estadistica_votantes";

     return $this->db->query($sql)->result();
     
   }

   public function selecNombreInstituciones() {
     $sql = "SELECT NOMBRE_INSTITUCIONES FROM  votantes_cuadernillos";
     
     return $this->db->query($sql)->result();
    }
    
    
    public function deleteAllEstadisticas() {
      $sql = "DELETE FROM  estadistica_votantes";
      return $this->db->query($sql);
    }
    
    public function parroquias() {
      $sql = "SELECT PARROQUIA FROM  data_general_cuadernillos";
      $selectParroquias = $this->db->query($sql)->result();

      $parroquias = [];

      

      foreach( $selectParroquias as $value ) {
        if( !in_array( $value->PARROQUIA, $parroquias ) ) {
          array_push( $parroquias, $value->PARROQUIA );
        }
      }

      return $parroquias;
    }
    
    public function filtrarVotosPorParroquia($parroquia) {
      $sql = "SELECT * FROM estadistica_votantes WHERE PARROQUIA = '$parroquia'";
      
      return $this->db->query($sql)->result();

   }

}