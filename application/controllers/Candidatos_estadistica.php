<?php
class Candidatos_estadistica extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();

    // helpers
    $this->load->helper(
      [
        "url",
        "mesa_estadistica_helper",
        "excel_helper",
        "candidatos_estadisticas_helper",
        "reportes_excel_helper",
        "estadisticas_helper"
      ]
    );

    // librerias
    $this->load->library(["session", "pagination"]);

    // models
    $this->load->model(
      [
        "mesa_instalacion_model",
        "Voto_candidato_model",
        "votantes_model"
      ]
    );

    // validar que este loggeado como administrador
    if (!$this->session->userdata("is_logged") or $this->session->userdata("rol") != "administrador") {
      redirect("/auth/login");
      return;
    }

    $this->ubch = $this->db->select(["id", "NOMBRE_INSTITUCIONES_CON_CODIGO"])->get("data_general_cuadernillos")->result();
    $this->parroquias = $this->votantes_model->parroquias();
  }

  public function index()
  {
    // die();

    $candidatos = $this->Voto_candidato_model->estadistica_candidato();

    $estadisticas_candidatos = candidatos_estadisticas($candidatos);

    $votos_por_cargo = $this->Voto_candidato_model->votos_totales_por_cargo();

    $data["candidatos_estadistica"] = $estadisticas_candidatos;
    $data["votos_por_cargo"] = $votos_por_cargo;
    $data["filtro"] = "El Sistema";
    $data["tituloPrincipal"] = "Estadisticas Candidatos";
    $this->load->view("candidatos_estadistica/index", $data);
  }

  public function filtro_pr($parroquia)
  {
    $sanirize_parroquia = str_replace("-", " ", $parroquia);

    $validate_parroquia = $this->db->get_where("data_general_cuadernillos", ["PARROQUIA" => $sanirize_parroquia])->row();

    $candidatos_por_parroquia = $this->Voto_candidato_model->estadistica_candidato_por_parroquia($sanirize_parroquia);


    if (empty($validate_parroquia)) {
      show_404();
      return;
    }

    $estadisticas_candidatos = !empty($candidatos_por_parroquia)
      ? candidatos_estadisticas($candidatos_por_parroquia)
      : $candidatos_por_parroquia;

    $votos_por_cargo = $this->Voto_candidato_model->votos_totales_por_cargo_y_parroquia($sanirize_parroquia);

    $data["candidatos_estadistica"] = $estadisticas_candidatos;
    $data["votos_por_cargo"] = $votos_por_cargo;
    $data["tituloPrincipal"] = "Estadisticas Candidatos " . $sanirize_parroquia;
    $data["filtro"] = $sanirize_parroquia;
    $data["parroquia"] = $sanirize_parroquia;
    $this->load->view("candidatos_estadistica/index", $data);
  }


  public function filtro_cv($id_centro)
  {
    $ubch = $this->db->get_where("data_general_cuadernillos", ["id" => $id_centro])->row();

    $candidatos_por_ubch = $this->Voto_candidato_model->estadistica_candidato_por_ubch($id_centro);


    if (empty($ubch)) {
      show_404();
      return;
    }

    $estadisticas_candidatos = !empty($candidatos_por_ubch)
      ? candidatos_estadisticas($candidatos_por_ubch)
      : $candidatos_por_ubch;

    $votos_por_cargo = $this->Voto_candidato_model->votos_totales_por_cargo_y_ubch($id_centro);

    $data["candidatos_estadistica"] = $estadisticas_candidatos;
    $data["votos_por_cargo"] = $votos_por_cargo;
    $data["tituloPrincipal"] = "Estadisticas Candidatos " . $ubch->NOMBRE_INSTITUCIONES_CON_CODIGO;
    $data["filtro"] = $ubch->NOMBRE_INSTITUCIONES_CON_CODIGO;
    $data["id_ubch"] = $ubch->id;
    $this->load->view("candidatos_estadistica/index", $data);
  }

  public function candidatos_all()
  {
    $estadisticas_candidatos = $this->Voto_candidato_model->reporte_votos_ubch_candidato_excel();
    

    $votos_por_cargo = $this->Voto_candidato_model->votos_totales_por_cargo();

    $data["candidatos_estadistica"] = $estadisticas_candidatos;
    $data["votos_por_cargo"] = $votos_por_cargo;
    $data["filtro"] = "El Sistema";
    $data["tituloPrincipal"] = "Estadisticas Candidatos Reporte";
    $this->load->view("candidatos_estadistica/reporte_ubch", $data);
  }

  public function candidatos_filtro_pr($parroquia)
  {
    $sanirize_parroquia = str_replace("-", " ", $parroquia);

    $validate_parroquia = $this->db->get_where("data_general_cuadernillos", ["PARROQUIA" => $sanirize_parroquia])->row();


    if (empty($validate_parroquia)) {
      show_404();
      return;
    }


    $estadisticas_candidatos = $this->Voto_candidato_model->reporte_votos_ubch_candidato_excel("WHERE dgc.PARROQUIA = '$sanirize_parroquia'");

    $votos_por_cargo = $this->Voto_candidato_model->votos_totales_por_cargo_y_parroquia($sanirize_parroquia);

    $data["candidatos_estadistica"] = $estadisticas_candidatos;
    $data["votos_por_cargo"] = $votos_por_cargo;
    $data["tituloPrincipal"] = "Estadisticas Candidatos " . $sanirize_parroquia;
    $data["filtro"] = $sanirize_parroquia;
    $data["parroquia"] = $sanirize_parroquia;
    $this->load->view("candidatos_estadistica/reporte_ubch", $data);
  }


  public function candidatos_filtro_cv($id_centro)
  {
    $ubch = $this->db->get_where("data_general_cuadernillos", ["id" => $id_centro])->row();



    if (empty($ubch)) {
      show_404();
      return;
    }

    $estadisticas_candidatos = $this->Voto_candidato_model->reporte_votos_ubch_candidato_excel("WHERE dgc.id = $id_centro");
    
    $votos_por_cargo = $this->Voto_candidato_model->votos_totales_por_cargo_y_ubch($id_centro);

    $data["candidatos_estadistica"] = $estadisticas_candidatos;
    $data["votos_por_cargo"] = $votos_por_cargo;
    $data["tituloPrincipal"] = "Estadisticas Candidatos Reporte" . $ubch->NOMBRE_INSTITUCIONES_CON_CODIGO;
    $data["filtro"] = $ubch->NOMBRE_INSTITUCIONES_CON_CODIGO;
    $data["id_ubch"] = $ubch->id;
    $this->load->view("candidatos_estadistica/reporte_ubch", $data);
  }

  public function por_parroquia()
  {

    $estadisticas_candidatos = $this->Voto_candidato_model->reporte_votos_por_par_candidato_excel();


    $votos_por_cargo = $this->Voto_candidato_model->votos_totales_por_cargo();

    $data["candidatos_estadistica"] = $estadisticas_candidatos;
    $data["votos_por_cargo"] = $votos_por_cargo;
    $data["filtro"] = "El Sistema";
    $data["tituloPrincipal"] = "Estadisticas Candidatos por parroquia";
    $this->load->view("candidatos_estadistica/reporte_parroquia", $data);
  }

  public function reporte_candidatos()
  {
    $candidatos = $this->Voto_candidato_model->estadistica_candidato();
    $estadisticas_candidatos = candidatos_estadisticas($candidatos);

    candidatos($estadisticas_candidatos);
  }


  public function reporte_candidatos_parroquia($parroquia)
  {
    $sanirize_parroquia = str_replace("-", " ", $parroquia);

    $candidatos_por_parroquia = $this->Voto_candidato_model->estadistica_candidato_por_parroquia($sanirize_parroquia);



    $estadisticas_candidatos = !empty($candidatos_por_parroquia)
      ? candidatos_estadisticas($candidatos_por_parroquia)
      : $candidatos_por_parroquia;
    candidatos($estadisticas_candidatos);
  }

  public function reporte_candidatos_ubch($id_ubch)
  {

    $candidatos_por_ubch = $this->Voto_candidato_model->estadistica_candidato_por_ubch($id_ubch);



    $estadisticas_candidatos = !empty($candidatos_por_ubch)
      ? candidatos_estadisticas($candidatos_por_ubch)
      : $candidatos_por_ubch;
    candidatos($estadisticas_candidatos);
  }

  public function reporte_candidatos_por_parroquia()
  {

    $estadisticas_candidatos = $this->Voto_candidato_model->reporte_votos_por_par_candidato_excel();


    $file = "reporte.xls";
    $test = $this->load->view("candidatos_estadistica/tablas/parroquia", [
      "candidatos_estadistica" => $estadisticas_candidatos
    ], true);


    header("Content-type: application/vnd.ms-Excel");
    header("Content-Disposition: attachment; filename=$file");
    echo $test;
  }

  public function reporte_candidatos_por_ubch()
  {
    $parroquia = $this->input->get("parroquia");
    $ubch = $this->input->get("id_ubch");
    // "http://[::1]/estadistica-voto/index.php/candidatos_estadistica/reporte_candidatos_por_ubch?parroquia=PETARE-NORTE&id_ubch=184"
    // if( $parroquia ) {}
    $sanirize_parroquia = str_replace("-", " ", $parroquia);

    if ($ubch) {
      $condicional = "WHERE dgc.id = $ubch";
    } else if ($parroquia) {
      $condicional = "WHERE dgc.PARROQUIA = '{$sanirize_parroquia}'";
    } else {
      $condicional = null;
    }
    $estadisticas_candidatos = $this->Voto_candidato_model->reporte_votos_ubch_candidato_excel($condicional);


    // var_dump($estadisticas_candidatos);die();

    $file = "reporte.xls";
    $test = $this->load->view("candidatos_estadistica/tablas/ubch", [
      "candidatos_estadistica" => $estadisticas_candidatos
    ], true);
    // echo $test;die();

    header("Content-type: application/vnd.ms-Excel");
    header("Content-Disposition: attachment; filename=$file");
    echo $test;
  }
  
}
