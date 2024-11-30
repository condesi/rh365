

var tablepersona;
var idpersonasselect = [];

function ListarPersonasAsistencia(date) {
  tablepersona = $('#tabla_persona_asistencia').DataTable({
    "ordering": true,
    "bLengthChange": false,
    "scrollCollapse": true,
    "searching": { "regex": true },
    "lengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "All"]],
    "pageLength": 8,
    "destroy": true,
    "async": false,
    "processing": true,
    "ajax": { "url": "../controller/asistencia/ControllerGetPersonalOn.php", "type": 'POST', data: { date: date } },
    "columns": [
      { "data": null, "render": function (data, type, row, meta) { return meta.row + 1; } },
      { "data": "idpersona" },
      { "data": null, "render": function (data, type, row) { return row.Nombre + ' ' + row.Apellidos; } },
      { "data": "Dni" },
      {
      "data": null,
      "render": function (data, type, row) {
        var isChecked = "";
        if (row.hasOwnProperty("stated")) {
          isChecked = row.stated == true || row.stated == 1 ? "checked" : "";

          if (isChecked) {
            if (!idpersonasselect.includes(row.idpersona)) {
              idpersonasselect.push(row.idpersona);

            }
          } else {
            var index = idpersonasselect.indexOf(row.idpersona);
            if (index != -1) {
              idpersonasselect.splice(index, 1);
            }
          }
        }

          return '<div class="toggle"><label><input type="checkbox" class="select-checkbox" value="' + row.idpersona + '" ' + isChecked +
            '><span class="button-indecator"></span></label></div>';
        }
      }
    ],
    "language": idioma_espanol,
  });

 

  document.getElementById("tabla_persona_asistencia_filter").style.display = "none";
  $('input.global_filter').on('keyup click', function () { });

  tablepersona.column(1).visible(false);

  $("#btn_bucar_data").html('<em class="fa fa-search"></em>');

}



function Registrar_Asistencia(){
var fecha =$("#fechaasistencia").val();

// Eliminar elementos duplicados del array idpersonasselect
idpersonasselect = Array.from(new Set(idpersonasselect));

// Realizar el registro con los valores únicos en idpersonasselect
if(fecha.length==0 || idpersonasselect.length==0){
return Swal.fire("Mensaje de advertencia", "Falta seleccionar la asistencia o la fecha de asistencia.", "warning");
}else{
  $.post(editando === false ? '../controller/asistencia/ControllerRegistrarAsistencia.php' :
   '../controller/asistencia/ControllerActualizarAsistencia.php', { idpersonasselect: idpersonasselect.toString(), fecha: fecha })
      .done(function(resultado) {
        var request = JSON.parse(resultado);

        if (request.data == 1) {
          resetearCheckboxes();
         
          Swal.fire({ position: 'top-end', icon: 'success', title: 'Éxito.', text: request.msg, showConfirmButton: false, timer: 1500 });
        
        } else {
          return Swal.fire("Mensaje De error", request.msg, "error"); 
        }
      }).fail(function(jqXHR, textStatus, errorThrown) {
  if (jqXHR.status === 403) {
   
    return Swal.fire("Mensaje de error", "No Autorizado"+jqXHR.url+",Iniciar Sessión.", "error");
  } else {
    
    return Swal.fire("Mensaje de error", errorThrown, "error");
  }
});
}
}

function resetearCheckboxes() {
   $("#fechaasistencia").val('');
  $('.cheboktem').prop('checked', false);
   $("#btn_bucar_data").hide();
    editando=false;
 // Resetear checkboxes individuales visibles en la página actual
  tablepersona.rows({page: 'current', search: 'applied'}).nodes().to$().find('.select-checkbox').prop('checked', false);
  // Resetear checkbox general visible en la página actual
  $('#tabla_persona_asistencia').find('#check-todo').prop('checked', false);
  // Resetear checkboxes individuales ocultos en páginas colapsadas
  tablepersona.rows({page: 'all', search: 'applied'}).nodes().to$().find('.select-checkbox').prop('checked', false);
  // Actualizar el vector de personas seleccionadas
  idpersonasselect = [];

}


$('#tabla_persona_asistencia').on('click', '.select-checkbox', function () {
  var idPersona = $(this).val();
  if ($(this).is(':checked')) {
    idpersonasselect.push(idPersona);
  } else {
    var index = idpersonasselect.indexOf(idPersona);
    if (index != -1) {
      idpersonasselect.splice(index, 1);
    }
  }
});


// Agregar evento de clic al checkbox general
$(document).on('click', '#check-todo', function () {
  var isChecked = $(this).is(':checked');
  $('.select-checkbox').prop('checked', isChecked);
  if (isChecked) {
    idpersonasselect = [...new Set([...idpersonasselect, ...tablepersona.column(1).data().toArray()])];
  } else {
    idpersonasselect = [];
  }
});

// Actualizar el checkbox general cuando se seleccionan/deseleccionan las checkboxes individuales
$(document).on('click', '.select-checkbox', function () {
  var isChecked = $(this).is(':checked');
  var allChecked = $('.select-checkbox').length == $('.select-checkbox:checked').length;
  $('#check-todo').prop('checked', allChecked);
  var idPersona = $(this).val();
  if (isChecked) {
    idpersonasselect.push(idPersona);
  } else {
    var index = idpersonasselect.indexOf(idPersona);
    if (index != -1) {
      idpersonasselect.splice(index, 1);
    }
  }
});




function Listar_Personal_Asistencia_edit() {

  var fecha=$("#fechaasistencia ").val();

     if ( fecha?.length==0 ) {
       return Swal.fire("Mensaje de advertencia", "Ingrese la fecha que desea modificar la asistencia", "warning");
       }
       $("#btn_bucar_data").html("<em class='fa fa-spin fa-refresh'></em>");
        //$('#btn_bucar_data').prop('disabled',true);

        ListarPersonasAsistencia(fecha);
} 

var editando=false;
function  All_Editar_Nuevo(e){
  if (e.checked) {
     idpersonasselect = [];
    tablepersona.clear().draw();
    $("#btn_bucar_data").show();
    $("#fechaasistencia").val('');
    editando=true;
} else {
    $("#btn_bucar_data").hide();
    editando=false;
    ListarPersonasAsistencia();
}
}
 


//////////////////////////////////////////////////////////////////////////////////////////
///////////////////////SECCION DE REPORTES////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////




var tableglobal
function Listar_report_asistencia(){
  var fechainicio = $("#FechaInicio").val();
  var fechafin = $("#FechaFin").val();

  tableglobal=$('#table_reporte_asistencia').DataTable({
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
                 title: 'REPORTE DE ADELANTOS EMPLEADOS - 2023',
                titleAttr: 'Exportar a copy'
            },
            {
                extend:    'excelHtml5',
                text:      '<i class="fa fa-file-text-o"></i>',
                 title: 'REPORTE DE ADELANTOS EMPLEADOS - 2023 ',
                titleAttr: 'Exportar a Excel'
            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="fa fa-file-text" aria-hidden="true"></i>',
                 title: 'REPORTE DE ADELANTOS EMPLEADOS - 2023',
                titleAttr: 'Exportar a PDF'
            },
            {
                extend:    'print',
                text:      '<i class="fa fa-print"></i> ',
                 title: 'REPORTE DE ADELANTOS EMPLEADOS - 2023 ',
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
            "url": "../controller/asistencia/controllerGetReportasistencia.php",
            type: 'POST',
            data:{fechainicio:fechainicio,fechafin:fechafin}
        },
        "columns": [
        { "data": null,"render": function (data, type, row, meta) {return meta.row + 1; }},
        { "data": "idpersona" },
        {"data": null, "render": function (data, type, row) {return row.Nombre + ' ' + row.Apellidos;}},
        { "data": "dni"},
        { "data": "Fechas"},
        {
            "data": "stated",
            render: function(data, type, row) {
                if (data == '1') {
                    return "<span class='badge badge-pill badge-primary'>Asistio</span>";
                } else {
                    return "<span class='badge badge-pill badge-primary'>Falto</span>";
                }
            }
        },
        { "data": "yearid"}
        ],
        "language": idioma_espanol,
        select: true
    });
    document.getElementById("table_reporte_asistencia_filter").style.display = "none";
    $('input.global_filter').on('keyup click', function() {
      filterreportasistencia();
        
    });
   
    tableglobal.column( 1 ).visible( false );
     $('#btn-place').html(tableglobal.buttons().container());

}

function filterreportasistencia() {
  $('#table_reporte_asistencia').DataTable().search($('#global_filter').val(), ).draw();
}