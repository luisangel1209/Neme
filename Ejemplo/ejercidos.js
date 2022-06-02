var datosTabla=[];
var bandera = false;
var bandera2 = false;
let Anio = getAnio();
var resVal = 0;
let desc;
var nombre;
let UBPP = document.getElementById('UBPP');
let UBPPDes = document.getElementById('UBPPDes');
let CCPA = document.getElementById('CCPA');
let CCPADes = document.getElementById('CCPADes');
let nombreMes = document.getElementById('nombreMes');
let importe = document.getElementById('importe');
let observaciones = document.getElementById('observaciones');
let BNuevo = document.getElementById('BNuevo');
let BGuardar = document.getElementById('BGuardar');
let BImprimir = document.getElementById('BImprimir');
let Agregar = document.getElementById('agregar');
const wsUrl = `http://10.0.0.12/ws/reporter.php?service=report&name=`;

// MASD3479
// ZOSU7825

$("#folio").change(function(){
    buscaFolio();
}); 

$('#UBPP').on('change', function() {
    $('#UBPPDes').val($("#UBPP").val());
    validarUBPPPartidas($('#UBPP').val(), $('#CCPA').val());
});

$('#UBPPDes').on('change', function() {
    $('#UBPP').val($("#UBPPDes").val());
    validarUBPPPartidas($('#UBPP').val(), $('#CCPA').val());
});

$('#CCPA').on('change', function() {
    $('#CCPADes').val($("#CCPA").val())
    descripcionPartidas($("#CCPA").val());
    validarUBPPPartidas($('#UBPP').val(), $('#CCPA').val());
});

$('#CCPADes').on('change', function() {
    $('#CCPA').val($("#CCPADes").val())
    descripcionPartidas($("#CCPA").val());
    validarUBPPPartidas($('#UBPP').val(), $('#CCPA').val());
});

$('#nombreMes').on('change', function() {
    $.ajax({
        url: '/validaImp2',
        type: 'GET',
        data:{
            UBPP: $("#UBPP").val(),
            CCPA: $("#CCPA").val(),
            mes: $('#nombreMes').val()
        },
    }).done(function(data){
        if(data > 0){
            $('#importe').val(dosDecimales(data));
        }else{
            $('#importe').val("0");
        }
    });
});

function dosDecimales(n) {
    let t=n.toString();
    let regex=/(\d*.\d{0,2})/;
    return t.match(regex)[0];
}

$( document ).ready(function() {
    $('#contra').modal('toggle')
    $('#botonContra').hide();
});

function Cancelar(){
    $('#folio').hide();
    $('#contra').modal('hide');
    $("#miContraseña").val("");
    $('#botonContra').show();
    BGuardar.disabled = true;
    BImprimir.disabled = true;
    Agregar.disabled = true;
}

function ingresarContra(){
    $('#contra').modal('show');
}

function validarContra() {
    $.ajax({
        url: '/contra',
        type: 'GET',
        data:{
            login: $("#miContraseña").val(),
            },
    })
    .done(function(data) {
        if(data.length != 0){
            toastr.success('Contraseña correcta');
            $('#contra').modal('hide'); 
            $('#botonContra').hide();
            $('#folio').show();
            $("#UBPP").val(data[0].UBPP);
            $("#UBPPDes").val(data[0].UBPP);
            const fecha = new Date();
            $("#fecha").val(fecha.toLocaleDateString());
            Agregar.disabled = false;
            if(data[0].UBPP == 6065 || data[0].UBPP == 6030){
                bandera = true;
            }else{
                UBPP.disabled = true;
                UBPPDes.disabled = true;
            }
        }else{
            toastr.error('La contraseña introducida no es valida, Vuelva a intentarlo');
            $("#miContraseña").val("");
        }
    });
}

function buscaFolio() {
    if($("#folio").val() != ""){
        toastr.info("Buscando información con el folio "+$("#folio").val()+"... espere un momento");
        $.ajax({
            url: '/buscaFolioEjercidos',
            type: 'GET',
            data:{
                folio: $("#folio").val(),
                vista: 'ejercidos'
            },
        }).done(function(data){
            if(data.tabla != ""){
                if(data.editar == false){
                    UBPP.disabled=true;
                    UBPPDes.disabled=true;
                    CCPA.disabled=true;
                    CCPADes.disabled=true;
                    nombreMes.disabled=true;
                    importe.disabled=true;
                    observaciones.disabled=true;
                    BGuardar.disabled=true;
                    BImprimir.disabled=false;
                    Agregar.disabled=true;
                    $("#fecha").val(formatoFecha(data.fecha.split(" ")));
                    $('#tabla > tbody').html(data.tabla);
                }else{
                    $("#fecha").val(formatoFecha(data.fecha.split(" ")));
                    $('#tabla > tbody').html(data.tabla);
                }
            }else{
                toastr.warning("No hay datos con el folio "+$("#folio").val());
                $('#tabla > tbody').html("");
                // $("#incrementos").val("");
                // $("#disminuciones").val("");
                // buttonImprimir.disabled = true;
            }
        });
    }
}

function validarUBPPPartidas(UBPP, CCPA){
    if(UBPP != '' && CCPA != ''){
        if(UBPP == 6065 || UBPP == 6030){
            $.ajax({
                url: '/buscarUBPPCCPA',
                type: 'GET',
                data:{
                    UBPP: UBPP,
                    CCPA: CCPA
                    },
            })
            .done(function(data) {
                if(data.length != 0){
                }else{
                    toastr.info('UBPP y Partida inexistente en el presupuesto');
                    $('#UBPP').val("");
                    $('#UBPPDes').val("");
                    $('#CCPA').val("");
                    $('#CCPADes').val("");
                }
            });
        }else{
            if(CCPA >= 2000 && CCPA < 7000 || bandera == true){
                $.ajax({
                    url: '/buscarUBPPCCPA',
                    type: 'GET',
                    data:{
                        UBPP: UBPP,
                        CCPA: CCPA
                        },
                })
                .done(function(data) {
                    if(data.length != 0){
                    }else{
                        toastr.info('UBPP y Partida inexistente en el presupuesto');
                        $('#UBPP').val("");
                        $('#UBPPDes').val("");
                        $('#CCPA').val("");
                        $('#CCPADes').val("");
                    }
                });
            }else{
                toastr.info('No esta autorizado para visualizar estas partidas');
                partidaInvalida();
            }
        }
    }
}

function descripcionPartidas(CCPA) {
    $.ajax({
        url: '/traerDescripcionPartidas',
        type: 'GET',
        data:{
            CCPA: CCPA,
        }
    })
    .done(function(data) {
        if(data.length != ''){
            desc = data[0].Descripción;
        }
    });
}

function formatoFecha(texto){
    let fecha = texto[0].split("-");
    return fecha[2]+"/"+fecha[1]+"/"+fecha[0];
}

function partidaInvalida(){
    $('#tabla > tbody').html("");
    $("#fecha").val("");
    $("#CCPA").val("");
    $("#CCPADes").val("");
    // $("#nombreMes").val("");
    // $("#importe").val("");
    // $("#transferencias").val("");
    // $("#observaciones").val("");
    // actualizarTabla(datosTabla);
    // $('#kt_datatable').KTDatatable('destroy');
    // UBPP.disabled=false;
    // UBPPDes.disabled=false;
    // CCPA.disabled=false;
    // CCPADes.disabled=false;
    // nombreMes.disabled=false;
    // importe.disabled=false;
    // transferencias.disabled=false;
    // observaciones.disabled=false;
    // BGuardar.disabled=true;
    // BImprimir.disabled=true;
    // Agregar.disabled=false;
}

function Nuevo(){
    $('#tabla > tbody').html("");
    $("#folio").val("");
    $("#fecha").val("");
    $("#UBPP").val("");
    $("#UBPPDes").val("");
    $("#CCPA").val("");
    $("#CCPADes").val("");
    $("#nombreMes").val("");
    $("#importe").val("");
    $("#observaciones").val("");
    UBPP.disabled=false;
    UBPPDes.disabled=false;
    CCPA.disabled=false;
    CCPADes.disabled=false;
    nombreMes.disabled=false;
    importe.disabled=false;
    observaciones.disabled=false;
    BGuardar.disabled=true;
    BImprimir.disabled=true;
    Agregar.disabled=false;
}

function validaRepetidos( ubbp, ccpa ){
    // obtenemos las filas
    trTable = $('#tabla > tbody').find('tr');
    let ubppTable = [];
    let ccpaTable = [];
    let expReg = /(\d+)/g;
    let ubppArray = ubbp.match(expReg);
    let ccpaArray = ccpa.match(expReg);
    for (let i = 0; i <= trTable.length; i ++){
        ubppTable[i] = $(trTable[i]).find('td:nth-child(2)').text();
        ccpaTable[i] = $(trTable[i]).find('td:nth-child(3)').text();
    }
    for (let i = 0; i <= trTable.length; i ++){
        if( parseInt(ubppArray[0], 10) == parseInt(ubppTable[i], 10) && parseInt(ccpaArray[0], 10) == parseInt(ccpaTable[i], 10) ){
            return false;
        }
    }
    return true;
}

function agregar(){
    var total = document.getElementById("tabla").rows.length;
    toastr.info( 'Validando información...espere un momento por favor');
    // obtenemos los valores del formulario
    let ubpp            = $("#UBPP").val();
    let ccpa            = $('#CCPA').val();
    let mes             = $("#nombreMes").val();
    let importe         = $("#importe").val();
    let observaciones   = $("#observaciones").val();
    if( validaRepetidos( $("#UBPP").val(), $('#CCPA').val() ) ){
        $.ajax({
            url:    '/agregarTablaEjercidos',
            type:   'GET',
            data:{
                ubpp: ubpp,
                ccpa: ccpa,
                desc: desc,
                mes:  mes,
                importe: importe,
                cons: total,
                observaciones: observaciones,
                vista: 'ejercidos',
            }
        })
        .done( function(data) {
            if( data.error != '' ){
                toastr.error( data.error);
            }else{
                //insertar data en la tabla
                if(total >= 2){
                    $('#tabla > tbody').append(data.tabla);
                }else{
                    $('#tabla').removeClass('d-none')
                    $('#tabla > tbody').html(data.tabla);
                }
                BGuardar.disabled = false;
                BImprimir.disabled = true;
                limpiar();
            }
           
        });
    }else{
        toastr.error( 'La UBPP / PARTIDA ya se ha agregado y no se puede repetir');
    }
}

function compararNuevos(UBPP, CCPA){
    let ubpp;
    let ccpa;
    if(datosTabla.length == 0){
        return true;
    }else{
        ubpp = datosTabla.find(valor => valor.UBPP === UBPP);
        ccpa = datosTabla.find(valor => valor.CCPA === CCPA);
        if(ubpp != undefined && ccpa != undefined){
            return false;
        }
        return true;
    }
}

function limpiar(){
    if(bandera == false){
        // $("#UBPP").val("");
        // $("#UBPPDes").val("");
        $("#CCPA").val("");
        $("#CCPADes").val("");
        $("#nombreMes").val("");
        $("#importe").val("");
        $("#observaciones").val("");
    }else{
        $("#UBPP").val("");
        $("#UBPPDes").val("");
        $("#CCPA").val("");
        $("#CCPADes").val("");
        $("#nombreMes").val("");
        $("#importe").val("");
        $("#observaciones").val("");
    }
}

function Guardar(){
    if( $('#tabla tbody > tr').length == 0 ){
        toastr.error( 'No se puede guardar si no hay registros.');
    }else{
        // obtenemos todas las filas de la tabla
        let filas = [];
        filas = obtenerFilas();
        $.ajax({
            url:    '/guardarFolioEjercidos',
            type:   'GET',
            data:{
                table: filas, 
                vista: 'ejercidos',
                folio: $("#folio").val()
            }
        })
        .done( function(data) {
            if( data.error != '' ){
                toastr.error( data.error);
            }else{
                toastr.success('Datos guardados correctamente');
                $('#folio').val( data.folio );
                BImprimir.disabled=false;
            }
        });
    }
}

function obtenerFilas(){
    let filas = [];
    let letras = '';
    let numFilas = $('#tabla tbody tr').length;
    for( i = 0; i < numFilas; i++ ){
        let fila = $('#tabla tbody tr')[i];
        let numCeldas = $(fila).find('td').length;
        for( j = 0; j <= numCeldas; j++ ){
            letras = letras + $( $(fila).find('td')[j] ).text() + '_';
        }
        filas[i] = letras;
        letras = '';
    }
    return filas;
}

$(document).ready(function(){
    $("#buscar").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $("#tablaBody tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });
});

$(document).on('click', '#eliminar', function(event) {
    event.preventDefault();
    Swal.fire({ // se define el mensaje del modal eliminar grupo
        title: '¿Estás seguro de eliminar el registro?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminarlo',
        cancelButtonText: 'No, cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed){
            $(this).closest('tr').remove();
            toastr.success('Registro eliminado');
        }
    });
});

$(document).on('click', '#editar', function(event) {
    event.preventDefault();
    $("#UBPP").val($(this).parents("tr").find("td")[1].innerHTML);
    $("#UBPPDes").val($(this).parents("tr").find("td")[1].innerHTML);
    $("#CCPA").val($(this).parents("tr").find("td")[2].innerHTML);
    $("#CCPADes").val($(this).parents("tr").find("td")[2].innerHTML);
    $("#nombreMes").val($(this).parents("tr").find("td")[8].innerHTML);
    $("#importe").val($(this).parents("tr").find("td")[5].innerHTML);
    $("#observaciones").val($(this).parents("tr").find("td")[10].innerHTML);
    $(this).closest('tr').remove();
    BGuardar.disabled = false;
});

function nombreCapturista(){
    $.ajax({
        url: '/nombreCapturista',
        type: 'GET',
        data:{
            folio: $("#folio").val()
        },
        async: false
    })
    .done(function(data) {
        console.log(data);
        nombre = data;
    });
}

function Imprimir(){
    nombreCapturista();
    let mensajeEdoSolicitud = toastr.info('Procesando solicitud, espere un momento por favor . . .', '', {
        timeOut: '0'
    });
    let urlEjercidos = `${wsUrl}RptCPCedEjercido&params=Anio:${Anio},Folio:${$("#folio").val()},Nombre:${nombre.trim()}`;
    fetch(urlEjercidos).then(response => {
        if (!response.ok) {
            toastr.error("Problemas al procesar la solicitud, por favor inténtelo más tarde");
            mensajeEdoSolicitud.remove();
        }else{
            response.text().then(pdfEjercidos => {
                window.open(pdfEjercidos);
                mensajeEdoSolicitud.remove();
            });
        }
    }).catch(error => {
        toastr.error("Problemas al procesar la solicitud, por favor inténtelo más tarde");
    });
} 

function getAnio() {
    var anio = '';
    $.ajax({
      url: '/getAnioPeriodo',
      type: 'GET',
      async: false
    }).done(function(data) {
      anio = data;
      if (anio==''){
        anio=null;
      }
    });
    return anio;
}