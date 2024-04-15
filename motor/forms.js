function send_marcas(){
    marca = document.form1.marca.value;
    api_format = "makes,marca,models";
    wanted_values= "name";
    url = "http://localhost/clasefj2024/motor/forms.php?api_format="+api_format+"&wanted_values="+wanted_values;
    url = url+"&selectedFilter="+marca;
    location.href=url;
}

function send_modelos(){
    modelo = document.form1.modelo.value;
    api_format = "models,modelo,years";
    wanted_values= "name";
    url = "http://localhost/clasefj2024/motor/forms.php?api_format="+api_format+"&wanted_values="+wanted_values;
    url = url+"&selectedFilter="+modelo;
    location.href=url;
}


function send_anio(){
    anio = document.form1.anio.value;
    api_format = "models,modelo,years,anio,vehicles";
    wanted_values= "version";
    url = "http://localhost/clasefj2024/motor/forms.php?api_format="+api_format+"&wanted_values="+wanted_values;
    url = url+"&selectedFilter="+anio;
    location.href=url;
}

function send_version(){
    version = document.form1.version.value;
    api_format = "vehicles,version,pricings";
    wanted_values= "vehicle_id, vehicle_version,";
    url = "http://localhost/clasefj2024/motor/forms.php?api_format="+api_format+"&wanted_values="+wanted_values;
    url = url+"&selectedFilter="+version;
    location.href=url;
}

function send_rest_values(){
    api_format = "kilometraje,color";
    kilometraje = document.form1.kilometraje.value;
    color = document.form1.color.value;
    datos = kilometraje + "," + color;

    url = "http://localhost/clasefj2024/motor/forms.php?selectedFilter="+datos+"&api_format="+api_format;
    location.href=url;
}
