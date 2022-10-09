<?php
$this->load->view("template/head.php");
?>

<!-- navbar -->
<?php $this->load->view("components/navbar"); ?>
<!-- endnavbar -->

<div style="background-color: #ececec;">
    <div class="container">
        <form onsubmit="return envioForm()" action="<?php echo $url_form ?>" method="POST" class="py-3" id="form_cierre_mesa">
            <table class="table table-light">
                <thead class="thead-light">
                    <tr>
                        <th>Cargo</th>
                        <th>Partido</th>
                        <th>Nombre y Apellido</th>
                        <th>Totalizacion</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody id="table_cand">

                    <?php foreach ($candidatos_data as $i => $item) : ?>
                        <?php if ($i == 0) {
                            $cargo = $item->cargo;
                        } ?>

                        <?php if ($cargo != $item->cargo  /*($i + 1) == 3 or ($i + 1) == 6*/) : ?>

                            <tr class="bg-light">
                                <td><?php echo "ALCALDE" ?></td>
                                <td>TOTAL</td>
                                <td></td>
                                <td><input onkeyup="innerValueOtros('<?php echo 'ALCALDE' ?>')" class="form-control total_votos <?php echo 'ALCALDE' ?>" type="text" min="1" value="<?php echo isset($total_votos) ? $total_votos['ALCALDE'] : "" ?>"></td>
                                <td></td>
                            </tr>
                        <?php endif;
                        $cargo = $item->cargo;
                        ?>

                        <tr>
                            <td><?php echo $item->cargo ?></td>
                            <td><?php echo $item->partido ?></td>
                            <td><?php echo $item->nombre_y_apellido ?></td>
                            <td><input value="<?php echo isset($item->votos) ? $item->votos : "" ?>" onkeyup="innerValueOtros('<?php echo $item->cargo ?>')" <?php echo $item->partido == "OTROS" ? "readonly " : "" ?> class="<?php echo $item->cargo ?> <?php echo $item->partido == "OTROS" ? "otros_input" : "input_candidato" ?> form-control" type="text" min="1" name="<?php echo $item->id ?>"></td>
                            <td></td>
                        </tr>



                        <?php if ($i == (count($candidatos_data) -1)  /*($i + 1) == 3 or ($i + 1) == 6*/) : ?>

                            <tr class="bg-light">
                                <td><?php echo $item->cargo ?></td>
                                <td>TOTAL</td>
                                <td></td>
                                <td><input onkeyup="innerValueOtros('<?php echo $item->cargo ?>')" class="form-control total_votos <?php echo $item->cargo ?>" type="text" min="1" value="<?php echo isset($total_votos) ? $total_votos[$item->cargo] : "" ?>"></td>
                                <td></td>
                            </tr>
                        <?php endif;
                        ?>

                    <?php endforeach ?>
                </tbody>
            </table>
            <button class="btn btn-primary" type="submit">Registrar</button>
        </form>
    </div>
</div>




<script>
    const table_cand = document.querySelectorAll("#table_cand input");

    for (let i = 0; i < table_cand.length; i++) {
        table_cand[i].addEventListener("keypress", soloNumeros);
    }

    console.log(table_cand);

    function soloNumeros(e) {
        var key = window.event ? e.which : e.keyCode;
        if (key < 48 || key > 57) {
            e.preventDefault();
        }
    }

    function validLength(cargo) {
        const allInputsCandidatosAlcalde = document.querySelectorAll(".input_candidato." + cargo)
        let sumaALlCandidatos = 0;
        let totalVotos = document.querySelector(".total_votos." + cargo)
        totalVotos = Number(totalVotos.value.length == 0 ? 0 : totalVotos.value)

        Array.from(allInputsCandidatosAlcalde).forEach(el => {
            let num = el.value.length == 0 ? 0 : el.value
            num = Number(num)
            sumaALlCandidatos += num
        });

        let mensg = "deseas Cerrar esta mesa"

        if (totalVotos < sumaALlCandidatos) {
            alert("La cantidad total no puede ser menor que la suma de los candidatos");
            return false
        } else {
            return true
        }

    }

    function envioForm() {
        const alcalde = validLength("ALCALDE");
        const gobernador = validLength("GOBERNADOR");

        let mensg = "deseas Cerrar esta mesa"


        if (!alcalde || !gobernador) {
            return false
        }

        if (confirm(mensg)) {
            return true
        }

        return false
    }

    function innerValueOtros(cargo) {
        const allInputsCandidatos = document.querySelectorAll(".input_candidato." + cargo)
        let sumaALlCandidatos = 0;
        let totalVotos = document.querySelector(".total_votos." + cargo)
        totalVotos = Number(totalVotos.value.length == 0 ? 0 : totalVotos.value)

        Array.from(allInputsCandidatos).forEach(el => {
            let num = el.value.length == 0 ? 0 : el.value
            num = Number(num)
            sumaALlCandidatos += num
        });


        let otros_input = document.querySelector(".otros_input." + cargo)
        // console.log(otros_input) 
        // return false
        if (totalVotos < sumaALlCandidatos) {
            otros_input.value = 0
        } else {

            let result_otros = totalVotos - sumaALlCandidatos
            otros_input.value = result_otros;

        }


    }
</script>

<?php
$this->load->view("template/footer.php");
?>