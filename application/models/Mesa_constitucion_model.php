<?php
class Mesa_constitucion_model extends CI_Model { 
   public function __construct() {
      parent::__construct();
      $this->table = "mesas_constitucion";
    }
    
   public function insertar($data) {
    $t = $this->table;
    $sql = "INSERT INTO $t (centro_votacion_id, user_id, numero_mesa, parroquia)
             VALUE (
               $data[centro_votacion_id],
               $data[user_id],
               $data[numero_mesa],
               '$data[parroquia]')";

    return $this->db->query($sql);
   }

   public function validate_mesa($id_ubch, $numero_mesa) {
     $t = $this->table;
     
     $sql = "SELECT * FROM $t WHERE (centro_votacion_id = $id_ubch AND numero_mesa = $numero_mesa)";

     return $this->db->query($sql)->row();
     
   }

}