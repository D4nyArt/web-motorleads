function send_login_data(){
    email = document.form1.email.value;
    contrasena = document.form1.contrasena.value;
    regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    valid = regexCorreo.test(email);
    if (valid && contrasena.length > 4) {
        url = "http://localhost/clasefj2024/motor/login.php?email="+email+"&contrasena="+contrasena;
        location.href=url;
    } 
    
    else {
        document.getElementById("email").style.boxShadow = "5px 5px 5px lightblue";
        document.getElementById("contrasena").style.boxShadow= "5px 5px 5px lightblue";
        customAlert.alert('No pudimos encontrar tu cuenta. Ingresa un correo o contraseña válida.','Inicio de Sesión Invalido');
    }
}

function gray_button(id){
    document.getElementById(id).style.boxShadow = "none";
    document.getElementById(id).style.boxShadow = "none";

}

function CustomAlert(){
    this.alert = function(message,title){
    document.body.innerHTML = document.body.innerHTML + '<div id="dialogoverlay"></div><div id="dialogbox" class="slit-in-vertical"><div><div id="dialogboxhead"></div><div id="dialogboxbody"></div><div id="dialogboxfoot"></div></div></div>';

    let dialogoverlay = document.getElementById('dialogoverlay');
    let dialogbox = document.getElementById('dialogbox');

    let winH = window.innerHeight;
    dialogoverlay.style.height = winH+"px";

    dialogbox.style.top = "100px";

    dialogoverlay.style.display = "block";
    dialogbox.style.display = "block";

    document.getElementById('dialogboxhead').style.display = 'block';

    if(typeof title === 'undefined') {
    document.getElementById('dialogboxhead').style.display = 'none';
    } else {
    document.getElementById('dialogboxhead').innerHTML = '<i class="fa fa-exclamation-circle" aria-hidden="true"></i> '+ title;
    }
    document.getElementById('dialogboxbody').innerHTML = message;
    document.getElementById('dialogboxfoot').innerHTML = '<button class="pure-material-button-contained active" onclick="customAlert.ok()">OK</button>';
    }

    this.ok = function(){
    document.getElementById('dialogbox').style.display = "none";
    document.getElementById('dialogoverlay').style.display = "none";
    }
}

    let customAlert = new CustomAlert();