<?php
$this->load->view("template/head.php");
?>

<!-- navbar -->
<?php $this->load->view("components/navbar"); ?>
<!-- endnavbar -->

<div style="background-color: #ececec;">
  <div class="container">
    <div class="d-flex align-items-center" style="min-height: 100vh;">
      <div class="text-center shadow col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 bg-white d-flex align-items-center justify-content-center flex-column p-4" style="border-radius: 20px; min-height: 400px;">

        <h1 class="mb-3"><?php echo $title_page ?></h1>

        <p class="mb-4"><?php echo $subtitle_page ?></p>

        <?php if (validation_errors() or isset($_SESSION["error_message"])) : ?>
          <div class="alert alert-danger text-danger" role="alert">
            <?php echo validation_errors("<div>", "*</div>") ?>
            <?php if (isset($_SESSION["error_message"])) : ?>
              <?php echo $_SESSION["error_message"];
              unset($_SESSION["error_message"]) ?>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <?php if (isset($_SESSION["success_message"])) : ?>
          <div class="alert alert-success" role="alert">
            <?php echo $_SESSION["success_message"] ?>
          </div>
        <?php
          unset($_SESSION["success_message"]);
        endif;
        ?>


        <div class="w-100 text-left">
          <div id="accordion">


            <div class="card">
              <div class="bg-info" id="headingPrimary">
                <h5 class="mb-0">
                  <button class="card-header btn btn-block btn-info" data-toggle="collapse" data-target="#collapseprimary" aria-expanded="true" aria-controls="collapseprimary">

                    <?php if (!empty($cotillon)) : ?>
                      <i class="
                      <?php echo $cotillon->entregado == 1 ? "fas fa-check" : 'far fa-times-circle' ?>
                    "></i>
                      <?php echo $cotillon->entregado == 1 ? "Materiales entregados con exito" : 'Los materiales no han sido entregados"' ?>
                    <?php else : ?>
                      Entrega de materiales Electorales
                    <?php endif ?>


                  </button>
                </h5>
              </div>

              <div id="collapseprimary" class="collapse <?php echo (empty($cotillon) or $cotillon->entregado == 0) ? "show" : "" ?>" aria-labelledby="headingPrimary" data-parent="#accordion">
                <div class="card-body">

                  <form action="<?php echo site_url("mesa_status/entrega_material/" . $ubch->id) ?>" method="POST">
                    <p>¿la entrega de los materiales fue <b>Exitosa</b>? en el centro de votación <?php echo $ubch->NOMBRE_INSTITUCIONES_CON_CODIGO ?></p>

                    <input type="hidden" name="id_cotillon" value="<?php echo !empty($cotillon) ? $cotillon->id : 0 ?>">

                    <div class="form-group">

                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="entregado" id="flexRadioDefault1" value="1" <?php echo (!empty($cotillon) and $cotillon->entregado == 1) ? "checked" : "" ?>>
                        <label class="form-check-label" for="flexRadioDefault1">
                          Se entregaron correctamente
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="entregado" id="flexRadioDefault2" value="0" <?php echo (!empty($cotillon) and $cotillon->entregado == 0) ? "checked" : "" ?>>
                        <label class="form-check-label" for="flexRadioDefault2">
                          No se entregaron
                        </label>
                      </div>
                    </div>


                    <div class="form-group">
                      <label for="observacion">Observacion</label>
                      <textarea class="form-control" name="observacion" id="observacion" rows="4"><?php echo !empty($cotillon) ? $cotillon->observacion : "" ?></textarea>
                    </div>
                    <button class="btn btn-primary" type="submit">
                      <?php echo empty($cotillon) ? "Registrar" : "Actualizar" ?>
                    </button>

                  </form>

                </div>
              </div>
            </div>

            <?php if (!empty($cotillon)) : ?>
              <?php if ($cotillon->entregado == 1) : ?>
                <div class="card">
                  <div class="bg-primary" id="headingOne">
                    <h5 class="mb-0">
                      <button class="card-header btn btn-block btn-primary" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Instalacion de mesa
                      </button>
                    </h5>
                  </div>

                  <div id="collapseOne" class="collapse " aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">

                      <div class="row">
                        <div class="col-12">
                          <div style="flex-direction: row;" class="list-group" id="list-tab" role="tablist">
                            <a class="d-inline-block list-group-item list-group-item-action  active" id="list-home-list" data-toggle="list" href="#list-home" role="tab" aria-controls="home">Instalacion</a>
                            <a class="d-inline-block list-group-item list-group-item-action" id="list-profile-list" data-toggle="list" href="#list-profile" role="tab" aria-controls="profile">Mesas Averiadas</a>
                          </div>
                        </div>
                        <div class="p-3 col-12">
                          <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">


                              <form action="<?php echo site_url("mesa_status/instalacion_mesa/" . $ubch->id) ?>" method="POST">
                                <p>Ingrese el numero de mesa que se desea <b>Instañar</b> en el centro de votación <?php echo $ubch->NOMBRE_INSTITUCIONES_CON_CODIGO ?></p>

                                <div class="form-group">
                                  <label for="instalacion">Numero de mesa</label>

                                  <select id="instalacion" class="form-control" name="numero_mesa">
                                    <?php for ($i = 1; $i <= $mesas_por_ubch; $i++) : ?>
                                      <option value="<?php echo $i ?>"><?php echo "Mesa Numero " . $i ?></option>
                                    <?php endfor ?>
                                  </select>
                                  <input type="hidden" value="<?php echo $ubch->id ?>" name="centro_votacion_id">
                                </div>

                                <div class="form-check mb-3">
                                  <input id="INSTALAR_TODO" class="form-check-input" type="checkbox" name="instalar_todo" value="true">
                                  <label for="INSTALAR_TODO" class="form-check-label">Constituir todas las mesas</label>
                                </div>

                                <button class="btn btn-primary" type="submit">Registrar</button>
                              </form>


                            </div>
                            <div class="tab-pane fade" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">

                              <form action="<?php echo site_url("mesa_status/instalacion_mesa/" . $ubch->id) ?>" method="POST">
                                <p>En caso de tener probles con la constitucion de una mesa puedes registrar el numero de la mesa que tiene problesmas y es obligatorio una descripcion que señale el problema que se tiene.</p>

                                <input type="hidden" name="averiado" value="1">

                                <div class="form-group">
                                  <label for="instalacion">Numero de mesa</label>

                                  <select id="instalacion" class="form-control" name="numero_mesa">
                                    <?php for ($i = 1; $i <= $mesas_por_ubch; $i++) : ?>
                                      <option value="<?php echo $i ?>"><?php echo "Mesa Numero " . $i ?></option>
                                    <?php endfor ?>
                                  </select>
                                  <input type="hidden" value="<?php echo $ubch->id ?>" name="centro_votacion_id">
                                </div>

                                <div class="form-group">
                                  <label for="observacion">observacion</label>
                                  <textarea id="observacion" class="form-control" name="observacion" rows="5"></textarea>
                                </div>

                                <button class="btn btn-primary" type="submit">Registrar</button>
                              </form>

                            </div>
                          </div>
                        </div>
                      </div>


                    </div>
                  </div>
                </div>

                <div class="card">
                  <div class="bg-success" id="headingTwo">
                    <h5 class="mb-0">
                      <button class="card-header btn btn-block btn-success" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Constitucion de mesa
                      </button>
                    </h5>
                  </div>
                  <div id="collapseTwo" class="collapse <?php echo !empty($cotillon) ? "show" : "" ?>" aria-labelledby="headingTwo" data-parent="#accordion">
                    <div class="card-body">

                      <?php if (!empty($mesas_instaladas)) : ?>
                        <form action="<?php echo site_url("mesa_status/constitucion_mesa/" . $ubch->id) ?>" method="POST">
                          <p>Ingrese el numero de mesa que se desea <b>Constituir</b> en el centro de votación <?php echo $ubch->NOMBRE_INSTITUCIONES_CON_CODIGO ?></p>

                          <div class="form-group">
                            <label for="instalacion">Numero de mesa</label>

                            <select class="form-control" name="numero_mesa" id="instalacion">
                              <option value=" ">seleccionar mesa</option>
                              <?php foreach ($mesas_instaladas as $mesa) : ?>
                                <option value="<?php echo $mesa->numero_mesa ?>"><?php echo "Mesa Numero " . $mesa->numero_mesa ?></option>
                              <?php endforeach ?>
                            </select>

                            <input type="hidden" value="<?php echo $ubch->id ?>">
                          </div>  

                          <div class="form-check mb-3">
                            <input id="const_todo" class="form-check-input" type="checkbox" name="constituir_todo" value="true">
                            <label for="const_todo" class="form-check-label">Constituir todas las mesas</label>
                          </div>

                          <button class="btn btn-success" type="submit">Registrar</button>
                        </form>
                      <?php else : ?>

                        <div class="alert alert-danger" role="alert">
                          No hay mesas Instaladas
                        </div>

                      <?php endif ?>
                    </div>
                  </div>
                </div>

                <div class="card">
                  <div class="bg-danger" id="headingThree">
                    <h5 class="mb-0">
                      <button class="card-header btn btn-block btn-danger" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        cierre de mesa
                      </button>
                    </h5>
                  </div>
                  <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                    <div class="card-body">


                      <?php if (!empty($mesas_constituidas)) : ?>
                        <p>seleccione la mesa que desea <b>Cerrar</b> en el centro de votación <?php echo $ubch->NOMBRE_INSTITUCIONES_CON_CODIGO ?></p>
                        <form action="">

                          <div class="form-group">
                            <div class="dropdown">
                              <button id="cierre_mesa" class="btn btn-primary btn-block dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Seleccionar mesa</button>
                              <div class="dropdown-menu" aria-labelledby="cierre_mesa">
                                <?php foreach ($mesas_constituidas as $item) : ?>
                                  <a class="dropdown-item" href="<?php echo site_url("mesa_status/cierre_mesa_candidatos?numero_mesa=$item->numero_mesa&cv_id=$ubch->id") ?>">
                                    <?php echo "Mesa Numero " . $item->numero_mesa ?>
                                  </a>
                                <?php endforeach; ?>
                              </div>
                            </div>
                          </div>
                        </form>
                      <?php else : ?>
                        <div class="alert alert-danger" role="alert">
                          No hay mesas constituidas
                        </div>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              <?php endif ?>
            <?php endif ?>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>



<?php
$this->load->view("template/footer.php");
?>