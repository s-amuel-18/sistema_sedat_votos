<?php 
class Auth_model extends CI_Model {
  public function __construct() {
    parent::__construct();
    $this->load->database();
  }

  public function login($user, $pass) {
    $consultaUsuario = $this->db->get_where("usuarios", ["username" => $user,"password" => $pass], 1)->result();
    if( !$consultaUsuario ) {
      return false;
    }
    return $consultaUsuario[0];

  }
}