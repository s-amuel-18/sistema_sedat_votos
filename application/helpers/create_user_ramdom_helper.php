<?php 
    function create_user_random($data) {
        echo '
        <table class="table table-light">
        <thead class="thead-light">
          <tr>
            <th>Identificador</th>
            <th>Centro de votacion</th>
            <th>Usuario</th>
            <th>Contraseña</th>
          </tr>
        </thead>
        <tbody>
        ';


        
        for ($i=0; $i < count($data); $i++) { 
            $data_user[$i]["username"] = "admin_".($data[$i]->id);
            $data_user[$i]["rol"] = "usuario";
            $pass = "admin@".($data[$i]->id * 5);
            $data_user[$i]["password"] = md5($pass);

            echo "
            <tr>
                <td>{$data[$i]->id}</td>
                <td>{$data[$i]->NOMBRE_INSTITUCIONES_CON_CODIGO}</td>
                <td>{$data_user[$i]["username"]}</td>
                <td>{$pass}</td>
            </tr>
            ";
            
 
        }
     
        echo '
        </tbody>
        </table>
        ';

        return $data_user;
    }