<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();

    $this->load->helper(['url', "form", "tools_helper", "reportes_excel_helper"]);
    $this->load->library(["session", "pagination", "form_validation"]);
    $this->load->model("votantes_model");
    $this->ubch = $this->db->select(["id", "NOMBRE_INSTITUCIONES_CON_CODIGO"])->get("data_general_cuadernillos")->result();
    $this->parroquias = $this->votantes_model->parroquias();



    // var_dump($this->session->userdata("rol") != "administrador");die();
    if (!$this->session->userdata("is_logged") or $this->session->userdata("rol") != "administrador") {
      redirect("/auth/login");
      return;
    }



    
  }


  public function index($offset = 0)
  {

    $select_cuadernillos = $this->db->get("data_general_cuadernillos");

    $num_data = $select_cuadernillos->num_rows();





    $config["base_url"] = site_url("dashboard/index");
    $config["por_page"] = 10;
    $config["total_rows"] = $num_data;

    // Bootstrap 4, work very fine.
    $config['full_tag_open'] = '<ul class="pagination">';
    $config['full_tag_close'] = '</ul>';
    $config['attributes'] = ['class' => 'page-link'];
    $config['first_link'] = false;
    $config['last_link'] = false;
    $config['first_tag_open'] = '<li class="page-item">';
    $config['first_tag_close'] = '</li>';
    $config['prev_link'] = '&laquo';
    $config['prev_tag_open'] = '<li class="page-item">';
    $config['prev_tag_close'] = '</li>';
    $config['next_link'] = '&raquo';
    $config['next_tag_open'] = '<li class="page-item">';
    $config['next_tag_close'] = '</li>';
    $config['last_tag_open'] = '<li class="page-item">';
    $config['last_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
    $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';
    $this->pagination->initialize($config);

    $dataEstadisticas = $this->votantes_model->estadisticas_gnerales($config["por_page"], $offset);

    $count_votantes_totales_registrados = $this->votantes_model->sumVotanteRegistrados()->total;
    $count_votantes_confirmados = $this->db->get("votantes")->num_rows();


    $data["dataEstadisticas"] = $dataEstadisticas;
    // $data = createDataDashboard($page, $this);
    $data["tituloPrincipal"] = "Estadisticas generales";
    $data["countVotantesRegistrados"] = number_format($count_votantes_totales_registrados, 0);
    $data["countVotantesConfirmados"] = number_format($count_votantes_confirmados, 0);

    $porcentajeSumaVotandesConf = ($count_votantes_confirmados / $count_votantes_totales_registrados) * 100;


    $data["porcentajeSumaVotandesConf"] = number_format($porcentajeSumaVotandesConf, 2);
    $this->load->view("dashboard/dashboard", $data);
  }

  public function filterParroquia($par)
  {


    $parroquia = str_replace("-", " ", $par);

    $count_votantes_totales_registrados = $this->votantes_model->sumVotanteRegistrados($parroquia)->total;
    $count_votantes_confirmados = $this->db->get_where("votantes", ["parroquia" => $parroquia])->num_rows();



    $dataEstadisticas = $this->votantes_model->estadisticas_por_parroquia($parroquia);
    $data["parroquiaFilter"] = $parroquia;
    $data["countVotantesRegistrados"] = number_format($count_votantes_totales_registrados, 0);
    $data["countVotantesConfirmados"] = number_format($count_votantes_confirmados, 0);
    $data["porcentajeSumaVotandesConf"] = number_format($count_votantes_confirmados / $count_votantes_totales_registrados * 100, 2);
    $data["tituloPrincipal"] = "Parroquia " . $parroquia;
    $data["dataEstadisticas"] = $dataEstadisticas;

    $this->load->view("dashboard/dashboard", $data);
  }


  public function reporteParroquias()
  {

    $parroquias_array = $this->votantes_model->parroquias();

    foreach ($parroquias_array as $parroquia) :
      // $prueba = $this->votantes_model->pr($parroquia);
      $v_registrados = $this->votantes_model->sumVotanteRegistrados($parroquia)->total;
      $data_parroquias[$parroquia]["VOTANTES_REGISTRADOS"] = number_format($v_registrados, 0);

      $v_confirmados = $this->db->get_where("votantes", ["parroquia" => $parroquia])->num_rows();
      $data_parroquias[$parroquia]["VOTANTES_CONFIRMADOS"] = number_format($v_confirmados, 0);

      $porcentaje_v_conf = $v_confirmados * 100 / $v_registrados;

      $data_parroquias[$parroquia]["PORCENTAJE_VOTANTES_CONFIRMADOS"] = number_format($porcentaje_v_conf, 2);

    endforeach;

    $data["data_parroquias"] = $data_parroquias;
    $data["parroquias"] = $this->votantes_model->parroquias();
    $data["tituloPrincipal"] = "Reporte Parroquias";

    $this->load->view("dashboard/parroquias", $data);
  }

  public function filterVotantes($par, $offset = 0)
  {


    $parroquia = str_replace("-", " ", $par);
    if ($parroquia == "todos") {
      $num_data = $this->db->get("votantes")->num_rows();
    } else {
      $num_data = $this->db->get_where("votantes", ["parroquia" => $parroquia])->num_rows();
    }


    $config["base_url"] = site_url("dashboard/filterVotantes/" . $par);
    $config["por_page"] = 20;
    $config["total_rows"] = $num_data;

    // Bootstrap 4, work very fine.
    $config['full_tag_open'] = '<ul class="pagination">';
    $config['full_tag_close'] = '</ul>';
    $config['attributes'] = ['class' => 'page-link'];
    $config['first_link'] = false;
    $config['last_link'] = false;
    $config['first_tag_open'] = '<li class="page-item">';
    $config['first_tag_close'] = '</li>';
    $config['prev_link'] = '&laquo';
    $config['prev_tag_open'] = '<li class="page-item">';
    $config['prev_tag_close'] = '</li>';
    $config['next_link'] = '&raquo';
    $config['next_tag_open'] = '<li class="page-item">';
    $config['next_tag_close'] = '</li>';
    $config['last_tag_open'] = '<li class="page-item">';
    $config['last_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
    $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';
    $this->pagination->initialize($config);


    if ($parroquia == "todos") {
      $select_data_votantes  = $this->db->get("votantes", $config["por_page"], $offset)->result();
    } else {
      $select_data_votantes = $this->db->get_where("votantes", ["parroquia" => $parroquia], $config["por_page"], $offset)->result();
    }

    $data["votantes"] = $select_data_votantes;



    foreach ($select_data_votantes as $value) {
      $value->centro_votacion_id = $this->votantes_model->leerConCentroVotacionConParroquia($value->centro_votacion_id)->NOMBRE_INSTITUCIONES_CON_CODIGO;
    }


    $data["tituloPrincipal"] = "votantes de la parroquia " . $parroquia;

    $this->load->view("dashboard/vontantes_view", $data);
  }


  public function searchVotante()
  {


    $search = $this->input->post("search");



    $config = [
      [
        "field" =>  "search",
        "label" => "busqueda",
        "rules" =>  "required|min_length[7]",
        "errors" => [
          "required" => "El campo %s es requerido",
        ]
      ]
    ];

    $this->form_validation->set_rules($config);

    if (!$this->form_validation->run()) {

      redirect("dashboard/filterVotantes/todos");
      return;
    }

    $data["votantes"] = $this->votantes_model->searchVotante($search);



    foreach ($data["votantes"] as $value) {
      $value->centro_votacion_id = $this->votantes_model->leerConCentroVotacionConParroquia($value->centro_votacion_id)->NOMBRE_INSTITUCIONES_CON_CODIGO;
    }

    $data["tituloPrincipal"] = "Resultado de busqueda para C.I " . $search;

    $this->load->view("dashboard/vontantes_view", $data);
  }


  public function filtrar_ubch($idCentro)
  {
    $dataEstadisticas = $this->votantes_model->estadisticas_por_ubch($idCentro);

    $data["dataEstadisticas"] = $dataEstadisticas;

    // var_dump($dataEstadisticas);die();

    $dataEstadisticas = $dataEstadisticas[0];

    if (!$dataEstadisticas) {
      show_404();
      return;
    }

    $countVotantesRegistrados = $dataEstadisticas->POBLACION_VOTANTE;
    $countVotantesConfirmados = $dataEstadisticas->total_general;

    $data["countVotantesRegistrados"] = $countVotantesRegistrados;
    $data["countVotantesConfirmados"] = $countVotantesConfirmados;


    $data["porcentajeSumaVotandesConf"] = $dataEstadisticas->porcentaje;
    $data["tituloPrincipal"] =  $dataEstadisticas->NOMBRE_INSTITUCIONES_CON_CODIGO;

    $this->load->view("dashboard/dashboard", $data);
  }

  public function reporte_votantes() {

    
    $dataEstadisticas = $this->votantes_model->estadisticas_gnerales(200, 0);
    
    votantes($dataEstadisticas);
  }

  
  public function reporte_votantes_pr($par) {
    $parroquia = str_replace("-", " ", $par);

    $dataEstadisticas = $this->votantes_model->estadisticas_por_parroquia($parroquia);
    
    
    votantes($dataEstadisticas);
  }
  
  public function reporte_votantes_parroquia() {
    $parroquias_array = $this->votantes_model->parroquias();

    foreach ($parroquias_array as $parroquia) :
      // $prueba = $this->votantes_model->pr($parroquia);
      $v_registrados = $this->votantes_model->sumVotanteRegistrados($parroquia)->total;
      $data_parroquias[$parroquia]["VOTANTES_REGISTRADOS"] = number_format($v_registrados, 0);

      $v_confirmados = $this->db->get_where("votantes", ["parroquia" => $parroquia])->num_rows();
      $data_parroquias[$parroquia]["VOTANTES_CONFIRMADOS"] = number_format($v_confirmados, 0);

      $porcentaje_v_conf = $v_confirmados * 100 / $v_registrados;

      $data_parroquias[$parroquia]["PORCENTAJE_VOTANTES_CONFIRMADOS"] = number_format($porcentaje_v_conf, 2);

    endforeach;
    
    $dataEstadisticas = $data_parroquias;
    
    votantes_parroquia($dataEstadisticas);
  }
}
