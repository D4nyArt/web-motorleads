<?php 
    session_start();
    $_SESSION['ids_form_nonapi'] = array('kilometraje', 'color');
    $_SESSION['id_marca'] = array();
    $_SESSION['marca'] = array();
    $_SESSION['id_modelo'] = array();
    $_SESSION['modelo'] = array();
    $_SESSION['id_anio'] = array();
    $_SESSION['anio'] = array();
    $_SESSION['id_version'] = array();
    $_SESSION['version'] = array();
    $_SESSION['ids_form'] = array('marca', 'modelo', 'anio', 'version');
    $_SESSION['iterate'] = 0;
    $_SESSION['specified_filts'] = array();

    $_SESSION['finalUrl'] = null;
    $_SESSION['graph_info'] = array(array());
    $_SESSION['sales_info'] = array();
    start();

    function start(){
        get_marca();
        include("forms.html");
        include("forms.php");
    }

    function get_marca(){
        $url = "https://motorleads-api-d3e1b9991ce6.herokuapp.com/api/v1/makes";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);

        if(curl_errno($curl)){
            $error_msg =curl_error($curl);
            echo"Error al conectarse a la API";
        }

        else{
            curl_close($curl);
            $marcas_data = json_decode($response, true);

            foreach ($marcas_data as $data) {
                $_SESSION['marca'][] = $data["name"];
                $_SESSION['id_marca'][$data["name"]] = $data["id"];
            }
        }
    }

?>

