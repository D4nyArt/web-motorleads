function handlePeriodoChange(months){
    let periodoSeleccionado = months;
    url = 'http://localhost/MotorLeads/graph.php?required_months='+periodoSeleccionado;
    location.href = url; 
}
