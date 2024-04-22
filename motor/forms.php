
        <script type = "text/JavaScript"> 
            function change_option(filter, id, default_op){
                for (var i = 0; i < filter.length; i++) {
                    var filter_select= document.getElementById(id);
                    filter_select.disabled = false;

                    var new_option= document.createElement('option');
                    new_option.value=filter[i];
                    new_option.text= filter[i];
                    filter_select.add(new_option);
                }
                
                if(default_op != "0"){
                    defaultv(id, default_op);
                }
            }

            function defaultv(id, default_op){
                var filter_select = document.getElementById(id);
                filter_select.value = default_op;
                filter_select.disabled = true;
            }

            function enable_rest_filters(id_filters){
                for (var i = 0; i < id_filters.length; i++){
                    var filter_select1= document.getElementById(id_filters[i]);
                    filter_select1.disabled = false;
                }
            }

        </script>

<?php

    if(!isset($_SESSION)) { 
        session_start(); 
    } 

    reload(0);
    
    //___________________________________________________________________________________________________________
    $api_format = $_GET['api_format'] ?? null;
    $wanted_values = $_GET['wanted_values'] ?? null;
    $selectedFilter = $_GET['selectedFilter'] ?? null;

    if ($selectedFilter==null && $wanted_values == null && $selectedFilter == null) form();

    else if ($wanted_values == null) {
        $filts = explode(",", $selectedFilter);
        $keys = explode(",", $api_format);
        
        for ($i = 0; $i <(count($filts)); $i++){
            $_SESSION['specified_filts'][$keys[$i]] = $filts[$i];
        }

        include("graph.php");
    }

    else{
        select_filter($selectedFilter,$api_format,$wanted_values);
    }

    //___________________________________________________________________________________________________________
    
    function enable($ids_form_nonapi){

        $ids_form_nonapi = json_encode($ids_form_nonapi);
        echo "<script type='text/javascript'>enable_rest_filters(".$ids_form_nonapi.");</script>";
    }
    
    function reload($option){
        $is_page_refreshed = (isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] == 'max-age=0');
    
        if($is_page_refreshed) {
            session_destroy();
            echo"
            <script>
            url = 'http://localhost/motor/start.php';
            location.href=url;
            </script>";
        } 

        if ($option == 1){
            session_destroy();
            echo"
            <script>
            url = 'http://localhost/motor/start.php';
            location.href=url;
            </script>";
        } 
    }

    function select_filter($filters, $api_format, $atribute){
        $ids = array();
        $api_format = explode(",", $api_format);

        for ($i = 0; $i <(count($api_format)); $i++){
            if ((($i+1) % 2) == 0){
                $id_filter = $api_format[$i];

                if (!array_key_exists($id_filter, $_SESSION['specified_filts'])) {
                    $_SESSION['specified_filts'][$id_filter] = $filters; //añadir filtro elegido al dict
                } 

                $spec_filt = $_SESSION['specified_filts'][$id_filter];
                $ids[] =  $_SESSION["id_".$id_filter][$spec_filt]; //aqui se guardan los ids que se van a ocupar para el query
            }
        }

        $url = "https://motorleads-api-d3e1b9991ce6.herokuapp.com/api/v1";
        $j = 0;

        for ($i = 0; $i < (count($api_format)); $i++){
            if ((($i+1) % 2) != 0){
                $url = $url."/".$api_format[$i];
            }

            else{
                $url = $url."/".$ids[$j];
                $j = $j + 1;
            }
        }

        if($_SESSION['iterate'] + 1 >= count($_SESSION['ids_form'])){
            $_SESSION['finalUrl'] = $url;
            call_forms();
            update();
            enable($_SESSION['ids_form_nonapi']); //se terminó de checar filtros que dependen de la api
        }

        else{
            $next_filt =  $_SESSION['ids_form'][$_SESSION['iterate'] + 1]; 
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

                if (empty($filter_data)) {
                    echo "<script type='text/javascript'> alert('Error: no se posee una versión del vehículo seleccionado.');</script>";
                    reload(1);
                }

                else{
                    
                    foreach ($filter_data as $data) {
                        //añadir nombre de marca/modelo/anio/version a la lista correspondiente
                        $_SESSION[$next_filt][] = $data[$atribute]; 
                        //añadir ids de marca/modelo/anio/version al dict correspondiente
                        $_SESSION['id_'.$next_filt][$data[$atribute]] = $data["id"]; 
                    }
    
                    $_SESSION['iterate'] = $_SESSION['iterate'] + 1;
                    call_forms();
                    update();
                }
            }
        }
    }

    function call_forms(){
        include("forms.html");
    }

    function form(){  
        add_select($_SESSION["marca"], "marca", 0);
    }

    function add_select($filter, $id, $default_op){
        $filter = json_encode($filter);
        echo "<script type='text/javascript'>change_option(".$filter.",'".$id."', '".$default_op."');</script>";

    }

    function update(){
        $filters = $_SESSION['specified_filts']; 

        for ($i = 0; $i < ((count($filters))+1); $i++){  

            if($i<count($filters)){
                $id = $_SESSION['ids_form'][$i];
                add_select($_SESSION[$id],$id, $filters[$id]);
            }
            else if (count($filters) ==  count($_SESSION['ids_form'])){
                break;
            }

            else{
                $id = $_SESSION['ids_form'][$i];
                add_select($_SESSION[$id],$id, 0);
            }
        }
    }
?>

</body>
</html>

