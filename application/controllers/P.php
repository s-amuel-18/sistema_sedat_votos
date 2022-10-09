<?php
defined('BASEPATH') or exit('No direct script access allowed');

class P extends CI_Controller
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
        $this->load->model(["mesa_instalacion_model", "votantes_model", "mesa_model", "voto_candidato_model"]);


        $this->ubch = $this->db->select(["id", "NOMBRE_INSTITUCIONES_CON_CODIGO"])->get("data_general_cuadernillos")->result();
        $this->parroquias = $this->votantes_model->parroquias();

        $this->data_post = (object)json_decode(file_get_contents('php://input'), true);
    }

    public function index()
    {
        // var_dump($this->parroquias);die();
        
        $t = "
        <table>
            <tbody>
                ";
        foreach ($this->parroquias as $p) {
            $votos_por_parroquia = $this->voto_candidato_model->candidato_voto_par($p);
            $total = 0;
            // var_dump($votos_por_parroquia);die();

            $t .= "<tr>";
            $alcalde = 0;
            $gobernador = 0;

            foreach ($votos_por_parroquia as $votos) {
                $total += $votos->votos;
                if( $votos->cargo =="ALCALDE" ) {
                    $alcalde += $votos->votos;
                } else {
                    $gobernador += $votos->votos;
                }
                $t .= "<td>{$votos->votos}</td>";
            }
            $alcalde_porcentaje = $alcalde * 100 / ( $alcalde + $gobernador );
            $gobernador_porcentaje = $gobernador * 100 / ( $alcalde + $gobernador );
            $t .= "<td>{$alcalde_porcentaje}</td>";
            $t .= "<td>{$gobernador_porcentaje}</td>";
            $t .= "<td>{$total}</td>";
            $t .= "</tr>";
            
        }
        $t .= "
        </tbody>
    </table>";
        
        echo $t;
    }
}

?>
