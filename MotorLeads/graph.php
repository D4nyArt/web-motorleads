<?php

    //Inicio de sesion en la pagina, necesario para el funcionamiento de la pagina.
    session_start();

    if ($_SESSION['specified_filts'] == []){
        header('Location:forms.php');
    } 


    /*
    A este php solo es necesario pasarle el numero de meses deseados, se guardan todos los valores (estructura)
    necesarios para la construcción de la grafica y los valores que se muestran en la pagina
    en variables globales para permitir el acceso en cualquiermomento de la ejecucioón del programa.
    */
    
    $GLOBALS["required_values_graph"] = array("year","month","month_name","purchase_price","sale_price","medium_price");
    $GLOBALS["required_values_sales"] = array("sale_price_variation","sale_price_percentage_variation","purchase_price_variation","purchase_price_percentage_variation","medium_price_variation","medium_price_percentage_variation","km_minimum","km_maximum","km_average");
    $required_months = $_GET['required_months'] ?? null;
    $month_change = 0;

    //se modifica el ultimo valor del url el cual corresponde al numero de meses que se desean seleccionar
    if($required_months != null){
        $url= substr($_SESSION['finalUrl'], 0, -1) . $required_months; //cuando se desee trabajar con otro filtro 
        $month_change = $required_months;
    }

    else{
        $url = $_SESSION['finalUrl']; //la url predeterminada trabaja con un filtro de 3 meses
        $month_change = 3;
    }
    
    //echo $url; Verificar la url


    //Se manda llamar a la funcion get_graph_info()
    get_graph_info($url);

    /*
    La funcion get_graph_info() es la encargada de conectarse con la API y obtener los valores necesarios
    para la construccion de la pagina mostrada en graph.php, la funcion recible la url actual que contiene todos
    los filtros seleccionados en el apartado de forms el resultado de la consulta a la API guarda los valores 
    utiles para la graficación en las variables $_SESSION['graph_info'] y $_SESSION['sales_info']

    */
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

    //___________________________________

    //lo que está a continuación es un ejemplo de como seleccionar cada uno de los 
    //datos que se necesitan para la pagina de los resultados
        
    /*for ($i = 1; $i < (count($_SESSION ["graph_info"])); $i++){
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
    }*/

    /*
    La funcion send_graph_info() crea un arrreglo en donde se almacena la informacion que se mandara 
    a las funciones que crearan las graficas y muestran la la informacion, los datos se almacenan
    en un header con la información codificada para poder ser recuperada por las demas funciones con
    nombre de 'X-GraphData'
    */
    function send_graph_info(){
    $graphData = array(); // Initialize an empty array

    for ($i = 1; $i < count($_SESSION["graph_info"]); $i++) {
        $rowData = array();  // Create an array for each row of data
        for ($j = 0; $j < count($GLOBALS["required_values_graph"]); $j++) {
            $rowData[$GLOBALS["required_values_graph"][$j]] = $_SESSION['graph_info']["mes" . $i][$GLOBALS["required_values_graph"][$j]];
        }
        $graphData[] = $rowData;  // Add the row of data to the main array
    }

    $jsonData = json_encode($graphData); // Encode the array as JSON
    header('X-GraphData: ' . $jsonData);
    }

    /*
    La funcion create_general_info_table($month_change) se encarga de recuperar los datos de 
    $_SESSION['specified_filts'], $_SESSION['graph_info'] y ['sales_info'] dentro de estos arreglos
    se encuentra toda la información necesaria para el despliegue de los datos del auto consultado. 
    Las variables que se recuperan son :
        marca, modelo, anio, longVersion, arrLongVersion, shortVersion, finalVersion, kilometraje y color

        compra, venta y medio

        cambio_compra, cambio_compra_porc, cambio_venta, cambio_venta_porc, cambio_medio ,cambio_medio_porc

        valor_km, km_max, km_min, graph_max, graph_min, graph_mean
    */
    function create_general_info_table($month_change){
        $marca = $_SESSION['specified_filts'][$_SESSION['ids_form'][0]];
        $modelo = $_SESSION['specified_filts'][$_SESSION['ids_form'][1]];
        $anio = $_SESSION['specified_filts'][$_SESSION['ids_form'][2]];
        $longVersion = $_SESSION['specified_filts'][$_SESSION['ids_form'][3]];
        $arrLongVersion = explode(',', $longVersion);
        $shortVersion = $arrLongVersion[0];
        $arrShortVersion = explode('.', $shortVersion);
        $finalVersion = $arrShortVersion[1];
        $kilometraje = $_SESSION['specified_filts'][$_SESSION['ids_form_nonapi'][0]];
        $color = $_SESSION['specified_filts'][$_SESSION['ids_form_nonapi'][1]];

        $compra = $_SESSION['graph_info']["mes1"][$GLOBALS["required_values_graph"][3]];;
        $venta = $_SESSION['graph_info']["mes1"][$GLOBALS["required_values_graph"][4]];
        $medio = $_SESSION['graph_info']["mes1"][$GLOBALS["required_values_graph"][5]];

        $cambio_compra = $_SESSION['sales_info'][$GLOBALS["required_values_sales"][0]];
        $cambio_compra_porc = $_SESSION['sales_info'][$GLOBALS["required_values_sales"][1]];
        $cambio_venta = $_SESSION['sales_info'][$GLOBALS["required_values_sales"][2]];
        $cambio_venta_porc = $_SESSION['sales_info'][$GLOBALS["required_values_sales"][3]];
        $cambio_medio = $_SESSION['sales_info'][$GLOBALS["required_values_sales"][4]];
        $cambio_medio_porc = $_SESSION['sales_info'][$GLOBALS["required_values_sales"][5]];

        $valor_km= $_SESSION['specified_filts'][$_SESSION['ids_form_nonapi'][0]];
        $km_max= $_SESSION['sales_info']['km_maximum'];
        $km_min= $_SESSION['sales_info']['km_minimum'];
        $graph_max= $km_max;
        $graph_min= $km_min;
        $graph_mean= $_SESSION['sales_info']['km_average'];

        if($valor_km <= $km_min){
            $graph_min= $valor_km-($valor_km * .8);
        }
        if($valor_km >= $km_max){
            $graph_max= $valor_km+($valor_km * .05);
        }


        /*
        Creacion del html para presentar los valores recuperados 
        Se efectuan dentro de este HTML todos los encabezados, carga de imágenes,
        y de los datos para su visualización en pantalla
        
        Posteriormente se realiza toda la búsqueda de diccionarios para la
        construcción de las gráficas, tanto de los precios como del kilometraje

        Todo se construye dentro de dos contenedores con varias tablas que se
        encargan de organizar y construir todos los datos de manera gráfica
        */
        echo "
        <html>
        <head>
            <title>Motor Leads</title>
            <link href='graph.css' rel='stylesheet' type='text/css'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <link href='assets\copa_logo.png' rel='shortcut icon' type='image/jpg'>
            <meta charset='utf-8'>

            <style>
                body {
                    font-family: sans-serif;
                }   

                table {
                    border-spacing: 10px; 
                }

                tr {
                    text-align: center;
                }

            </style>
        </head>

        <body class = 'background'>
            <header>
                <div class='header-image'>
                    <a href='http://localhost/MotorLeads/login.php?email=".$_SESSION['email']."&contrasena=". $_SESSION['contrasena']."'><img src='assets\copa_logo.png' width= '100' height='50'></a>
                </div>

                <div class = 'user-info'>
                    <img src='assets\user.png' id='usericon' width='25' height='25'> 
                    <h1 class = 'username' style = 'color: white;' id='username' >".$_SESSION['username']."</h1>  
                </div>

            </header>
        
        <center>
        <div class='container'>
            <div class='div1'>
            <table>
                <tr>
                    <td>
                        <img src='assets/{$marca}.png' width='200'>
                    </td>
                    <td>
                        <h1>{$marca} {$modelo}</h1>
                        <p><b>{$anio}  •  {$color}  •  {$finalVersion}  •  {$kilometraje} km</b></p>
                    </td>
                </tr>
            </table>
            </div>
            <div class='div2'>
            <table>
                <tr>
                    <td>
                        <p style ='display: inline-block;'>Valor a la <b>Venta</b> <div class='green-circle'></div> </p>
                    </td>
                    <td>
                        <p style ='display: inline-block;'>Valor <b>Medio</b> <div class='orange-circle'></div> </p>
                    </td>
                    <td>
                        <p style ='display: inline-block;'>Valor a la <b>Compra</b> <div class='blue-circle'></div> </p>
                    </td>
                </tr>
                <tr>
                    <td>
                    <h1>&dollar;{$compra}</h1>
                    </td>
                    <td>
                        <h1>&dollar;{$medio}</h1>
                    </td>
                    <td>
                        <h1>&dollar;{$venta}</h1>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><small>Cambio de {$month_change} meses</small></p>
                        <p><b>&dollar;{$cambio_compra} ({$cambio_compra_porc}%)</b></p>
                    </td>
                    <td>
                        <p><small>Cambio de {$month_change} meses</small></p>
                        <p><b>&dollar;{$cambio_medio} ({$cambio_medio_porc}%)</b></p>
                    </td>
                    <td>
                        <p><small>Cambio de {$month_change} meses</small></p>
                        <p><b>&dollar;{$cambio_venta} ({$cambio_venta_porc}%)</b></p>
                    </td>
                </tr>
            </table>
        </div>
        </div>
        <div class='container'>
            <table>
                <tr>
                    <td colspan='3'>
                        <div class='line'></div>
                    </td>
                </tr>
                <td>
                    <table>
                        <tr>
                            <div id='menu-container'>
                                <input class = 'buttonMonths' type = 'button' id = 'button3' value = '3M' onclick = 'handlePeriodoChange(3)'>
                                <input class = 'buttonMonths' type = 'button' id = 'button6' value = '6M' onclick = 'handlePeriodoChange(6)'>
                                <input class = 'buttonMonths' type = 'button' id = 'button12' value = '1A' onclick = 'handlePeriodoChange(12)'>
                                <input class = 'buttonMonths' type = 'button' id = 'button24' value = '2A' onclick = 'handlePeriodoChange(24)'>
                            </div>
                        </tr>

                        <script>
                            id_button = 'button'+'".$month_change."';
                            var month_button= document.getElementById(id_button);
                            month_button.style.background = '#cee9ff';
                            month_button.style.border = '1px solid #58aef5';
                            month_button.disabled = true;
                        </script>

                        <tr>
                            <script src='https://cdn.jsdelivr.net/npm/chart.js'></script>
                            <script src = 'graph.js' type = 'text/JavaScript'> </script>
                            <canvas id='myChart' width='750' height='400'></canvas>

                            <script>
                            const xhr = new XMLHttpRequest();
                            xhr.open('GET', document.location.href);  // Get current URL
                            xhr.onload = function() {
                                if (xhr.status === 200) {
                                    const graphDataJS = JSON.parse(xhr.getResponseHeader('X-GraphData'));
                                
                                    let dictionary_list = [];
                                    
                                    let mont_name_list = [];
                                    
                                    let purchase_price_list = [];
                                    
                                    let sale_price_list = [];
                                    
                                    let medium_price_list = [];
                                    
                                    let required_months = '$month_change';
                                    if(required_months == ''){
                                        required_months = 3;
                                    }
                                    let count = 0;
                                    for (const dictionary of graphDataJS) {
                                        if (count < parseInt(required_months)) {
                                            dictionary_list.push(dictionary);
                                            count++;
                                        } else {
                                            break; // Salir del bucle una vez que se han tomado suficientes diccionarios
                                        }
                                    }

                                    for (const dic of dictionary_list) {
                                        mont_name_list.push(dic['month_name']);
                                    }
                                    
                                    for (const dic of graphDataJS) {
                                        purchase_price_list.push(dic['purchase_price']);
                                    }
                                    
                                    for (const dic of graphDataJS) {
                                        sale_price_list.push(dic['sale_price']);
                                    }
                                    
                                    for (const dic of graphDataJS) {
                                        medium_price_list.push(dic['medium_price']);
                                    }

                                    dictionary_list.reverse();
                                    
                                    mont_name_list.reverse();
                                    
                                    purchase_price_list.reverse();
                                    
                                    sale_price_list.reverse();
                                    
                                    medium_price_list.reverse();

                                        
                                    const data = {
                                        labels: [...mont_name_list],
                                        datasets: [
                                            {
                                            label: 'Venta',
                                            data: [...purchase_price_list],
                                            backgroundColor: 'rgba(75, 189, 123, 0.1)',
                                            borderColor: 'rgb(75, 189, 123)',
                                            borderWidth: 2,
                                            fill: 'start'
                                            },
                                            {
                                            label: 'Medio',
                                            data: [...medium_price_list],
                                            backgroundColor: 'rgb(230, 144, 79, 0.1)',
                                            borderColor: 'rgb(230 144 79)',
                                            borderWidth: 2,
                                            fill: 'start'
                                            },
                                            {
                                            label: 'Compra',
                                            data: [...sale_price_list],
                                            backgroundColor: 'rgba(4, 96, 204, 0.1)',
                                            borderColor: 'rgb(4 96 204)',
                                            borderWidth: 2,
                                            fill: 'start'
                                            }
                                        ]
                                        };
                                
                                    const ctx = document.getElementById('myChart').getContext('2d');
                                    minimum = Math.min(purchase_price_list)-100000;
                                    const myChart = new Chart(ctx, {
                                    type: 'line',
                                    data: data,
                                    options: {
                                        responsive: false, // Desactivar la responsividad para usar dimensiones fijas
                                        maintainAspectRatio: false, // No mantener una relación de aspecto específica
                                        plugins: {
                                        legend: {
                                            position: 'top',
                                        },
                                        title: {
                                            display: true,
                                            text: 'Gráfico de Venta, Medio y Compra'
                                        }
                                        },
                                        layout: {
                                        padding: {
                                            left: 10,
                                            right: 10,
                                            top: 10,
                                            bottom: 10
                                        }
                                        },
                                        scales: {
                                            y: {
                                            beginAtZero: false,
                                            suggestedMin: minimum,
                                            position: 'right' 
                                        }
                                        }
                                        }
                                    });
                                }
                            };
                            xhr.send();
                            </script>
                        </tr>
                    </table>
                </td>
                <td>
                    <table>
                            <td style = 'padding-right: 0px;'>
                                <p style ='display: inline-block;'> <small> <b>Kilometraje Esperado </b></small> </p>
                                <p>{$_SESSION['sales_info']['km_minimum']} km - {$_SESSION['sales_info']['km_maximum']} km</p>
                            </td>
                            <td style = 'padding-left: 0px;'>
                                <p style ='display: inline-block;'><small> <b> Kilometraje Promedio </b> </small> </p>
                                <p>{$_SESSION['sales_info']['km_average']} km</p>
                            </td>
                                <tr>
                                    <td colspan = '2'>
                                        <div id='myDiv'>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan = '2'>
                                        <div class='bottom-left-button'>
                                            <input name ='boton' type = 'button'  class ='buttons' value = '+ Cotizar nuevo auto' onclick= \"window.location.href='http://localhost/MotorLeads/login.php?email=".$_SESSION['email']."&contrasena=". $_SESSION['contrasena']."'\"> 
                                        </div>
                                    </td>
                                </tr>
                        <tr>
                            <div style='text-align: right'>
                                <table>
                                    <td></td>
                                    <td class='custom-width'>
                                        <script src='https://cdn.plot.ly/plotly-2.31.1.min.js'></script>
                                        <script>
                                            var data = [
                                                {
                                                type: 'indicator',
                                                mode: 'gauge',
                                                value: ". $valor_km.",
                                                domain: { x: [0, 1], y: [0, 1] },
                                                title: { text: '<b>Km</b>' },
                                                gauge: {
                                                bar: { color: '#0da8eb' },
                                                shape: 'bullet',
                                                axis: { range: [".$graph_min.",".$graph_max."] },
                                                threshold: {
                                                    line: { color: '#ff414194', width: 5 },
                                                    thickness: 1,
                                                    value:".$graph_mean."
                                                },
                                                steps: [
                                                    { range: [".$km_min.", ".$graph_mean."], color: '#4bbd7a54'},
                                                    { range: [".$graph_mean.", ".$km_max."], color: '#0a5e2d9c'}
                                                ]
                                                }
                                            }
                                            ];
                                        
                                            var layout = { width: 550, height: 260, title: {
                                                text: '<b>Kilometraje proporcionado con respecto al esperado</b>',
                                                font: {
                                                    family: 'Arial',
                                                    size: 14,
                                                    color: 'black'
                                                },
                                                x: '0.5',
                                                y: '0.8',
                                                automargin: true,
                                                }};
                                            var config = { responsive: true };
                                        
                                            Plotly.newPlot('myDiv', data, layout, config);
                                        </script>
                                    </td>
                                </table>
                            </div>
                        </tr>
                    </table>
                </td>
            </table>
        </div>
        </center>
        </body>
        </html>";
    }


    //Llamada a las funciones principales
    send_graph_info();
    create_general_info_table($month_change);

?>