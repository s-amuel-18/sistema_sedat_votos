<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Carnetizacion extends CI_Controller {

  private $http_headers;
  private $access_key;
  private $data_post;

	function __construct() {
    parent::__construct();
    header("Content-Type: application/json");
    $this->http_headers = apache_request_headers();
    $this->access_key = base64_encode('CarnetizacionSucreVotos:SucreVotos');
    $this->data_post = (object)json_decode(file_get_contents('php://input'), true);
    $this->load->model("votantes_model", "query");

    /**************************************************************************************************************************************** */
    /**************************************************************************************************************************************** */

    /**
     * VALIDAR API-KEY
     * ERROR 401 UNAUTHORIZED
     */

    if(!array_key_exists("carnetizacion-api-key",$this->http_headers)):
      echo json_encode("Se requiere autorizacion por key."); http_response_code(401); exit;
    endif;
    
    /**************************************************************************************************************************************** */
    /**************************************************************************************************************************************** */
    
    /**
     * VALIDAR API-KEY CORRECTA
     * ERROR 401 UNAUTHORIZED
     */

    if($this->http_headers['carnetizacion-api-key'] != $this->access_key):
      echo json_encode("Key incorrecta.");  http_response_code(401); exit;
    endif;

    /**************************************************************************************************************************************** */
    /**************************************************************************************************************************************** */
    
    /**
     * VALIDAR CONTENT-TYPE
     * ERROR 415 UNSUPPORTED MEDIA TYPE
     */

//    if(!array_key_exists("content-type",$this->http_headers)):
//      echo json_encode("Content-Type no recibido."); http_response_code(415); exit;
//    endif;

    /**************************************************************************************************************************************** */
    /**************************************************************************************************************************************** */

    /**
     * VALIDAR CONTENT-TYPE EN FORMATO JSON
     * ERROR 415 UNSUPPORTED MEDIA TYPE
     */

//    if($this->http_headers['content-type'] != 'application/json'):
//      echo json_encode("Content-Type Incorrecto, debe ser 'application/json'."); http_response_code(415); exit;
//    endif;

    /**************************************************************************************************************************************** */
    /**************************************************************************************************************************************** */

  }

  public function cant_vot_x_par(){

    $data = $this->data_post;
    $data_keys = array_keys((array)$data);
    $keys_permitted = ['parroquia'];
    $return = [];

    if(!empty($data) && is_object($data)):

      if(implode(',', $keys_permitted) != implode(',', $data_keys)):

        if(count($data_keys) == 0):
          $cantidad = 'no se recibieron campos';
        elseif(count($data_keys) == 1):
          $cantidad = 'se recibe el campo';
        else:
          $cantidad = 'se recibieron los campos';
        endif;

        $return = "Los campos necesarios son (" . implode(', ', $keys_permitted) . "), {$cantidad} (" . implode(', ', $data_keys).")";

      else:

        $parroquia = $data->parroquia;
        $cantidad = $this->query->cant_votantes_parroquia($parroquia);
        
        echo json_encode((int)$cantidad); exit;

      endif;

    else:
      $return = "No se ha recibido data.";
    endif;

    echo json_encode($return); exit;

  }

  /**************************************************************************************************************************************** */
  /**************************************************************************************************************************************** */

  public function det_vot_x_par(){

    $data = $this->data_post;
    $data_keys = array_keys((array)$data);
    $keys_permitted = ['parroquia'];
    $return = [];

    if(!empty($data) && is_object($data)):

      if(implode(',', $keys_permitted) != implode(',', $data_keys)):

        if(count($data_keys) == 0):
          $cantidad = 'no se recibieron campos';
        elseif(count($data_keys) == 1):
          $cantidad = 'se recibe el campo';
        else:
          $cantidad = 'se recibieron los campos';
        endif;

        $return = "Los campos necesarios son (" . implode(', ', $keys_permitted) . "), {$cantidad} (" . implode(', ', $data_keys).")";

      else:

        $parroquia = $data->parroquia;
        $cantidad = $this->query->det_votantes_parroquia($parroquia);
        
        echo json_encode($cantidad); exit;

      endif;

    else:
      $return = "No se ha recibido data.";
    endif;

    echo json_encode($return); exit;

  }

   /**************************************************************************************************************************************** */
  /**************************************************************************************************************************************** */

  public function upd_ids_vot(){

    $data = $this->data_post;
    $data_keys = array_keys((array)$data);
    $keys_permitted = ['ids'];
    $return = [];

    if(!empty($data) && is_object($data)):

      if(implode(',', $keys_permitted) != implode(',', $data_keys)):

        if(count($data_keys) == 0):
          $cantidad = 'no se recibieron campos';
        elseif(count($data_keys) == 1):
          $cantidad = 'se recibe el campo';
        else:
          $cantidad = 'se recibieron los campos';
        endif;

        $return = "Los campos necesarios son (" . implode(', ', $keys_permitted) . "), {$cantidad} (" . implode(', ', $data_keys).")";

      else:

        $ids = $data->ids;
        $update = $this->query->act_votantes_importados($ids);
        
        echo json_encode($update); exit;

      endif;

    else:
      $return = "No se ha recibido data.";
    endif;

    echo json_encode($return); exit;

  }


}
