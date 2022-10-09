<?php
defined('BASEPATH') or exit('No direct script access allowed');

class hoja_de_calculo extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		// libreria de excel
		$this->load->helper('tools_helper');
		$this->load->helper('url');
		$this->load->library("excel");
		$this->load->model("votantes_cuadernillos");
	}
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		// echo phpinfo();
		// return;
		$this->load->view('index');
	}

	public function upload()
	{

		if( !isset($_FILES["file"]) ) {
			redirect("/");
			
			return ;
		}
	 	
		$config['upload_path'] = './uploads';
		$config['allowed_types'] = 'xlsx';
		// $config['max_size'] = '10000000';
		// $config['max_width']  = '10240';
		// $config['max_height']  = '7680';
		$config['overwrite'] = TRUE;
		$config['encrypt_name'] = FALSE;
		$config['remove_spaces'] = TRUE;
		$config['file_name'] = "xlsx_" . uniqid();

		if (!is_dir($config['upload_path'])) die("THE UPLOAD DIRECTORY DOES NOT EXIST");
		$this->load->library('upload', $config);

		if (!$this->upload->do_upload("file")) {
			$datos["message_error"] = "El archivo seleccionado no es una hoja de calculo xlsx";
			$this->load->view('index', $datos);
			return;
		} else {

			// $data_img["dsa"] = $this->upload->data();
			$data =  array('upload_data' => $this->upload->data());


			$fileName = $data["upload_data"]["file_name"];
			// echo "upload/".$fileName;

			$archivoRoute = str_replace("\application", "", APPPATH) . "uploads\\$fileName";
			// echo $archivoRoute;
			// return;
			$valorExacel = arrayCeldasVotantes($archivoRoute);

			$selecNombreInstituciones = arrayVotantesCudernillos();

			// $selecNombreInstituciones = $this->votantes_cuadernillos->selecNombreInstituciones();
		
			foreach ($selecNombreInstituciones as  $value):

				$valorSinEspacios = trim($value["NOMBRE_INSTITUCIONES"]);
				// $valorSinEspacios = trim($value["PARROQUIA"]);
				
				$votantesInstituciones[$valorSinEspacios] = 0;

			endforeach;

			
			foreach ($valorExacel as $i =>  $value):
				// echo $i;
				
				if(array_key_exists($value["centro_de_votación"], $votantesInstituciones)):
					$votantesInstituciones[$value["centro_de_votación"]] = $votantesInstituciones[$value["centro_de_votación"]] + 1;
					
				endif;
				
			endforeach;
			
			$cuadernillaData = $selecNombreInstituciones;

			$estadistica_votantes = arrayEstadisticaExcel($cuadernillaData, $votantesInstituciones);
			
			$this->votantes_cuadernillos->deleteAllEstadisticas();
			foreach($estadistica_votantes as $value) {
				$value["PARROQUIA"] = trim($value["PARROQUIA"]);
				$this->db->insert("estadistica_votantes", $value);
			}
 
			redirect("/hoja_de_calculo/estadisticas");
	
		}
	}


	public function estadisticas() {
		
		$estadistica_votantes = $this->votantes_cuadernillos->leerEstadisticas();

		if( empty( $estadistica_votantes ) ) {
			redirect("/");
		}

		
		$sumaVotandesConf = sumaVotantes($estadistica_votantes, "VOTANTES_CONFIRMADOS");
		$sumaVotandesReg = sumaVotantes($estadistica_votantes, "VOTANTES_REGISTRADOS");
		$porcentajeSumaVotandesConf = $sumaVotandesConf / $sumaVotandesReg * 100;
		// echo $sumaVotandesReg;
		// return;
		$parroquias = $this->votantes_cuadernillos->parroquias();

		
		$data["estadistica_votantes"] = $estadistica_votantes;
		$data["parroquias"] = $parroquias;
		$data["sumaVotandesConf"] = $sumaVotandesConf;
		$data["sumaVotandesReg"] = $sumaVotandesReg;
		$data["porcentajeSumaVotandesConf"] = number_format($porcentajeSumaVotandesConf, 3);
		$data["tituloPrincipal"] = "Instituciones de votacion";
			
		
		$this->load->view("estadisticas", $data);
	}

	public function filtroPorParroquia() {
		$parroquiaFilter = $this->input->post("parroquia");

		if( !isset($parroquiaFilter) ) redirect("/");
		
		$selectFiltro = $this->votantes_cuadernillos->filtrarVotosPorParroquia($parroquiaFilter);
		if( empty($selectFiltro) ) redirect("/");

		$parroquias = $this->votantes_cuadernillos->parroquias();

		$sumaVotandesConf = sumaVotantes($selectFiltro, "VOTANTES_CONFIRMADOS");
		$sumaVotandesReg = sumaVotantes($selectFiltro, "VOTANTES_REGISTRADOS");
		$porcentajeSumaVotandesConf = $sumaVotandesConf / $sumaVotandesReg * 100;

		$data["estadistica_votantes"] = $selectFiltro;
		$data["parroquias"] = $parroquias;
		$data["parroquiaFilter"] = $parroquiaFilter;
		$data["sumaVotandesConf"] = $sumaVotandesConf;
		$data["sumaVotandesReg"] = $sumaVotandesReg;
		$data["porcentajeSumaVotandesConf"] = number_format($porcentajeSumaVotandesConf, 3);
		$data["tituloPrincipal"] = "Parroquia \"$parroquiaFilter\"";
		
		// echo var_dump($selectFiltro);
		$this->load->view("estadisticas", $data);
	}
}

