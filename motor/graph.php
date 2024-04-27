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

    function create_general_info_table(){
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
                    border-spacing: 20px; 
                }

                tr {
                    text-align: center;
                }

            </style>
        </head>

        <body class = 'background'>
            <header>
                <div class='header-image'>
                    <a href='forms.html'><img src='assets\copa_logo.png' width= '100' height='50'></a>
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
                        <p><b>{$anio} • {$color} • {$finalVersion} • {$kilometraje} km</b></p>
                    </td>
                </tr>
            </table>
            </div>
            <div class='div2'>
            <table>
                <tr>
                    <td>
                        <p>Valor a la <b>Venta</b></p>
                    </td>
                    <td>
                        <p>Valor <b>Medio</b></p>
                    </td>
                    <td>
                        <p>Valor a la <b>Compra</b></p>
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
                        <p><small>Cambio de 3 meses</small></p>
                        <p><b>{$cambio_compra} ({$cambio_compra_porc})</b></p>
                    </td>
                    <td>
                        <p><small>Cambio de 3 meses</small></p>
                        <p><b>{$cambio_medio} ({$cambio_medio_porc})</b></p>
                    </td>
                    <td>
                        <p><small>Cambio de 3 meses</small></p>
                        <p><b>{$cambio_venta} ({$cambio_venta_porc})</b></p>
                    </td>
                </tr>
            </table>
            </div>
        </div>
        </center>
        <div class='bottom-right-button'>
            <button onclick=\"window.location.href='forms.html'\">Regresar</button>
        </div>
        </body>
        </html>";
    }

    function show_info_graph(){
        echo "
        <script src='https://cdn.jsdelivr.net/npm/chart.js'></script>
        <canvas id='myChart' width='800' height='300'></canvas>
        <script>
            const xhr = new XMLHttpRequest();
            xhr.open('GET', document.location.href);  // Get current URL
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const graphDataJS = JSON.parse(xhr.getResponseHeader('X-GraphData'));
    
                    const firstDictionary = graphDataJS[0];
                    const secondDictionary = graphDataJS[1];
                    const thirdDictionary = graphDataJS[2];
    
                    const firstMonth = firstDictionary['month_name']
                    const secondMonth = secondDictionary['month_name']
                    const thirdMonth = thirdDictionary['month_name']
    
                    const firstMonthPurchase = parseInt(firstDictionary['purchase_price'])
                    const secondMonthPurchase = parseInt(secondDictionary['purchase_price'])
                    const thirdMonthPurchase = parseInt(thirdDictionary['purchase_price'])
    
                    const firstMonthSale = parseInt(firstDictionary['sale_price'])
                    const secondMonthSale = parseInt(secondDictionary['sale_price'])
                    const thirdMonthSale = parseInt(thirdDictionary['sale_price'])
    
                    const firstMonthMedium = parseInt(firstDictionary['medium_price'])
                    const secondMonthMedium = parseInt(secondDictionary['medium_price'])
                    const thirdMonthMedium = parseInt(thirdDictionary['medium_price'])
    
                    const data = {
                        labels: [firstMonth, secondMonth, thirdMonth],
                        datasets: [
                          {
                            label: 'Venta',
                            data: [firstMonthPurchase, secondMonthPurchase, thirdMonthPurchase],
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 5
                          },
                          {
                            label: 'Compra',
                            data: [firstMonthSale, secondMonthSale, thirdMonthSale],
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 5
                          },
                          {
                            label: 'Medio',
                            data: [firstMonthMedium, secondMonthMedium, thirdMonthMedium],
                            backgroundColor: 'rgba(255, 205, 86, 0.2)',
                            borderColor: 'rgba(255, 205, 86, 1)',
                            borderWidth: 5
                          }
                        ]
                      };
    
                    const ctx = document.getElementById('myChart').getContext('2d');
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
                              text: 'Gráfico de líneas Chart.js'
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
                              beginAtZero: true
                            }
                          }
                        }
                      });
                }
            };
            xhr.send();
        </script>";
    }
    
    send_graph_info();
    create_general_info_table();
    show_info_graph(); 
?>
