<h2>Reporte Alcalde</h2>
<br>


    <table id="estadisticas_cand1" class="table table-light">
        <thead class="thead-light">
            <tr>
                <th>Numero mesa</th>
                <th>Institucion</th>
                <th>Parroquia</th>
    
                <th>Total participación</th>
                <!-- <th>Total Electores</th> -->
    
                <th>JOSE RANGEL</th>
                <th>% JOSE RANGEL</th>
                <th>ROSIRIS TORO</th>
                <th>% ROSIRIS TORO</th>
                <th>ANDRES SCHLOETER</th>
                <th>% ANDRES SCHLOETER</th>
                <th>NULO ALCALDE</th>
                <th>% NULO ALCALDE</th>
                <th>OTROS ALCALDES</th>
                <th>% OTROS ALCALDES</th>
            </tr>
        </thead>
        <tbody id="tbody_estadisticas">
    
    
            <?php
    
            // $POBLACION_VOTANTE = 0;
            $TOTAL_GOBERNADOR = 0;
            // $total_general = 0;
            $TOTAL_ALCALDE = 0;
            $JOSE_RANGEL = 0;
            $ANDRES_SCHLOETER = 0;
            $ROSIRIS_TORO = 0;
            $OTROS_ALCALDES = 0;
            $NULO_ALCALDE = 0;
    
    
            foreach ($candidatos_estadistica as $value) :
                // $POBLACION_VOTANTE += $value->POBLACION_VOTANTE;
                $TOTAL_GOBERNADOR += $value->TOTAL_GOBERNADOR;
                // $total_general += $value->TOTAL_GENERAL;
                $TOTAL_ALCALDE += $value->TOTAL_ALCALDE;
                $JOSE_RANGEL += $value->JOSE_RANGEL;
                $ANDRES_SCHLOETER += $value->ANDRES_SCHLOETER;
                $ROSIRIS_TORO += $value->ROSIRIS_TORO;
                $OTROS_ALCALDES += $value->OTROS_ALCALDES;
                $NULO_ALCALDE += $value->NULO_ALCALDE;
    
            ?>
                <tr>
                    <td><?php echo $value->numero_mesa ?></td>
                    <td><?php echo $value->NOMBRE_INSTITUCIONES ?></td>
                    <td><?php echo $value->PARROQUIA ?></td>
    
                    <td><?php echo $value->TOTAL_GOBERNADOR ?></td>
                    <!-- <td><?php /* echo $value->POBLACION_VOTANTE  */?></td> -->
    
                    <td><?php echo $value->JOSE_RANGEL ?></td>
                    <td><?php echo number_format(media_aritmetica($value->TOTAL_GOBERNADOR, $value->JOSE_RANGEL), 2) ?>%</td>
                    <td><?php echo $value->ROSIRIS_TORO ?></td>
                    <td><?php echo number_format(media_aritmetica($value->TOTAL_GOBERNADOR, $value->ROSIRIS_TORO), 2) ?>%</td>
                    <td><?php echo $value->ANDRES_SCHLOETER ?></td>
                    <td><?php echo number_format(media_aritmetica($value->TOTAL_GOBERNADOR, $value->ANDRES_SCHLOETER), 2) ?>%</td>
                    <td><?php echo $value->NULO_ALCALDE ?></td>
                    <td><?php echo number_format(media_aritmetica($value->TOTAL_GOBERNADOR, $value->NULO_ALCALDE), 2) ?>%</td>
                    <td><?php echo $value->OTROS_ALCALDES ?></td>
                    <td><?php echo number_format(media_aritmetica($value->TOTAL_GOBERNADOR, $value->OTROS_ALCALDES), 2) ?>%</td>
                </tr>
            <?php endforeach ?>
    
            <tr>
                <td>TOTAL</th>
                <td></td>
                <td></td>
                <td><?php echo $TOTAL_GOBERNADOR ?></td>
                <!-- <td><?php /* echo $POBLACION_VOTANTE */ ?></td> -->
    
    
    
                <td><?php echo $JOSE_RANGEL ?></td>
                <td><?php echo number_format(media_aritmetica($TOTAL_GOBERNADOR, $JOSE_RANGEL), 2) ?></td>
                <td><?php echo $ROSIRIS_TORO ?></td>
                <td><?php echo number_format(media_aritmetica($TOTAL_GOBERNADOR, $ROSIRIS_TORO), 2) ?></td>
                <td><?php echo $ANDRES_SCHLOETER ?></td>
                <td><?php echo number_format(media_aritmetica($TOTAL_GOBERNADOR, $ANDRES_SCHLOETER), 2) ?></td>
                <td><?php echo $NULO_ALCALDE ?></td>
                <td><?php echo number_format(media_aritmetica($TOTAL_GOBERNADOR, $NULO_ALCALDE), 2) ?></td>
                <td><?php echo $OTROS_ALCALDES ?></td>
                <td><?php echo number_format(media_aritmetica($TOTAL_GOBERNADOR, $OTROS_ALCALDES), 2) ?></td>
            </tr>
    
        </tbody>
    </table>


<br>
<h2>Reporte Gobernador</h2>
<br>
<table id="estadisticas_cand2" class="table table-light">
    <thead class="thead-light">
        <tr>
            <th>Numero mesa</th>
            <th>Institucion</th>
            <th>Parroquia</th>
            <th>Total participación</th>
            <!-- <th>Total Electores</th> -->

            <th>HECTOR RODRIGUEZ</th>
            <th>% HECTOR RODRIGUEZ</th>
            <th>DAVID UZCATEGUI</th>
            <th>% DAVID UZCATEGUI</th>
            <th>NULO GOBERNADOR</th>
            <th>% NULO GOBERNADOR</th>
            <th>OTROS GOBERNADORES</th>
            <th>% OTROS GOBERNADORES</th>
        </tr>
    </thead>
    <tbody id="tbody_estadisticas">


        <?php


        // $POBLACION_VOTANTE = 0;
        $TOTAL_GOBERNADOR = 0;
        $HECTOR_RODRIGUEZ = 0;
        $DAVID_UZCATEGUI = 0;
        $OTROS_GOBERNADORES = 0;
        $NULO_GOBERNADOR = 0;


        foreach ($candidatos_estadistica as $value) :

            $HECTOR_RODRIGUEZ += $value->HECTOR_RODRIGUEZ;
            $DAVID_UZCATEGUI += $value->DAVID_UZCATEGUI;
            $OTROS_GOBERNADORES += $value->OTROS_GOBERNADORES;
            // $POBLACION_VOTANTE += $value->POBLACION_VOTANTE;
            $TOTAL_GOBERNADOR += $value->TOTAL_GOBERNADOR;
            $NULO_GOBERNADOR += $value->NULO_GOBERNADOR;
        ?>
            <tr>
                <td><?php echo $value->numero_mesa ?></td>
                <td><?php echo $value->NOMBRE_INSTITUCIONES ?></td>
                <td><?php echo $value->PARROQUIA ?></td>
                <td><?php echo $value->TOTAL_GOBERNADOR ?></td>
                <!-- <td><?php /* echo $value->POBLACION_VOTANTE */ ?></td> -->

                <td><?php echo $value->HECTOR_RODRIGUEZ ?></td>
                <td><?php echo number_format(media_aritmetica($value->TOTAL_GOBERNADOR, $value->HECTOR_RODRIGUEZ), 2) ?>%</td>
                <td><?php echo $value->DAVID_UZCATEGUI ?></td>
                <td><?php echo number_format(media_aritmetica($value->TOTAL_GOBERNADOR, $value->DAVID_UZCATEGUI), 2) ?>%</td>
                <td><?php echo $value->NULO_GOBERNADOR ?></td>
                <td><?php echo number_format(media_aritmetica($value->TOTAL_GOBERNADOR, $value->NULO_GOBERNADOR), 2) ?>%</td>
                <td><?php echo $value->OTROS_GOBERNADORES ?></td>
                <td><?php echo number_format(media_aritmetica($value->TOTAL_GOBERNADOR, $value->OTROS_GOBERNADORES), 2) ?>%</td>
            </tr>
        <?php endforeach ?>

        <tr>
            <td>TOTAL</th>
            <td></td>
            <td></td>
            <td><?php echo $TOTAL_GOBERNADOR ?></td>
            <!-- <td><?php /* echo $POBLACION_VOTANTE */ ?></td> -->



            <td><?php echo $HECTOR_RODRIGUEZ ?></td>
            <td><?php echo number_format(media_aritmetica($TOTAL_GOBERNADOR, $HECTOR_RODRIGUEZ), 2) ?></td>
            <td><?php echo $DAVID_UZCATEGUI ?></td>
            <td><?php echo number_format(media_aritmetica($TOTAL_GOBERNADOR, $DAVID_UZCATEGUI), 2) ?></td>
            <td><?php echo $NULO_GOBERNADOR ?></td>
            <td><?php echo number_format(media_aritmetica($TOTAL_GOBERNADOR, $NULO_GOBERNADOR), 2) ?></td>
            <td><?php echo $OTROS_GOBERNADORES ?></td>
            <td><?php echo number_format(media_aritmetica($TOTAL_GOBERNADOR, $OTROS_GOBERNADORES), 2) ?></td>
        </tr>

    </tbody>
</table>