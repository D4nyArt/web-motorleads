function handlePeriodoChange(){
    let periodoSeleccionado = document.getElementById("periodo").value;
    console.log(periodoSeleccionado);
    url = 'http://localhost/MotorLeads/graph.php?required_months='+periodoSeleccionado;
    location.href = url; 
}
