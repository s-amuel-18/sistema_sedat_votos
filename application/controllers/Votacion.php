<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Votacion extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();


    $this->load->model("votantes_model");
    $this->load->helper(["tools_helper", "url", "form", "create_user_ramdom_helper"]);
    $this->load->library(["form_validation", "session", 'user_agent']);
 
    if (!$this->session->userdata("is_logged")) {
      redirect("/auth/login");
      return;
    }
    $this->ubch = $this->db->select(["id", "NOMBRE_INSTITUCIONES_CON_CODIGO"])->get("data_general_cuadernillos")->result();


  }

  public function index()
  {
    $data["centro_votacion"] = $this->ubch;
    $data["title_page"] = "Seleccionar Centro de Votación";



    $this->load->view("index", $data);
  }


  public function selectCentroVotacion($idCentro)
  {
    if (!$idCentro) {
      redirect("/");
      return;
    }

    $validate = $this->db->select("NOMBRE_INSTITUCIONES_CON_CODIGO")->get_where("data_general_cuadernillos", ["id" => $idCentro])->result()[0];

    if (!$validate) {
      redirect("/");
      return;
    }

    $data["title_page"] = "Centro de votación " . $validate->NOMBRE_INSTITUCIONES_CON_CODIGO;
    $data["idCentro"] = $idCentro;

    $this->load->view("index", $data);
  }


  public function createVotante()
  {
    $request_method = $this->input->server("REQUEST_METHOD");

    if ($request_method != "POST") redirect("/");
    // die();

    $config = [
      [
        "field" =>  "cedula",
        "label" => "cedula de identidad",
        "rules" =>  "required|numeric|min_length[6]|max_length[8]",
        "errors" => [
          "required" => "La %s es requerida",
          "min_length" => "La %s no puede tener una longitud menor a 6 digitos.",
          "max_length" => "La %s no puede tener una longitud mayor a 8 digitos."
        ]
      ],
      [
        "field" =>  "centro_votacion",
        "label" => "Nombre de Centro de Votación",
        "rules" =>  "required|numeric|max_length[3]",
        "errors" => [
          "required" => "El %s es requerida",
        ]
      ],

    ];

    $this->form_validation->set_rules($config);
    $idCentro = $this->input->post("centro_votacion");

    if (!$this->form_validation->run()) {
      $_SESSION["error_message"] = "El campo Cédula es requerido y debe tener una longitud mínima de 6 caracteres y máximo 8";

      redirect("votacion/selectCentroVotacion/" . $idCentro);
      return;
    }

    $consultaCedula = $this->db->where("cedula", set_value("cedula"))->get("votantes")->num_rows();
    if ($consultaCedula != 0) {

      $_SESSION["error_message"] = "ya esta registrada una persona con el numero de cedula " . set_value("cedula");

      redirect("votacion/selectCentroVotacion/" . $idCentro);
      return;
    }

    $validate_length_centro = $this->votantes_model->validateLengthCentroVotacion(set_value("centro_votacion"));



    if (!$validate_length_centro) {
      $_SESSION["error_message"] = "la poblacion total del centro de votacion " .

        redirect("votacion/selectCentroVotacion/" . $idCentro);
      return;
    } else {
      $this->db->insert("votantes", [
        "cedula" => set_value("cedula"),
        "centro_votacion_id" => set_value("centro_votacion"),
        "parroquia" => $this->votantes_model->leerParroquiaConCentroVotacion(set_value("centro_votacion"))->PARROQUIA,
      ]);

      $_SESSION["success_message"] = "registrado con éxito la cédula " . set_value("cedula");

      redirect("votacion/selectCentroVotacion/" . $idCentro);
      return;
    }
  }

  public function status_mesa($id_ubch)
  {
    $ubch = $this->db->get_where("data_general_cuadernillos", ["id" => $id_ubch]);

    var_dump($ubch);
    die();
  }


  public function searchUbch()
  {
    $search = $this->input->get("search");
    $url = $this->input->get("url");

    if (empty($search)) {
      show_404();
      return;
    }

    $this->db->select(["id", "NOMBRE_INSTITUCIONES_CON_CODIGO"])->like("NOMBRE_INSTITUCIONES_CON_CODIGO", $search);
    $query = $this->db->get("data_general_cuadernillos")->result();


    $data["url_go_back"] = $this->agent->referrer();

    if (!$query) {

      $data["error_message"] = "No se encontro resultado para " . $search;
      $this->load->view("searchs/search-links", $data);
      return;
    }

    if (count($query) === 1) {
      redirect($url . $query[0]->id);
      return;
    }


    $data["data_link"] = $query;
    $data["url"] = site_url($url);

    $this->load->view("searchs/search-links", $data);
  }

  // public insertar
}
