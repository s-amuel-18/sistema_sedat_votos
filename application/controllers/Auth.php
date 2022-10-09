<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();

    // libreria de excel
    $this->load->helper(['url', "form", "tools_helper", "create_user_ramdom_helper"]);
    $this->load->library(["form_validation", "session"]);
    $this->load->model("auth_model");
  }

  public function index()
  {
    $this->load->view("login/login");
    $this->load->helper(["create_user_ramdom_helper"]);
  }

  public function login()
  {
     
    
    $request_method = $this->input->server("REQUEST_METHOD");

    if ($request_method != "POST") redirect("/auth");
    // die();

    $config = [
      [
        "field" =>  "username",
        "label" => "Nombre De Usuario",
        "rules" =>  "required",
        "errors" => [
          "required" => "El campo %s es requerido",
        ]
      ],
      [
        "field" =>  "password",
        "label" => "Contraseña",
        "rules" =>  "required",
        "errors" => [
          "required" => "El campo %s es requerida",
        ]
      ],

    ];

    $this->form_validation->set_rules($config);

    if (!$this->form_validation->run()) {

      // $data["selectVotantesCuadernillos"] = $this->votantes_model->leerCentrosVotacion();

      $this->load->view("login/login");
      return;
    }

    if (!$respuesta = $this->auth_model->login(set_value("username"), md5(set_value("password")))) {
      $data["error_message"] = "El nombre de usuario o la Contraseña son incorrectos";

      $this->load->view("login/login", $data);
      return; 
    }


    $data_user = [
      "id" => $respuesta->id,
      "username" => $respuesta->username,
      "is_logged" => true,
      "rol" => $respuesta->rol
    ];

    
    $this->session->set_userdata($data_user);

    if( $data_user["rol"] === "administrador" ) {
      redirect("/dashboard");
    } else {
      redirect("/");
    }
    
  }

  public function logout()
  {
    $sesion_data = [
      "id", "username", "is_logged"
    ];

    $this->session->unset_userdata($sesion_data);
    $this->session->sess_destroy();
    redirect("/auth/login");
  }
}
