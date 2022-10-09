<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Uploader v3.0 COMUNS Helpers
 *
 * @author			Frank Valle Sanchez
 * @author-email	fvalle81@gmail.com
 */


/*
	 * PATH HELPERS
	 *
	 * retornan una ruta virtual o fisica segun se el caso.
	 */
// ------------------------------------------------------------------------

function get_site_url($path = '', $protocol = 'autodetect')
{

	if ($protocol == 'autodetect') {
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
			$current_protocol = "https";
		}
		$current_protocol = "http:";
	} else {
		$current_protocol = $protocol;
	}


	return  str_replace('http:', $current_protocol, site_url($path));
}

function get_assets_url($path = '')
{
	$assets_url = get_site_url($path);
	$assets_url = str_replace("/index.php", "/", $assets_url);
	$assets_url = str_replace("admin/", "assets/", $assets_url);
	$assets_url = str_replace("assets//", "assets/", $assets_url);
	return  $assets_url;
}

function createDataDashboard($data_cuadernillos, $thiss)
{
	// $count_votantes_totales_registrados = 0;

	// $count_votantes_confirmados = 0;

	$parroquias = $thiss->votantes_model->parroquias();


	foreach ($data_cuadernillos as $i => $value) :

		
		$dataEstadisticas[$i]["COD_UBCH"] = $value->COD_UBCH;
		$dataEstadisticas[$i]["NOMBRE_INSTITUCIONES"] = $value->NOMBRE_INSTITUCIONES_CON_CODIGO;
		$dataEstadisticas[$i]["PARROQUIA"] = $value->PARROQUIA;

		$pVotante = $value->POBLACION_VOTANTE;
		$dataEstadisticas[$i]["POBLACION_VOTANTE"] = number_format($pVotante, 0);

		$vorantesConfirmados = $thiss->db->get_where("votantes", ["centro_votacion_id" => $value->id])->num_rows();


		$dataEstadisticas[$i]["VOTANTES_COMFIRMADOS"] = number_format($vorantesConfirmados, 0);

		$dataEstadisticas[$i]["PORCENTAJE_VOTANTES_CONFIRMADOS"] = number_format($vorantesConfirmados / $pVotante * 100, 2);

	// $count_votantes_totales_registrados += $value->POBLACION_VOTANTE;
	// $count_votantes_confirmados += $vorantesConfirmados;
	endforeach;


	// echo var_dump($count_votantes_totales_registrados);
	// die();

	$data["dataEstadisticas"] = $dataEstadisticas;


	$data["parroquias"] = $parroquias;

	return $data;
}
