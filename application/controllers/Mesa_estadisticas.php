<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mesa_estadisticas extends CI_Controller
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
        "estadisticas_helper",
        "reportes_excel_helper"
      ]
    );

    // librerias
    $this->load->library(["session", "pagination"]);

    // models
    $this->load->model(["mesa_instalacion_model", "votantes_model", "mesa_model"]);

    // validar que este loggeado como administrador
    if (!$this->session->userdata("is_logged") or $this->session->userdata("rol") != "administrador") {
      redirect("/auth/login");
      return;
    }

    $this->ubch = $this->db->select(["id", "NOMBRE_INSTITUCIONES_CON_CODIGO"])->get("data_general_cuadernillos")->result();
    $this->parroquias = $this->votantes_model->parroquias();

    $this->data_post = (object)json_decode(file_get_contents('php://input'), true);
  }  

  public function index($offset = 0)
  {
    $count_data = $this->db->select([
      "id",
      "COD_UBCH",
      "NOMBRE_INSTITUCIONES_CON_CODIGO",
      "PARROQUIA"
    ]);

    $count_data = $this->db->get("data_general_cuadernillos")->num_rows();
    $sise_por_page = 10;

    $this->pagination_config(
      site_url("mesa_estadisticas/index"),
      $sise_por_page,
      $count_data
    );


    $data_estadistica = $this->mesa_instalacion_model->estadistica_mesas(200, 0);
    
    $total_mesas = $this->db->select_sum("MESAS")->get("mesas")->row("MESAS");
    
    
    $grafica["total_mesas"]["titulo"] = "Mesas Totales";
    $grafica["total_mesas"]["total"] = $total_mesas;
    $grafica["total_mesas"]["porcentaje"] = media_aritmetica($total_mesas, $total_mesas);
    $grafica["total_mesas"]["color"] = "warning";

    $grafica["instaladas"]["titulo"] = "Mesas instaladas";
    $t_instaladas = $this->db->get_where("mesas_instalacion", ["averiado" => 0])->num_rows();
    $grafica["instaladas"]["total"] = $t_instaladas;
    $grafica["instaladas"]["porcentaje"] = media_aritmetica($total_mesas, $t_instaladas);
    $grafica["instaladas"]["color"] = "primary";

    
    $grafica["constituidas"]["titulo"] = "Mesas constituidas";
    $t_constituidas = $this->db->get("mesas_constitucion")->num_rows();
    $grafica["constituidas"]["total"] = $t_constituidas;
    $grafica["constituidas"]["porcentaje"] = media_aritmetica($total_mesas, $t_constituidas);
    $grafica["constituidas"]["color"] = "success";
    
    $grafica["cerradas"]["titulo"] = "Mesas Cerradas";
    $t_cerradas = $this->db->get("mesas_cierre")->num_rows();
    $grafica["cerradas"]["total"] = $t_cerradas;
    $grafica["cerradas"]["porcentaje"] = media_aritmetica($total_mesas, $t_cerradas);
    $grafica["cerradas"]["color"] = "danger";

    $cotillones_entregados = $this->db->get_where("entrega_cotillon", ["entregado" => 1])->num_rows();

    $data["cotillones_entregados"] = "Materiales electorales entregados $cotillones_entregados de $count_data"; 
    $data["grafica_estadisticas"] = $grafica;
    
    $data["titulo_grafica"] = "Estadisticas Genetales";
    $data["table_estadisticas"] = $data_estadistica;
    $data["tituloPrincipal"] = "Estadisticas Mesas";

    $this->load->view("mesa_estadisticas/index", $data);
  }



  public function filtro_cv($id_centro)
  {

    $ubch = $this->db->select([
      "id",
      "COD_UBCH",
      "NOMBRE_INSTITUCIONES_CON_CODIGO",
      "PARROQUIA"
    ]);

    $ubch = $this->db->get_where("data_general_cuadernillos", ["id" => $id_centro])->result();

    if( empty($ubch) ) {
      show_404();
      return;
    }
    
    $data_estadistica = $this->mesa_instalacion_model->estadistica_mesas(null, null, "WHERE dgc.id = {$ubch[0]->id}");
    // var_dump($data_estadistica);die();

    $total_mesas = $this->db->select_sum("MESAS")->get_where("mesas", ["COD_UBCH" => $ubch[0]->COD_UBCH])->row("MESAS");
    // var_dump($total_mesas);die();
    
    
    $grafica["total_mesas"]["titulo"] = "Mesas Totales";
    $grafica["total_mesas"]["total"] = $total_mesas;
    $grafica["total_mesas"]["porcentaje"] = media_aritmetica($total_mesas, $total_mesas);
    $grafica["total_mesas"]["color"] = "warning";
    
    $grafica["instaladas"]["titulo"] = "Mesas instaladas";
    $t_instaladas = $this->db->get_where("mesas_instalacion", ["centro_votacion_id" => $id_centro, "averiado" => 0])->num_rows();
    $grafica["instaladas"]["total"] = $t_instaladas;
    $grafica["instaladas"]["porcentaje"] = media_aritmetica($total_mesas, $t_instaladas);
    $grafica["instaladas"]["color"] = "primary";
    
    
    $grafica["constituidas"]["titulo"] = "Mesas constituidas";
    $t_constituidas = $this->db->get_where("mesas_constitucion", ["centro_votacion_id" => $id_centro])->num_rows();
    $grafica["constituidas"]["total"] = $t_constituidas;
    $grafica["constituidas"]["porcentaje"] = media_aritmetica($total_mesas, $t_constituidas);
    $grafica["constituidas"]["color"] = "success";
    
    $grafica["cerradas"]["titulo"] = "Mesas Cerradas";
    $t_cerradas = $this->db->get_where("mesas_cierre", ["centro_votacion_id" => $id_centro])->num_rows();
    $grafica["cerradas"]["total"] = $t_cerradas;
    $grafica["cerradas"]["porcentaje"] = media_aritmetica($total_mesas, $t_cerradas);
    $grafica["cerradas"]["color"] = "danger";
    
    $cotillones_entregados = $this->db->get_where("entrega_cotillon", ["entregado" => 1, "centro_votacion_id" => $id_centro])->num_rows();
    
    $data["cotillones_entregados"] = "Materiales electorales entregados $cotillones_entregados de 1"; 
    $data["grafica_estadisticas"] = $grafica;
    
    $mesas_cierre_data = $this->db->get_where(
      "mesas_cierre",
      [
        "centro_votacion_id" => $id_centro
      ]
    )->result(); 

    $data["mesas_cerradas"] = $mesas_cierre_data;
    $data["titulo_grafica"] = "Estadisticas Genetales - ". $ubch[0]->NOMBRE_INSTITUCIONES_CON_CODIGO;
    $data["table_estadisticas"] = $data_estadistica;
    
    $data["tituloPrincipal"] = $ubch[0]->NOMBRE_INSTITUCIONES_CON_CODIGO;
    // var_dump($ubch);die();
    $data["id_ubch"] = $ubch[0]->id;
    // var_dump($grafica);die();
    $this->load->view("mesa_estadisticas/index", $data);
  }



  public function filtro_pr($parroquia) {
    $sanirize_parroquia = str_replace("-", " ", $parroquia);
    
    $data_por_parroquia = $this->db->get_where("data_general_cuadernillos", ["PARROQUIA" => $sanirize_parroquia])->num_rows();
    
    if( empty($data_por_parroquia) ) {
      show_404();
      return;
    }
    $validate_ubch = $this->db->get_where("data_general_cuadernillos", ["PARROQUIA" => $sanirize_parroquia])->row();

    $data_estadistica = $this->mesa_instalacion_model->estadistica_mesas(null, null, "WHERE dgc.parroquia = '$sanirize_parroquia'");

    $total_mesas = $this->mesa_model->count_mesas_por_parroquia($sanirize_parroquia);


    
    
    $grafica["total_mesas"]["titulo"] = "Mesas Totales";
    $grafica["total_mesas"]["total"] = $total_mesas;
    $grafica["total_mesas"]["porcentaje"] = media_aritmetica($total_mesas, $total_mesas);
    $grafica["total_mesas"]["color"] = "warning";
    
    $grafica["instaladas"]["titulo"] = "Mesas instaladas";
    $t_instaladas = $this->db->get_where("mesas_instalacion", ["parroquia" => $sanirize_parroquia, "averiado" => 0])->num_rows();
    $grafica["instaladas"]["total"] = $t_instaladas;
    $grafica["instaladas"]["porcentaje"] = media_aritmetica($total_mesas, $t_instaladas);
    $grafica["instaladas"]["color"] = "primary";
    
    
    $grafica["constituidas"]["titulo"] = "Mesas constituidas";
    $t_constituidas = $this->db->get_where("mesas_constitucion", ["parroquia" => $sanirize_parroquia])->num_rows();
    $grafica["constituidas"]["total"] = $t_constituidas;
    $grafica["constituidas"]["porcentaje"] = media_aritmetica($total_mesas, $t_constituidas);
    $grafica["constituidas"]["color"] = "success";
    
    $grafica["cerradas"]["titulo"] = "Mesas Cerradas";
    $t_cerradas = $this->db->get_where("mesas_cierre", ["parroquia" => $sanirize_parroquia])->num_rows();
    $grafica["cerradas"]["total"] = $t_cerradas;
    $grafica["cerradas"]["porcentaje"] = media_aritmetica($total_mesas, $t_cerradas);
    $grafica["cerradas"]["color"] = "danger";
    
    $cotillones_entregados = $this->db->get_where("entrega_cotillon", ["entregado" => 1, "parroquia" => $sanirize_parroquia])->num_rows();
    
    $data["cotillones_entregados"] = "Materiales electorales entregados $cotillones_entregados de $data_por_parroquia"; 
    $data["grafica_estadisticas"] = $grafica;
    
    $data["titulo_grafica"] = "Estadisticas Genetales - ". $sanirize_parroquia;
 
    $data["table_estadisticas"] = $data_estadistica;
    $data["tituloPrincipal"] = $sanirize_parroquia;
    $data["parroquia"] = $sanirize_parroquia; 

    $this->load->view("mesa_estadisticas/index", $data);
  }



  
  public function reporte_parroquia() {
    $data_reporte = reporte_parroquia_data($this);

    $total_mesas = $this->db->select_sum("MESAS")->get("mesas")->row("MESAS");
    
    
    $grafica["total_mesas"]["titulo"] = "Mesas Totales";
    $grafica["total_mesas"]["total"] = $total_mesas;
    $grafica["total_mesas"]["porcentaje"] = media_aritmetica($total_mesas, $total_mesas);
    $grafica["total_mesas"]["color"] = "warning";

    $grafica["instaladas"]["titulo"] = "Mesas instaladas";
    $t_instaladas = $this->db->get_where("mesas_instalacion", ["averiado" => 0])->num_rows();
    $grafica["instaladas"]["total"] = $t_instaladas;
    $grafica["instaladas"]["porcentaje"] = media_aritmetica($total_mesas, $t_instaladas);
    $grafica["instaladas"]["color"] = "primary";

    
    $grafica["constituidas"]["titulo"] = "Mesas constituidas";
    $t_constituidas = $this->db->get("mesas_constitucion")->num_rows();
    $grafica["constituidas"]["total"] = $t_constituidas;
    $grafica["constituidas"]["porcentaje"] = media_aritmetica($total_mesas, $t_constituidas);
    $grafica["constituidas"]["color"] = "success";
    
    $grafica["cerradas"]["titulo"] = "Mesas Cerradas";
    $t_cerradas = $this->db->get("mesas_cierre")->num_rows();
    $grafica["cerradas"]["total"] = $t_cerradas;
    $grafica["cerradas"]["porcentaje"] = media_aritmetica($total_mesas, $t_cerradas);
    $grafica["cerradas"]["color"] = "danger";

    $cotillones_entregados = $this->db->get_where("entrega_cotillon", ["entregado" => 1])->num_rows();

    $count_data = $this->db->get("data_general_cuadernillos")->num_rows();

    $data["cotillones_entregados"] = "Materiales electorales entregados $cotillones_entregados de $count_data"; 
    $data["grafica_estadisticas"] = $grafica;
   
    $data["titulo_grafica"] = "Estadisticas Genetales";
    
    $data["table_estadisticas"] = $data_reporte;
    $data["tituloPrincipal"] = "Reporte de mesas por parroquia";

    $this->load->view("mesa_estadisticas/reporte_parroquias", $data);
  }
  
  public function info_mesas_por_ubch($id_ubch)
  {
    $cotillon = $this->db->select(["entregado", "observacion", "fecha_creacion"])->get_where("entrega_cotillon", ["centro_votacion_id" => $id_ubch]);
    $cotillon = $cotillon->row();
    $data_get["cotillon"] = $cotillon;

    $data_get["centro_votacion_id"] = $id_ubch;

    $mesas_averiadas = $this->db->get_where("mesas_instalacion", ["centro_votacion_id" => $id_ubch, "averiado" => 1])->result();
    $data_get["mesas_averiadas"] = $mesas_averiadas;
    
    $mesas_instaladas = $this->db->get_where("mesas_instalacion", ["centro_votacion_id" => $id_ubch, "averiado" => 0])->result();
    $data_get["mesas_instaladas"] = $mesas_instaladas ;

    $mesas_constituidas = $this->db->get_where("mesas_constitucion", ["centro_votacion_id" => $id_ubch])->result();
    $data_get["mesas_constituidas"] = $mesas_constituidas ;

    $mesas_cerradas = $this->db->get_where("mesas_cierre", ["centro_votacion_id" => $id_ubch])->result();
    $data_get["mesas_cerradas"] = $mesas_cerradas ;
    echo json_encode($data_get);
    
  }

  public function reporte_mesas_estadisticas()
  {
    $data_estadistica = $this->mesa_instalacion_model->estadistica_mesas(198, 0);    

    mesas_estadisticas($data_estadistica);
  }

  public function reporte_mesas_estadisticas_filtro_pr($parroquia)
  {
    $sanirize_parroquia = str_replace("-", " ", $parroquia);

    $data_estadistica = $this->mesa_instalacion_model->estadistica_mesas(null, null, "WHERE dgc.parroquia = '$sanirize_parroquia'");    

    mesas_estadisticas($data_estadistica);
  }



  public function reporte_mesas_estadisticas_parroquias()
  {
    $data_estadistica = reporte_parroquia_data($this);    

    mesas_estadisticas_parroquias($data_estadistica);
    
  }

  public function reporte_votos_candidatos_por_mesa()
  {
    $parroquia = $this->input->get("parroquia");
    $ubch = $this->input->get("id_ubch");

    
    if ($ubch) {
      $condicional = "WHERE dgc.id = $ubch";
    } else if ($parroquia) {
      $sanirize_parroquia = str_replace("-", " ", $parroquia);
      $condicional = "WHERE dgc.PARROQUIA = '{$sanirize_parroquia}'";
    } else {
      $condicional = null;
    }
    $mesas_estadisticas = $this->mesa_model->data_mesas_votos($condicional);

    // var_dump($mesas_estadisticas);die();
    // var_dump($estadisticas_candidatos);die();

    $file = "reporte.xls";
    // $this->load->view("template/head.php");
    $test = $this->load->view("mesa_estadisticas/tablas/ubch", [
      "candidatos_estadistica" => $mesas_estadisticas
    ], true);
    
    // $this->load->view("template/footer.php");return;
    // echo $test;die();

    header("Content-type: application/vnd.ms-Excel");
    header("Content-Disposition: attachment; filename=$file");
    echo $test;
  }


  private function pagination_config($url, $size, $num_data)
  {
    $config["base_url"] = $url;
    $config["por_page"] = $size;
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
  }
}



