
var print_day={people:'', salary:'',data:'',addAmount:0,extAmount:0};


var tablehertra; 
function PagosJornadasMensualesPersonal(){
	  tablehertra=$('#tabla_pagos_jornadas').DataTable({ 
	    "ordering": true,
	    "bLengthChange": false,
	    "scrollCollapse": true,
	    "searching": {
	      "regex": true
	    },
	    "lengthMenu": [
	    [10, 20, 50, 100, -1],
	    [10, 20, 50, 100, "All"]
	    ],
	    "pageLength": 10,
	    "destroy": true,
	    
	    "processing": true,
	    "ajax": {
	      "url": "../controller/pagojornada/ControllerGetPersonasOn.php",
	      type: 'POST'
	    },
	    "columns": [
	    { "data": null,"render": function (data, type, row, meta) {return meta.row + 1; }},
	    { "data": "idpersona"},
	     {"data": null, "render": function (data, type, row) {return row.Nombre + ' ' + row.Apellidos;}},
	     { "data": "Dni"},
	    { "data": "Salario"},
	    { "data": "FechaInicio"},
	    { "data": "fechapago"},
	    { "data": "EstadoCuenta",
	      render: function(data, type, row) {
	        if (data == 'Pagado') {
	          return "<span class='badge badge-pill badge-primary'>" + data + "</span>";
	        } 
          if (data == 'Deuda') {
            return "<span class='badge badge-pill badge-danger'>" + data + "</span>";
          }
          if (data == 'Procesando...') {
            return "<span class='badge badge-pill badge-warning'>" + data + "</span>";
          }
	      }
	    } ,{
	      "defaultContent": "<button  class='pagarjornada btn btn-primary btn-sm' title='Pagar'><i class='fa fa-plus-circle' aria-hidden='true'></i></button>"+
        "&nbsp;<button  class='Imprimir btn btn-secondary btn-sm' title='imprimir'><i class='fa fa-print' aria-hidden='true'></i></button>"

	    }],
	    "language": idioma_espanol,
	    select: true
	  });
	  document.getElementById("tabla_pagos_jornadas_filter").style.display = "none";
	  $('input.global_filter').on('keyup click', function() {
	    filterGlobal();
	  });
	  tablehertra.column( 1 ).visible( false );
	}
function filterGlobal() {
	  $('#tabla_pagos_jornadas').DataTable().search($('#global_filter').val(), ).draw();
}

$('#tabla_pagos_jornadas').on('click', '.pagarjornada', function() {
	  var data = tablehertra.row($(this).parents('tr')).data();

	  if (tablehertra.row(this).child.isShown()) {
	    var data = tablehertra.row(this).data();
	  }
	  var idpersona= data.idpersona;
    var fecha= data.FechaInicio;
	
     print_day.people= data.Nombre+','+ data.Apellidos;
     print_day.salary= data.Salario;
     print_day.typeMoney= data.Moneda;

	   $("#modapagosjornadas").modal({
	      backdrop: 'static',
	        keyboard: false
	    })
      $("#namepeople").html(data.Nombre+','+ data.Apellidos);
	    $("#ipersonaspagos").val(idpersona);
      $("#fechadeultimopago").val(fecha);
      $("#htmlpago").html(fecha);
	    $("#tbody_tabla_detall_pagos").html('');
           reseterDtaModal();
          //cargarComboMeses();
	    $('#modapagosjornadas').modal('show');
	 
	});

function cargarComboMeses(){
	var mesSelect = document.getElementById('mesSelect');
	 // Cargamos los meses del año desde un array
    var meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

    // Creamos las opciones del select a partir del array de meses
    for (var i = 0; i < meses.length; i++) {
      var option = document.createElement('option');
      option.value = meses[i];
      option.text = meses[i];
      mesSelect.appendChild(option);
    }
}




var mesesNumeros = {
  'Enero': '01',
  'Febrero': '02',
  'Marzo': '03',
  'Abril': '04',
  'Mayo': '05',
  'Junio': '06',
  'Julio': '07',
  'Agosto': '08',
  'Septiembre': '09',
  'Octubre': '10',
  'Noviembre': '11',
  'Diciembre': '12'
};


var totalAcumulado = 0;
var yearAjustado;
var cambio=false;
var contador=1;

function AgregarMesSeleccionadoTable() {
  var mesSelect = document.getElementById('mesSelect');
  var montoInput = document.getElementById('montoInput');

  var mesSeleccionado = mesSelect.value;
  var monto = montoInput.value;
  if(!mesSeleccionado){ return;}

//FECHA DEL ULTIMO PAGO///
var fechpagado = $("#fechadeultimopago").val();
if (validarFormatoFecha(fechpagado)) {
  var parts = fechpagado.split('-');
  var day = parseInt(parts[2]);
  var mountPayment = parseInt(parts[1]);
  var month = mesesNumeros[mesSeleccionado];
  var year = parseInt(parts[0]);
} else {
  // La fecha no tiene el formato esperado, muestra un mensaje de error o realiza alguna acción adicional
  return Swal.fire("Mensaje de error", "La fecha no tiene el formato esperado."+fechpagado, "error");
}
if(mountPayment==12 && month ==1){cambio = true; }



if (month == 12 && mesSeleccionado == "Diciembre"/*&& day >= 31*/) {
  cambio = true;
} else if (yearAjustado == undefined) {
  yearAjustado = year;
}

if (cambio && month != 12) {
   
    yearAjustado++;
    cambio = false;
}
fecha = yearAjustado + '-' + (month.toString().padStart(2, '0')) + '-' + day.toString().padStart(2, '0');


if (validarFormatoFecha(fecha) && validarFormatoFecha(fechpagado)) {
  
 if (fecha <= fechpagado) {
  return Swal.fire("Mensaje de advertencia", "Las fechas a pagar deben ser mayor a: "+fechpagado+".", "warning");

} else {
  //aqui continua normal
}

}else {
  return Swal.fire("Mensaje de error", "Una o ambas fechas tienen un formato incorrecto."+fechpagado+" <=> "+ fecha, "error");

}

  // Verificar si la fecha ya está agregada en la tabla
  var existeFecha = verificarfecha(fecha);
  if (existeFecha) {
   
     return Swal.fire("Mensaje de advertencia", "La fecha: "+fecha+" ya está agregada en la tabla.", "warning");
  }

  if (mesSeleccionado && monto) {
    var html = "";
    html += "<tr>";
    html += "<td>"+contador+"</td>";
    html += "<td>" + mesSeleccionado + "</td>";
    html += "<td>"+ fecha +"</td>";
    html += "<td>1</td>";
    html += "<td>" + monto + "</td>";
    html += "<td><button class='btn btn-sm' onclick='remove(this)'><em class='fa fa-times'></em></button></td>";
    html += "</tr>";
    $('#tbody_tabla_detall_pagos').append(html);

       mesSelect.value = "";
       montoInput.value = "";
       contador++; // Incrementar el contador
    // Actualizar el número de filas en la tabla
    var filas = $('#tbody_tabla_detall_pagos tr');
    filas.each(function (index) {
      $(this).find('td:first').text(index + 1);
    });

    
  }
  actualizartableAcumulado();
}


function actualizartableAcumulado(){
   var tableRotal = 0;
  var filas = $('#tbody_tabla_detall_pagos tr');
    filas.each(function () {
     var monto = parseFloat($(this).find('td:eq(4)').text());
     if (!isNaN(monto)) {
        tableRotal += monto;
     }
  
     });
    var adelanto = parseFloat($('#total_adelantos').text()) || 0;
    var extra = parseFloat($('#total_horas_extras').text()) || 0;
   let total =  Number(tableRotal)+ Number(extra);
 $('#total_acumulado').text((Number(total)-Number(adelanto)) .toFixed(2));
}


function toggleincluiradelantos(e) {
 Extrae_adelantos_Persona()
 .then((montototal) => {
  MostrarTotalAdelantosPersonal(e, montototal);

})
 .catch((error) => {
  console.error(error);
        // Maneja el error si ocurre
      });//
}

function toggleincluirextras(e) {
 Extrae_Horas_Extras()
 .then((montototal) => {
   MostrarTotalHorasExtrasPersonal(e, montototal);
 })
 .catch((error) => {
  console.error(error);
        // Maneja el error si ocurre
      });//
}




function MostrarTotalHorasExtrasPersonal(e, montototal){
  
  var htmlextra = document.getElementById('total_horas_extras');
       htmlextra.textContent = '0.00';
       if (e.checked) {
        htmlextra.textContent = parseInt(montototal).toFixed(2);
        actualizartableAcumulado();
       } else actualizartableAcumulado();
       
}

function MostrarTotalAdelantosPersonal(e , montototal) {
  var htmlextra = document.getElementById('total_adelantos');
       htmlextra.textContent = '0.00';
        if (e.checked) {
       htmlextra.textContent = parseInt(montototal).toFixed(2);
       actualizartableAcumulado();
     } else actualizartableAcumulado();
}



function validarFormatoFecha(fecha) {
  var formatoValido = /^\d{4}-\d{2}-\d{2}$/;
  return formatoValido.test(fecha);
}



function remove(e) {
   var row = e.parentNode.parentNode;
   var monto = parseFloat(row.cells[4].innerHTML);
   row.parentNode.removeChild(row);
  var filas = $('#tbody_tabla_detall_pagos tr');
    filas.each(function (index) {
      $(this).find('td:first').text(index + 1);
    });
    actualizartableAcumulado();
}


function verificarfecha(fecha) {
  var filas = document.querySelectorAll('#tbody_tabla_detall_pagos td:nth-child(3)');
  for (var i = 0; i < filas.length; i++) {
    var fechaFila = filas[i].textContent;
    if (fechaFila === fecha) {
      return true; // La fecha ya está agregada en la tabla
    }
  }
  return false; // La fecha no está agregada en la tabla
}



function Registrar_Pagos_mensuales(btn) {
  var total= $('#total_acumulado').text();
  if (total < 0 || total == 0) {
  return Swal.fire("Mensaje de advertencia",'Usted tiene un saldo a favor de '+ total + ' soles; es insuficiente para completar la operación.', "warning");
 }


// Verificar si los checkboxes están marcados
let delantos = $('#adelantos').prop('checked');
let extras = $('#horasextras').prop('checked');
let idpersona = $('#ipersonaspagos').val();

  // Obtener los datos de la tabla
  var tablaData = [];
  $('#tbody_tabla_detall_pagos tr').each(function() {
    var filaData = {
      mesSeleccionado: $(this).find('td:nth-child(2)').text(),
      fecha: $(this).find('td:nth-child(3)').text(),
      monto: $(this).find('td:nth-child(5)').text()
    };
    tablaData.push(filaData);
  });
  //guadar el objeto para imprimir
   print_day.data = tablaData;
   print_day.addAmount = $('#total_adelantos').text() || 0;
   print_day.extAmount = $('#total_horas_extras').text() || 0;
  // Verificar si la tablaData tiene datos antes de enviarlos
  if (tablaData.length > 0) {
    
      toggleIconChange(btn);
    // Enviar los datos a través de una solicitud POST
    $.post('../controller/pagojornada/ControllerRegisterDaysPayment.php', { 
      data: tablaData ,delantos:delantos,extras:extras,idpersona:idpersona})
    .done(function(resultado) {
      var response = JSON.parse(resultado);
        if (response.status) {
          Swal.fire({ position: 'top-end',icon: 'success', title: 'Éxito !!', text: response.msg, showConfirmButton: false, timer: 1500 })
          tablehertra.ajax.reload();
          $('#modapagosjornadas').modal('hide');
          ShowPrintModalTiket(print_day);
          reseterDtaModal();
         
        } else { Swal.fire("Mensaje de error", response.msg, "error"); }
        
      })
    .fail(function(jqXHR, textStatus, errorThrown) {
      if (jqXHR.status === 403) {
        Swal.fire("Mensaje de error", "No Autorizado, Iniciar Sesión.", "error");
      } else {
        Swal.fire("Mensaje de error", errorThrown, "error");
      }
    });
     

  } else {
    // Mostrar un mensaje de error si no hay datos en la tablaData
    Swal.fire("Mensaje de error", "No hay datos para enviar", "error");
  }
   toggleIconRollback(btn);
}


function reseterDtaModal(){
  $('#montoInput').prop('checked', false);
  $('#horasextras').prop('checked', false);
  $('#mesSelect').val(0).trigger('change');
  var mesSelect = document.getElementById('mesSelect');
  mesSelect.value = "";

  $('#mesSelect').prop('selectedIndex', -1);

  var selectElement = document.getElementById('mesSelect');
  selectElement.selectedIndex = -1;
  var totalHorasExtras = document.getElementById('total_horas_extras');
  var totaladelanto = document.getElementById('total_adelantos');
  var totalAcumulado = document.getElementById('total_acumulado');
  totalHorasExtras.textContent = '0';
  totalAcumulado.textContent = '0';
  totaladelanto.textContent = '0';

  $('#montoInput').val('');

  totalAcumulado = 0;
  yearAjustado= undefined;
  cambio=false;
  contador=1;

}






function Extrae_Horas_Extras() {
  return new Promise(function(resolve, reject) {
    var idpersona = $('#ipersonaspagos').val();
    $.post('../controller/pagojornada/ControllerShowTotaCostoExtra.php', { idpersona: idpersona })
      .done(function(resultado) {
        var response = JSON.parse(resultado);
        var montototal = response.data;
        resolve(montototal);
      })
      .fail(function(jqXHR, textStatus, errorThrown) {
        if (jqXHR.status === 403) {
          Swal.fire("No Autorizado, Iniciar Sesión.", errorThrown, "error");
        } else {
         
         
        }
      });
  });
}

function Extrae_adelantos_Persona() {
  return new Promise(function(resolve, reject) {
    var idpersona = $('#ipersonaspagos').val();
    $.post('../controller/pagojornada/ControllerShowTotalCostoAdelantos.php', { idpersona: idpersona })
      .done(function(resultado) {
        var response = JSON.parse(resultado);
        // Obtener la fecha del servidor y resolver la promesa
        var montototal = response.data;
        resolve(montototal);
      })
      .fail(function(jqXHR, textStatus, errorThrown) {
        if (jqXHR.status === 403) {
           Swal.fire("No Autorizado, Iniciar Sesión.", errorThrown, "error");
        } else {
            Swal.fire("Mensaje de error", errorThrown, "error");
        }
      });
  });
}





//////////////////////////////////////////////////////////////////////////////////////////
///////////////////////SECCION DE REPORTES////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////

var tablevacationreport
function Listar_report_Pagos_Jornada(){
  var fechainicio = $("#FechaInicio").val();
  var fechafin = $("#FechaFin").val();

  tablevacationreport=$('#table_reporte_jornada').DataTable({
        "ordering": false,
        "bLengthChange": false,
        "scrollCollapse": true,
        "searching": {
            "regex": true
        },
         "responsive": true,
        dom: 'Bfrtilp',
         buttons:[{
                extend:    'copy',
                text:      '<i class="fa  fa-copy"></i> ',
                 title: 'REPORTE DE PAGOS EMPLEADOS - 2023',
                titleAttr: 'Exportar a copy'
            },
            {
                extend:    'excelHtml5',
                text:      '<i class="fa fa-file-text-o"></i>',
                 title: 'REPORTE DE PAGOS EMPLEADOS - 2023 ',
                titleAttr: 'Exportar a Excel'
            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="fa fa-file-text" aria-hidden="true"></i>',
                 title: 'REPORTE DE PAGOS EMPLEADOS - 2023',
                titleAttr: 'Exportar a PDF'
            },
            {
                extend:    'print',
                text:      '<i class="fa fa-print"></i> ',
                 title: 'REPORTE DE PAGOS EMPLEADOS - 2023 ',
                titleAttr: 'Imprimir'
            }],
        "lengthMenu": [
            [10, 20, 50, 100, -1],
            [10, 20, 50, 100, "All"]
        ],
        "pageLength": 10,
        "destroy": true,
        "async": false,
        "processing": true,
        "ajax": {
            "url": "../controller/pagojornada/controllerGetReportPagos.php",
            type: 'POST',
            data:{fechainicio:fechainicio,fechafin:fechafin}
        },
        "columns": [
        { "data": null,"render": function (data, type, row, meta) {return meta.row + 1; }},
        { "data": "idpersona" },
        {"data": null, "render": function (data, type, row) {return row.Nombre + ' ' + row.Apellidos;}},
        { "data": "dni"},
        { "data": "montoP"},
        { "data": "Description" },
        {"data": "fechasPagados"},
        { "data": "fechaoperacion"},
        { "data": "type"}

        ],
        "language": idioma_espanol,
        select: true
    });
    document.getElementById("table_reporte_jornada_filter").style.display = "none";
    $('input.global_filter').on('keyup click', function() {
      filterreportvacations();
        
    });
   
    tablevacationreport.column( 1 ).visible( false );
     $('#btn-place').html(tablevacationreport.buttons().container());

}

function filterreportvacations() {
  $('#table_reporte_jornada').DataTable().search($('#global_filter').val(), ).draw();
}


////////////////////////////////////////////////////
////////////////////////////////seccionde imprimi///////////
$('#tabla_pagos_jornadas').on('click', '.Imprimir', function() {
var data = tablehertra.row(tablehertra.row(this).child.isShown() ? this :
    $(this).parents('tr')).data();

     $("#modapagosimprimir").modal({
        backdrop: 'static',
          keyboard: false
      })

      $("#idpersonaimprimir").val(data.idpersona);
      cargarFechas(data.idpersona);
      $('#modapagosimprimir').modal('show');
   
  });



 var datesJonades;
function cargarFechas(idpersona) {
  $.post('../controller/pagojornada/ControllerComboDatesPagados.php', { idpersona: idpersona })
    .done(function (resultado) {
      try {
        var response = JSON.parse(resultado);
         var cadena = "";
        if (response.data.length>0) {
          datesJonades = response.data;
          const fechasOperacionUnicas = [...new Set(datesJonades.map(item => item.fechaoperacion))];
          for (var i = 0; i < fechasOperacionUnicas.length; i++) {
            cadena += "<option value='" + fechasOperacionUnicas[i] + "'>" + fechasOperacionUnicas[i] + "</option>";
          }
          $("#fechaimprimir").html(cadena);
        } else cadena += "<option value=''>NO HAY FECHAS PAGADOS </option>";
       
         $("#fechaimprimir").html(cadena);
      } catch (error) {
        console.error("Error al parsear la respuesta JSON:", error);
      }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      console.error("Error en la solicitud AJAX:", textStatus, errorThrown);
    });
}

function toggleIcon() {
  var icono = document.getElementById("icono");
  icono.classList.remove("fa-print"); // Elimina la clase actual si existe
  icono.classList.add("fa-refresh"); // Agrega la nueva clase
}
function toggleIcon1() {
  var icono1 = document.getElementById("icono");
  icono1.classList.remove("fa-refresh"); // Elimina la clase actual si existe
  icono1.classList.add("fa-print"); // Agrega la nueva clase
}

function ImprimiReportePago(btn){

  let fechaSeleccionada =$("#fechaimprimir").val();
  if(fechaSeleccionada.length !=0){
   toggleIconChange(btn);
    let companny = localStorage.getItem("companny");
    if(!companny) return  Swal.fire("Mensaje de error",'No se encontro datos de la empresa.', "error");
    companny = JSON.parse(companny);
    let url= '../controller/export/Controller-print-payment-pdf.php';
     console.log(datesJonades)
     filtrarRegistrosPorFecha(datesJonades, fechaSeleccionada,btn,companny,url);   
  }
    
}




function ActualizarStatePagosbackgroundRunn(){
$.post('../controller/pagojornada/ControllerbackgroundRunn.php')
      .done(function(resultado) {
        var response = JSON.parse(resultado);
         if (response==1) {
        } else {
        }
      })
       .fail(function(jqXHR, textStatus, errorThrown) {
        if (jqXHR.status === 403) {
          Swal.fire("Mensaje de error", "No Autorizado, Iniciar Sesión.", "error");
        } else {
          Swal.fire("Mensaje de error", errorThrown, "error");
        }
      });

}


function printing() {

      var printTiket = window.open('', '_blank');
      printTiket.document.write(`
          <!DOCTYPE html>
          <html lang="es">
          <head>
              <meta charset="UTF-8">
              <meta name="viewport" content="width=device-width, initial-scale=1.0">
              <title>Tiket de pago</title>
          </head>
          <body>
               ${document.getElementById('modal_tiket').querySelector('.modal-body').innerHTML}
                
          </body>

          </html>
      `);
      printTiket.document.close();
      printTiket.print();
      printTiket.close();
       $('#modal_tiket').modal('hide');
  }

  function ShowPrintModalTiket(pinter){
    var dateCurrent = new Date();
    $("#modal_tiket").modal({
        backdrop: 'static',
          keyboard: false
      })
      $("#peopeleNames").text("Nombres : "+pinter.people);
      $("#salaryPeople").text("Salario : "+pinter.salary+" "+ (pinter.typeMoney === 'SOLES' ? 'S/.' : pinter.typeMoney));
      $("#currentDate").text("Fecha :"+dateCurrent.toLocaleString());
       // Limpiar el contenido del tbody
       $("#tabla_body").empty();
        var total = 0;

    $.each(pinter.data, function(index, item) {
    total += parseFloat(item.monto);
    
     var fila = '<tr style="white-space: nowrap;">';
      fila += '<td style="text-align: left">' + item.fecha + '</td>';
      fila += '<td style="text-align: center; vertical-align: middle; width: 100%;">' + item.mesSeleccionado + '</td>';
      fila += '<td style="text-align: right;">' + parseFloat(item.monto).toFixed(2) + ' ' + (pinter.typeMoney === 'SOLES' ? 'S/.' : pinter.typeMoney) + '</td>';
      fila += '</tr>';
      $("#tabla_body").append(fila);
   });

    var addAmount = parseFloat(pinter.addAmount) || 0;
    var extAmount = parseFloat(pinter.extAmount) || 0;
   var total_acum = addAmount + extAmount + parseFloat(total) || 0;


    $("#amout_adelantos").text( addAmount.toFixed(2) + ' ' + (pinter.typeMoney === 'SOLES' ? 'S/.' : pinter.typeMoney));
    $('#hrExtraAmount').text(extAmount.toFixed(2) + ' ' + (pinter.typeMoney === 'SOLES' ? 'S/.' : pinter.typeMoney));
    $("#amout_total").html('<strong>' + total_acum.toFixed(2) + ' ' + (pinter.typeMoney === 'SOLES' ? 'S/.' : pinter.typeMoney) +'</strong>');
    // Mostrar el modal
    $('#modal_tiket').modal('show');
  
  }

