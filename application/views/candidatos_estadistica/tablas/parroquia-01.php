<table class="table table-light">
    <thead class="thead-light">
        <tr>
            <th>parroquia</th>
            <th>total general</th>
            <th>total gobernador</th>
            <th>% total gobernador</th>
            <th>total alcalde</th>
            <th>% total alcalde</th>
            <th>HECTOR RODRIGUEZ</th>
            <th>% HECTOR RODRIGUEZ</th>
            <th>DAVID UZCATEGUI</th>
            <th>% DAVID UZCATEGUI</th>
            <th>OTROS GOBERNADORES</th>
            <th>% OTROS GOBERNADORES</th>
            <th>JOSE RANGEL</th>
            <th>% JOSE RANGEL</th>
            <th>ANDRES SCHLOETER</th>
            <th>% ANDRES SCHLOETER</th>
            <th>ROSIRIS TORO</th>
            <th>% ROSIRIS TORO</th>
            <th>OTROS ALCALDES</th>
            <th>% OTROS ALCALDES</th>
            <!-- <th>Poblacion votante</th> -->
        </tr>
    </thead>
    <tbody id="tbody_estadisticas">


        <?php

        $total_general = 0;
        $TOTAL_GOBERNADOR = 0;
        $TOTAL_ALCALDE = 0;
        $HECTOR_RODRIGUEZ = 0;
        $DAVID_UZCATEGUI = 0;
        $OTROS_GOBERNADORES = 0;
        $JOSE_RANGEL = 0;
        $ANDRES_SCHLOETER = 0;
        $ROSIRIS_TORO = 0;
        $OTROS_ALCALDES = 0;
        // $poblacion_votante = 0;

        foreach ($candidatos_estadistica as $value) :
            // $parroquia = 0;
            $total_general += $value->total_general; 
            $TOTAL_GOBERNADOR += $value->TOTAL_GOBERNADOR; 
            $TOTAL_ALCALDE += $value->TOTAL_ALCALDE; 
            $HECTOR_RODRIGUEZ += $value->HECTOR_RODRIGUEZ; 
            $DAVID_UZCATEGUI += $value->DAVID_UZCATEGUI; 
            $OTROS_GOBERNADORES += $value->OTROS_GOBERNADORES; 
            $JOSE_RANGEL += $value->JOSE_RANGEL; 
            $ANDRES_SCHLOETER += $value->ANDRES_SCHLOETER; 
            $ROSIRIS_TORO += $value->ROSIRIS_TORO; 
            $OTROS_ALCALDES += $value->OTROS_ALCALDES; 
            // $poblacion_votante += $value->poblacion_votante; 

        ?>
            <tr>

                <td><?php echo $value->parroquia ?></td>
                <td><?php echo $value->total_general ?></td>
                <td><?php echo $value->TOTAL_GOBERNADOR ?></td>
                <td><?php echo number_format(media_aritmetica($value->total_general, $value->TOTAL_GOBERNADOR), 2) ?>%</td>
                <td><?php echo $value->TOTAL_ALCALDE ?></td>
                <td><?php echo number_format(media_aritmetica($value->total_general, $value->TOTAL_ALCALDE), 2) ?>%</td>
                <td><?php echo $value->HECTOR_RODRIGUEZ ?></td>
                <td><?php echo number_format(media_aritmetica($value->TOTAL_GOBERNADOR, $value->HECTOR_RODRIGUEZ), 2) ?>%</td>
                <td><?php echo $value->DAVID_UZCATEGUI ?></td>
                <td><?php echo number_format(media_aritmetica($value->TOTAL_GOBERNADOR, $value->DAVID_UZCATEGUI), 2) ?>%</td>
                <td><?php echo $value->OTROS_GOBERNADORES ?></td>
                <td><?php echo number_format(media_aritmetica($value->TOTAL_GOBERNADOR, $value->OTROS_GOBERNADORES), 2) ?>%</td>
                <td><?php echo $value->JOSE_RANGEL ?></td>
                <td><?php echo number_format(media_aritmetica($value->TOTAL_ALCALDE, $value->JOSE_RANGEL), 2) ?>%</td>
                <td><?php echo $value->ANDRES_SCHLOETER ?></td>
                <td><?php echo number_format(media_aritmetica($value->TOTAL_ALCALDE, $value->ANDRES_SCHLOETER), 2) ?>%</td>
                <td><?php echo $value->ROSIRIS_TORO ?></td>
                <td><?php echo number_format(media_aritmetica($value->TOTAL_ALCALDE, $value->ROSIRIS_TORO), 2) ?>%</td>
                <td><?php echo $value->OTROS_ALCALDES ?></td>
                <td><?php echo number_format(media_aritmetica($value->TOTAL_ALCALDE, $value->OTROS_ALCALDES), 2) ?>%</td>
            </tr>
        <?php endforeach ?>

        <tr>
            <td>TOTAL</td>
        <td><?php echo $total_general ?></td>
        <td><?php echo $TOTAL_GOBERNADOR ?></td>
        <td><?php echo number_format(media_aritmetica($total_general,
$TOTAL_GOBERNADOR), 2) ?>%</td>
        <td><?php echo $TOTAL_ALCALDE ?></td>
        <td><?php echo number_format(media_aritmetica($total_general,
$TOTAL_ALCALDE), 2) ?>%</td>
        <td><?php echo $HECTOR_RODRIGUEZ ?></td>
        <td><?php echo number_format(media_aritmetica($TOTAL_GOBERNADOR, $HECTOR_RODRIGUEZ), 2)?>%</td>
        <td><?php echo $DAVID_UZCATEGUI ?></td>
        <td><?php echo number_format(media_aritmetica($TOTAL_GOBERNADOR, $DAVID_UZCATEGUI), 2)?>%</td>
        <td><?php echo $OTROS_GOBERNADORES ?></td>
        <td><?php echo number_format(media_aritmetica($TOTAL_GOBERNADOR, $OTROS_GOBERNADORES), 2)?>%</td>
        <td><?php echo $JOSE_RANGEL ?></td>
        <td><?php echo number_format(media_aritmetica($TOTAL_ALCALDE, $JOSE_RANGEL), 2)?>%</td>
        <td><?php echo $ANDRES_SCHLOETER ?></td>
        <td><?php echo number_format(media_aritmetica($TOTAL_ALCALDE, $ANDRES_SCHLOETER), 2)?>%</td>
        <td><?php echo $ROSIRIS_TORO ?></td>
        <td><?php echo number_format(media_aritmetica($TOTAL_ALCALDE, $ROSIRIS_TORO), 2)?>%</td>
        <td><?php echo $OTROS_ALCALDES ?></td>
        <td><?php echo number_format(media_aritmetica($TOTAL_ALCALDE, $OTROS_ALCALDES), 2)?>%</td>
            <!-- <th>parroquia</th>
            <th>total general</th>
            <th>total gobernador</th>
            <th>total alcalde</th>
            <th>HECTOR RODRIGUEZ</th>
            <th>% HECTOR RODRIGUEZ</th>
            <th>DAVID UZCATEGUI</th>
            <th>% DAVID UZCATEGUI</th>
            <th>OTROS GOBERNADORES</th>
            <th>% OTROS GOBERNADORES</th>
            <th>JOSE RANGEL</th>
            <th>% JOSE RANGEL</th>
            <th>ANDRES SCHLOETER</th>
            <th>% ANDRES SCHLOETER</th>
            <th>ROSIRIS TORO</th>
            <th>% ROSIRIS TORO</th>
            <th>OTROS ALCALDES</th>
            <th>% OTROS ALCALDES</th> -->


            <td></td>
        </tr>

    </tbody>
</table>