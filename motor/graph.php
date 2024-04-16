<?php
    //a este php solo es necesario pasarle el numero de meses deseados
    
    $GLOBALS["required_values_graph"] = array("year","month","month_name","purchase_price","sale_price","medium_price");
    $GLOBALS["required_values_sales"] = array("sale_price_variation","sale_price_percentage_variation","purchase_price_variation","purchase_price_percentage_variation","medium_price_variation","medium_price_percentage_variation","km_minimum","km_maximum","km_average");
    $required_months = $_GET['required_months'] ?? null;

    //se modifica el ultimo valor del url el cual corresponde al numero de meses que se desean seleccionar
    if($required_months != null){
        $url= substr($_SESSION['finalUrl'], 0, -1) . $required_months; //cuando se desee trabajar con otro filtro 
    }

    else{
        $url = $_SESSION['finalUrl']; //la url predeterminada trabaja con un filtro de 3 meses
    }

    get_graph_info($url);

    function get_graph_info($url){
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
            $filter_data = json_decode($response, true);
            $j = 1;

            //datos para generar las tablas
            foreach ($filter_data["historic"] as $data) {
                for ($i = 0; $i < (count($GLOBALS ["required_values_graph"])); $i++){
                    $_SESSION['graph_info']["mes".$j][$GLOBALS["required_values_graph"][$i]] = $data[$GLOBALS["required_values_graph"][$i]];
                }   

                $j = $j + 1;
            }

            //datos para generar los datos del panel
            for ($i = 0; $i < (count($GLOBALS ["required_values_sales"])); $i++){
                $_SESSION['sales_info'][$GLOBALS["required_values_sales"][$i]] = $filter_data[$GLOBALS["required_values_sales"][$i]];
            }
        }
    }

    //_________________________________________________________________________________________________________

    //lo que está a continuación es un ejemplo de como seleccionar cada uno de los 
    //datos que se necesitan para la pagina de los resultados
        
    for ($i = 1; $i < (count($_SESSION ["graph_info"])); $i++){
        for ($j = 0; $j < (count($GLOBALS ["required_values_graph"])); $j++){
            echo $_SESSION['graph_info']["mes".$i][$GLOBALS["required_values_graph"][$j]];
        }   
    }

    for ($i = 0; $i < (count($_SESSION ["sales_info"])); $i++){
        echo $_SESSION['sales_info'][$GLOBALS["required_values_sales"][$i]];
    } 


    $j = 0;
    for ($i = 0; $i < (count($_SESSION ["specified_filts"])); $i++){

        //para obtener marca, modelo, año y version
        if ($i < count($_SESSION ["ids_form"])){
            echo $_SESSION['specified_filts'][$_SESSION['ids_form'][$i]];
        }

        //para obtener color y kilometraje
        else{
            echo $_SESSION['specified_filts'][$_SESSION['ids_form_nonapi'][$j]];
            $j = $j +1;
        }
    } 

?>
