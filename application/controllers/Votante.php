<?php

class Votante extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->helper(['url', "form", "tools_helper", "pagination_helper"]);
        $this->load->library(["session", "pagination", "form_validation", "user_agent"]);
        $this->load->model("votantes_model");
        $this->ubch = $this->db->select(["id", "NOMBRE_INSTITUCIONES_CON_CODIGO"])->get("data_general_cuadernillos")->result();
        $this->parroquias = $this->votantes_model->parroquias();



        // var_dump($this->session->userdata("rol") != "administrador");die();
        if (!$this->session->userdata("is_logged") or $this->session->userdata("rol") != "administrador") {
            redirect("/auth/login");
            return;
        }
    }

    public function index($offset = 0) {
        $limit_data = 20;
        $num_votantes = $this->db->get("votantes")->num_rows();
        $config = pagination_config(
            site_url("votantes/index"),
            $limit_data,
            $num_votantes
        );

        $this->pagination->initialize($config);

        $dataEstadisticas = $this->votantes_model->votantes($config["por_page"], $offset);
        
        $data["tituloPrincipal"] = "Votantes";
        $data["votantes"] = $dataEstadisticas;

        // var_dump($data["votantes"]);die();
        $this->load->view("votante/index", $data);
    }

    public function filter_ubch($id_ubch, $offset = 0) {

        $exist_ubch = $this->db->get_where("data_general_cuadernillos", ["id" => $id_ubch]);
        $exist_ubch = $exist_ubch->row();

        if( empty( $exist_ubch ) ) {
            show_404();return;
        }
        

        
        $limit_data = 20;
        $num_votantes = $this->db->get_where("votantes", ["centro_votacion_id" => $id_ubch])->num_rows();
        $config = pagination_config(
            site_url("votante/filter_ubch/$id_ubch"),
            $limit_data,
            $num_votantes
        );

        $this->pagination->initialize($config);

        $dataEstadisticas = $this->votantes_model->votantes_x_ubch($config["por_page"], $offset, $id_ubch);
        
        $data["tituloPrincipal"] = $exist_ubch->NOMBRE_INSTITUCIONES_CON_CODIGO;
        $data["votantes"] = $dataEstadisticas;

        // var_dump($data["votantes"]);die();
        $this->load->view("votante/index", $data);
    }
    
    
    public function filter_parroquia($par, $offset = 0) {
        $parroquia = str_replace("-", " ", $par);
        
        $limit_data = 20;
        $num_votantes = $this->db->get_where("votantes", ["parroquia" => $parroquia])->num_rows();
        $config = pagination_config(
            site_url("votante/filter_parroquia/$par"),
            $limit_data,
            $num_votantes
        );

        $this->pagination->initialize($config);

        $dataEstadisticas = $this->votantes_model->votantes_x_parroquia($config["por_page"], $offset, $parroquia);
        
        $data["tituloPrincipal"] = $parroquia;
        $data["votantes"] = $dataEstadisticas;

        // var_dump($data["votantes"]);die();
        $this->load->view("votante/index", $data);
    }
    
    public function delete()
    {
        $rm = $this->input->server("REQUEST_METHOD"); 

        $id_votante = $this->input->post("id");
        if( $rm != "POST" OR !$id_votante ) {
            show_404();
            return;
        }

        $exist_votante = $this->db->get_where("votantes", ["id" => $id_votante]);
        $exist_votante = $exist_votante->num_rows();

        if( $exist_votante < 1 ) {
            show_404();return;
        }

        $_SESSION["alert"] = "El elemento se ha eliminado correctamente";
        
        $this->db->delete("votantes", ["id" => $id_votante]);

        redirect($this->agent->referrer());
    }
}
