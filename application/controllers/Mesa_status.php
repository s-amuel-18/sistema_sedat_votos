<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Mesa_status extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();


    $this->load->model(["votantes_model", "mesa_instalacion_model", "mesa_constitucion_model", "voto_candidato_model", "votantes_cuadernillos"]);
    $this->load->helper(["tools_helper", "url", "form"]);
    $this->load->library(["form_validation", "session", 'user_agent']);

    if (!$this->session->userdata("is_logged")) {
      redirect("/auth/login");
      return;
    }
    $this->ubch = $this->db->select(["id", "NOMBRE_INSTITUCIONES_CON_CODIGO", "PARROQUIA"])->get("data_general_cuadernillos")->result();
  }

  public function index()
  {
    $parroquias = $this->votantes_cuadernillos->parroquias();
    $data["title_page"] = "Seleccionar Centro de Votacion";
    $data["parroquias"] = $parroquias;



    $this->load->view("status_mesa/seleccion_ubch", $data);
  }


  public function status_mesa($id_ubch)
  {
    $ubch = $this->db->get_where("data_general_cuadernillos", ["id" => $id_ubch])->row();
    if (empty($ubch)) {
      show_404();
    }

    $cotillon_valido = $this->db->get_where("entrega_cotillon", ["centro_votacion_id" => $ubch->id])->row();
    $data["cotillon"] = $cotillon_valido;

    $mesas_constituidas = $this->db->select(["id", "numero_mesa"])->get_where("mesas_constitucion", ["centro_votacion_id" => $id_ubch])->result();

    $mesas_instaladas = $this->db->select(["id", "numero_mesa"])->get_where("mesas_instalacion", ["centro_votacion_id" => $id_ubch, "averiado" => 0])->result();

    $mesas_por_ubch = $this->db->get_where("mesas", ["COD_UBCH" => $ubch->COD_UBCH])->row("MESAS");

    $data["mesas_por_ubch"] = $mesas_por_ubch;
    $data["mesas_constituidas"] = $mesas_constituidas;
    $data["mesas_instaladas"] = $mesas_instaladas;
    $data["title_page"] = "seleccion estatus";
    $data["subtitle_page"] = "selecciona un status para el centro de votacion " . $ubch->NOMBRE_INSTITUCIONES_CON_CODIGO;
    $data["ubch"] = $ubch;

    $this->load->view("status_mesa/seleccion_status", $data);
  }

  public function entrega_material($id_ubch)
  {
    $rm = $this->input->server("REQUEST_METHOD");
    $ubch = $this->db->get_where("data_general_cuadernillos", ["id" => $id_ubch])->row();


    if ($rm != "POST" or empty($ubch)) {
      show_404();
    }
    $request = $this->input->post();

    $config = [
      [
        "field" => "entregado",
        "label" => "entrega de material",
        "rules" => "required|numeric",
      ],
      [
        "field" => "observacion",
        "label" => "observacion",
        "rules" => $request["entregado"] == 0 ? "required" : "",
      ],
      [
        "field" => "id_cotillon",
        "label" => "identificador",
        "rules" => "required",
      ],
    ];

    $this->form_validation->set_rules($config);
    if (!$this->form_validation->run()) {

      $_SESSION["error_message"] = "Todos los campos son obligatorios, en caso de que no se entragaron los materiales (cotillon) es obligatorio adjuntar una observacion";

      redirect("mesa_status/status_mesa/" . $id_ubch);
      return;
    }

    $data_insert["centro_votacion_id"] = $id_ubch;
    $data_insert["user_id"] = $this->session->userdata("id");
    $data_insert["parroquia"] = $ubch->PARROQUIA;
    $data_insert["entregado"] = set_value("entregado");
    $data_insert["observacion"] = set_value("observacion") == "" ? null : set_value("observacion");


    if ($request["id_cotillon"] == 0) {
      $accion = "registrado";
      $response = $this->db->insert(
        "entrega_cotillon",
        $data_insert
      );
    } else {
      foreach ($data_insert as $k => $value) {
        $this->db->set($k, $value);
      }

      $this->db->where("id", $request["id_cotillon"]);
      $response = $this->db->update("entrega_cotillon");
      $accion = "actualizado";
    }


    if (!$response) {
      $_SESSION["error_message"] = "ha ocurrido un error";

      redirect("mesa_status/status_mesa/" . $id_ubch);
      return;
    }

    $_SESSION["success_message"] = "Se ha $accion correctamente";

    redirect("mesa_status/status_mesa/" . $id_ubch);
  }


  public function instalacion_mesa($id_ubch)
  {
    $rm = $this->input->server("REQUEST_METHOD");
    $ubch = $this->db->get_where("data_general_cuadernillos", ["id" => $id_ubch])->row();


    if ($rm != "POST" or empty($ubch)) {
      show_404();
    }

    $all_mesas_insta = $this->input->post("instalar_todo");

    if ($all_mesas_insta != true) {
      $config = [
        [
          "field" =>  "numero_mesa",
          "label" => "Numero de mesa",
          "rules" =>  "required|numeric",
          "errors" => [
            "required" => "El campo %s es requerido",
            "numeric" => "El campo %s debe ser un numero"
          ]
        ],
      ];


      $post_data = $this->input->post();

      if (isset($post_data["averiado"])) {
        $config[count($config)] = [
          "field" => "observacion",
          "label" => "observacion",
          "rules" => "required"
        ];

        $request["averiado"] = 1;
        $request["observacion"] = $post_data["observacion"];
        $message_success = "la mesa se ah registrado como averiada.";
      } else {
        
        $request["averiado"] = 0;
        $message_success = "La mesa numero " . $this->input->post("numero_mesa") . " se ha instalado correctamente en el centro de votacion " . $ubch->NOMBRE_INSTITUCIONES_CON_CODIGO;
      }

      $this->form_validation->set_rules($config);

      if (!$this->form_validation->run()) {

        $ubch = $this->db->get_where("data_general_cuadernillos", ["id" => $id_ubch])->row();
        if (empty($ubch)) {
          show_404();
        }

        $cotillon_valido = $this->db->get_where("entrega_cotillon", ["centro_votacion_id" => $ubch->id])->row();
        $data["cotillon"] = $cotillon_valido;

        $mesas_constituidas = $this->db->select(["id", "numero_mesa"])->get_where("mesas_constitucion", ["centro_votacion_id" => $id_ubch])->result();

        $mesas_instaladas = $this->db->select(["id", "numero_mesa"])->get_where("mesas_instalacion", ["centro_votacion_id" => $id_ubch, "averiado" => 0])->result();

        // var_dump($mesas_constituidas);die();

        $mesas_por_ubch = $this->db->get_where("mesas", ["COD_UBCH" => $ubch->COD_UBCH])->row("MESAS");

        $data["mesas_por_ubch"] = $mesas_por_ubch;
        $data["mesas_constituidas"] = $mesas_constituidas;
        $data["mesas_instaladas"] = $mesas_instaladas;
        $data["title_page"] = "seleccion estatus";
        $data["subtitle_page"] = "selecciona un status para el centro de votacion " . $ubch->NOMBRE_INSTITUCIONES_CON_CODIGO;
        $data["ubch"] = $ubch;

        $this->load->view("status_mesa/seleccion_status", $data);
        return;
      }

      $request["numero_mesa"] = $this->input->post("numero_mesa");
      $request["centro_votacion_id"] = $id_ubch;
      $request["user_id"] = $this->session->userdata("id");
      $request["parroquia"] = $ubch->PARROQUIA;



      $validate_mesa = $this->mesa_instalacion_model->validate_mesa($id_ubch, $request["numero_mesa"]);



      if ($validate_mesa) {
        foreach ($request as $k => $value) {
          $this->db->set($k, $value);
        }
  
        $this->db->where("id", $validate_mesa->id);
        $this->db->update("mesas_instalacion");
        
        
          $msg = "La mesa numero " . $validate_mesa->numero_mesa . " se ha actualizado correctamente)";


        $_SESSION["success_message"] = $msg;

        redirect("mesa_status/status_mesa/" . $id_ubch);

        return;
      }

          // $this->mesa_instalacion_model->insertar($request);
    $this->db->insert("mesas_instalacion", $request);
    $_SESSION["success_message"] = $message_success;

  } else {
    $mesage = $this->seleccionar_todas_las_mesas("mesas_instalacion", $ubch, "Constitucion");
    $_SESSION["success_message"] = $mesage;
  }
  


    redirect("mesa_status/status_mesa/" . $id_ubch);
  }


  public function constitucion_mesa($id_ubch)
  {
    $rm = $this->input->server("REQUEST_METHOD");
    $ubch = $this->db->get_where("data_general_cuadernillos", ["id" => $id_ubch])->row();


    if ($rm != "POST" or empty($ubch)) {
      show_404();
    }

    $all_mesas_const = $this->input->post("constituir_todo");

    if( $all_mesas_const != true ) {

      $config = [
        [
          "field" =>  "numero_mesa",
          "label" => "Numero de mesa",
          "rules" =>  "required|numeric",
          "errors" => [
            "required" => "El campo %s es requerido",
            "numeric" => "El campo %s debe ser un numero"
          ]
        ],
      ];
  
      $this->form_validation->set_rules($config);
  
      if (!$this->form_validation->run()) {
  
        $ubch = $this->db->get_where("data_general_cuadernillos", ["id" => $id_ubch])->row();
        if (empty($ubch)) {
          show_404();
        }
  
        $cotillon_valido = $this->db->get_where("entrega_cotillon", ["centro_votacion_id" => $ubch->id])->row();
        $data["cotillon"] = $cotillon_valido;
  
        $mesas_constituidas = $this->db->select(["id", "numero_mesa"])->get_where("mesas_constitucion", ["centro_votacion_id" => $id_ubch])->result();
  
        $mesas_instaladas = $this->db->select(["id", "numero_mesa"])->get_where("mesas_instalacion", ["centro_votacion_id" => $id_ubch, "averiado" => 0])->result();
  
        // var_dump($mesas_constituidas);die();
  
        $mesas_por_ubch = $this->db->get_where("mesas", ["COD_UBCH" => $ubch->COD_UBCH])->row("MESAS");
  
        $data["mesas_por_ubch"] = $mesas_por_ubch;
        $data["mesas_constituidas"] = $mesas_constituidas;
        $data["mesas_instaladas"] = $mesas_instaladas;
        $data["title_page"] = "seleccion estatus";
        $data["subtitle_page"] = "selecciona un status para el centro de votacion " . $ubch->NOMBRE_INSTITUCIONES_CON_CODIGO;
        $data["ubch"] = $ubch;
  
        $this->load->view("status_mesa/seleccion_status", $data);
        return;
      }
  
      $request["numero_mesa"] = $this->input->post("numero_mesa");
      $request["centro_votacion_id"] = $id_ubch;
      $request["user_id"] = $this->session->userdata("id");
      $request["parroquia"] = $ubch->PARROQUIA;
  
      $validate_instalacion = $this->mesa_instalacion_model->validate_mesa($id_ubch, $request["numero_mesa"]);
  
  
      if (!$validate_instalacion) {
        $_SESSION["error_message"] = "La mesa numero " . $request["numero_mesa"] . " aun no ha sido instalada, por favor ingrese el numero de mesa que desea constituir en instalacion de mesa.";
        redirect("mesa_status/status_mesa/" . $id_ubch);
  
        return;
      }
  
  
      $validate_mesa = $this->mesa_constitucion_model->validate_mesa($id_ubch, $request["numero_mesa"]);
  
  
  
      if ($validate_mesa) {
        $fecha = new DateTime($validate_mesa->fecha);
        $fecha = date_format($fecha, "Y/m/d");
  
        $_SESSION["error_message"] = "La mesa numero " . $validate_mesa->numero_mesa . " ya ha sido constituida <br> (fecha de constitucion $fecha)";
  
        redirect("mesa_status/status_mesa/" . $id_ubch);
  
        return;
      }
  
  
  
  
      $this->mesa_constitucion_model->insertar($request);
  
      $_SESSION["success_message"] = "La mesa numero " . $request["numero_mesa"] . " se ha constituido correctamente en el centro de votacion " . $ubch->NOMBRE_INSTITUCIONES_CON_CODIGO;
  
    } else {
      // var_dump($ubch);die();
      $mesas_instaladas = $this->mesa_instalacion_model->mesas_instaladas_sin_averiar_y_constituir($ubch->id);
      
      foreach ($mesas_instaladas as $mesa) {
        $data_insert["numero_mesa"] = $mesa->numero_mesa;
        $data_insert["centro_votacion_id"] = $mesa->centro_votacion_id;
        $data_insert["user_id"] = $this->session->userdata("id");
        $data_insert["parroquia"] = $mesa->parroquia;

        $this->db->insert("mesas_constitucion", $data_insert);
      }

      $_SESSION["success_message"] = "se constituyeron ". count($mesas_instaladas) . " mesas correctamente";
    }
    
    redirect("mesa_status/status_mesa/" . $id_ubch);
  }

  public function cierre_mesa_candidatos()
  {
    $numero_mesa = $this->input->get("numero_mesa");
    $cv_id = $this->input->get("cv_id");

    $validate_mesa = $this->db->get_where(
      "mesas_cierre",
      [
        "numero_mesa" => $numero_mesa,
        "centro_votacion_id" => $cv_id
      ]
    )->row();


    if (!empty($validate_mesa)) {
      $fecha = new DateTime($validate_mesa->fecha);
      $fecha = date_format($fecha, "Y/m/d");

      $_SESSION["error_message"] = "La mesa numero " . $validate_mesa->numero_mesa . " ya ha sido cerrada <br> (fecha de cierre $fecha)";

      redirect("mesa_status/status_mesa/" . $cv_id);
    }

    $mesa = $this->db->get_where(
      "mesas_constitucion",
      [
        "numero_mesa" => $numero_mesa,
        "centro_votacion_id" => $cv_id
      ]
    )->row();

    $ubch = $this->db->get_where("data_general_cuadernillos", ["id" => $cv_id])->row();



    if (empty($mesa) or empty($ubch)) {
      show_404();
      return;
    }

    $data["ubch"] = $ubch;
    $data["mesa_data"] = $mesa;
    $data["url_form"] = site_url("mesa_status/cierre_mesa?numero_mesa=$mesa->numero_mesa&cv_id=$ubch->id");
    $data["candidatos_data"] = $this->db->order_by("cargo", "ASC")->get("candidatos")->result();
    $this->load->view("status_mesa/cierre_mesa", $data);
  }

  public function cierre_mesa()
  {
    $rm = $this->input->server("REQUEST_METHOD");

    $numero_mesa = $this->input->get("numero_mesa");
    $cv_id = $this->input->get("cv_id");

    $validate_mesa = $this->db->get_where(
      "mesas_cierre",
      [
        "numero_mesa" => $numero_mesa,
        "centro_votacion_id" => $cv_id
      ]
    )->row();


    if (!empty($validate_mesa)) {
      $fecha = new DateTime($validate_mesa->fecha);
      $fecha = date_format($fecha, "Y/m/d");

      $_SESSION["error_message"] = "La mesa numero " . $validate_mesa->numero_mesa . " ya ha sido cerrada <br> (fecha de cierre $fecha)";

      redirect("mesa_status/status_mesa/" . $cv_id);
    }

    $mesa = $this->db->get_where(
      "mesas_constitucion",
      [
        "numero_mesa" => $numero_mesa,
        "centro_votacion_id" => $cv_id
      ]
    )->row();

    $ubch = $this->db->get_where("data_general_cuadernillos", ["id" => $cv_id])->row();


    if (empty($mesa) or empty($ubch) or $rm != "POST") {
      show_404();
      return;
    }

    $post_request = $this->input->post();

    $mesa_insert["numero_mesa"] = $numero_mesa;
    $mesa_insert["centro_votacion_id"] = $cv_id;
    $mesa_insert["user_id"] = $this->session->userdata("id");
    $mesa_insert["parroquia"] = $ubch->PARROQUIA;

    $this->db->insert("mesas_cierre", $mesa_insert);

    foreach ($post_request as $id => $votos) {
      $voto_insert["numero_mesa"] = $numero_mesa;
      $voto_insert["centro_votacion_id"] = $cv_id;
      $voto_insert["candidato_id"] = $id;
      $voto_insert["votos"] = empty($votos) ? 0 : $votos;
      $voto_insert["parroquia"] = $ubch->PARROQUIA;

      // var_dump("<pre>", $voto_insert); echo "<br>";
      $this->db->insert("votos_candidatos", $voto_insert);
    }

    $_SESSION["success_message"] = "La mesa numero " . $mesa_insert["numero_mesa"] . " se ha Cerrado correctamente en el centro de votacion " . $ubch->NOMBRE_INSTITUCIONES_CON_CODIGO;

    redirect("mesa_status/status_mesa/" . $cv_id);
  }

  public function actualizar_mesa_cerrada()
  {
    $numero_mesa = $this->input->get("numero_mesa");
    $cv_id = $this->input->get("cv_id");

    $validate_mesa = $this->db->get_where(
      "mesas_cierre",
      [
        "numero_mesa" => $numero_mesa,
        "centro_votacion_id" => $cv_id
      ]
    )->row();


    if (empty($validate_mesa)) {
      show_404();
      return;
    }

    $ubch = $this->db->get_where("data_general_cuadernillos", ["id" => $cv_id])->row();



    if (empty($ubch)) {
      show_404();
      return;
    }

    $votos_candidato = $this->voto_candidato_model->candidatos_por_mesas($cv_id, $numero_mesa);

    $total_votos = 0;


    $total_votos = [
      "ALCALDE" => 0,
      "GOBERNADOR" => 0
    ];
    foreach ($votos_candidato as $voto) {
      $vt = $voto->votos;
      if ($voto->cargo == "ALCALDE") {
        $total_votos["ALCALDE"] += $vt;
      } else {
        $total_votos["GOBERNADOR"] += $vt;
      }
    }



    $data["ubch"] = $ubch;
    $data["total_votos"] = $total_votos;
    $data["url_form"] = site_url("mesa_status/update_cierre_mesa?numero_mesa=$validate_mesa->numero_mesa&cv_id=$ubch->id");
    $data["mesa_data"] = $validate_mesa;
    $data["candidatos_data"] = $votos_candidato;
    $this->load->view("status_mesa/cierre_mesa", $data);
  }

  public function update_cierre_mesa()
  {
    $rm = $this->input->server("REQUEST_METHOD");

    $numero_mesa = $this->input->get("numero_mesa");
    $cv_id = $this->input->get("cv_id");

    $validate_mesa = $this->db->get_where(
      "mesas_cierre",
      [
        "numero_mesa" => $numero_mesa,
        "centro_votacion_id" => $cv_id
      ]
    )->row();

    $ubch = $this->db->get_where("data_general_cuadernillos", ["id" => $cv_id])->row();

    if (empty($validate_mesa) or empty($ubch) or $rm != "POST") {
      show_404();
      return;
    }


    $post_request = $this->input->post();


    foreach ($post_request as $id => $votos) {
      $votos = empty($votos) ? 0 : $votos;

      $this->voto_candidato_model->update_votos($cv_id, $numero_mesa, $id, $votos);
    }

    $_SESSION["success_message"] = "se ha actualizado correctamente";

    redirect("candidatos_estadistica/filtro_cv/" . $cv_id);
  }
  
  private function seleccionar_todas_las_mesas($table, $ubch, $accion) {
    $mesas_instaladas = $this->db->select("numero_mesa")->get_where(
      $table,
      [
        "centro_votacion_id" => $ubch->id
      ]
    )->result();

    $mesas_totales = $this->db->select("mesas")->get_where(
      "mesas",
      [
        "COD_UBCH" => $ubch->COD_UBCH
      ]
    )->row("mesas");

    if( count($mesas_instaladas) == $mesas_totales) {
      return "Ya se realizo la $accion de todas las mesas previamente";
    }
    
    $mesas_por_seleccionar = [];
    
    for ($i=1; $i <= $mesas_totales; $i++) { 
      $mesas_por_seleccionar[count($mesas_por_seleccionar)] = $i; 
    }
    
    foreach ($mesas_instaladas as $mesa_data) {
      $num_mesa = $mesa_data->numero_mesa;
      if( $pos = array_search($num_mesa, $mesas_por_seleccionar) ) {
        unset($mesas_por_seleccionar[$pos]);   

      } 
    }

    
    foreach ($mesas_por_seleccionar as $mesa) {
      $request["numero_mesa"] = $mesa;
      $request["centro_votacion_id"] = $ubch->id;
      $request["user_id"] = $this->session->userdata("id");
      $request["parroquia"] = $ubch->PARROQUIA;
      
      $this->db->insert($table, $request);
    }

    $c = count($mesas_por_seleccionar);

    return "La $accion de {$c} mesas se ha realizado Correctamente";
  }

  public function delete_mesa_cerrada()
  {
    $numero_mesa = $this->input->get("numero_mesa");
    $cv_id = $this->input->get("cv_id");

    // site_url("mesa_status/delete_mesa_cerrada?numero_mesa=1&cv_id=22")    

    $mesa_cerrada = $this->db->get_where(
      "mesas_cierre",
      [
        "centro_votacion_id" => $cv_id,
        "numero_mesa" => $numero_mesa 
      ]
    )->row();

    if( $mesa_cerrada ) {
      $candidatos = $this->db->get_where(
        "votos_candidatos",
        [
          "centro_votacion_id" => $cv_id,
          "numero_mesa" => $numero_mesa 
        ]
      )->result();

      $this->db->delete('mesas_cierre', ['id' => $mesa_cerrada->id]);

      foreach( $candidatos as $c ) {
        $this->db->delete('votos_candidatos', ['id' => $c->id]);
      }
    }


    redirect("mesa_estadisticas");
  }
}
