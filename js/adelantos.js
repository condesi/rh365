var tablepersona;

function Listar_Personas(){

   tablepersona=$('#tabla_personas').DataTable({
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
        "pageLength": 5,
        "destroy": true,
        "async": false,
        "processing": true,
        "ajax": {
            "url": "../controller/adelanto/ControllerGetPersonasOn.php",
            type: 'POST'
        },
        "columns": [
          { "data": null,"render": function (data, type, row, meta) {return meta.row + 1; }},
          {"data": "idpersona" },
          {"data": null, "render": function (data, type, row) {return row.Nombre + ' ' + row.Apellidos;}},
          {"data": "Dni"},
          {"data": "Salario"},
          {"data": "resulentrevista"},
          { "data": "Estado ",
           "render": function (data, type, row) {
            return "<span class='badge badge-pill " + (row.Estado == "Activo" ? "badge-primary" : "badge-warning") + "'>" + row.Estado + "</span>";
          }},
          {
            "defaultContent": "<button   class='adelanto btn btn-primary btn-sm'><i class='fa fa-plus-circle' title='Dar'></i></button>"+
            "&nbsp;<button  class='vizualizar btn btn-secondary btn-sm'><i class='fa fa-eye' title='Vizualizar'></i></button>"
        }

        ],
        "language": idioma_espanol,
        select: true
    });
    document.getElementById("tabla_personas_filter").style.display = "none";
    $('input.global_filter').on('keyup click', function() {
        filterGlobal();
    });
    $('input.column_filter').on('keyup click', function() {
        filterColumn($(this).parents('tr').attr('data-column'));
    });
     tablepersona.column(1).visible( false );
      $('#btn-place').html( tablepersona.buttons().container());
}

function filterGlobal() {
    $('#tabla_personas').DataTable().search($('#global_filter').val(), ).draw();
}


$('#tabla_personas').on('click', '.adelanto', function() {
    var data = tablepersona.row($(this).parents('tr')).data();
   
    if (tablepersona.row(this).child.isShown()) {
        var data = tablepersona.row(this).data();
    }
    var idpersona= data.idpersona;


     $("#modaladelantos").modal({
        backdrop: 'static',
          keyboard: false
      })
     // document.querySelector('#fromIncripts').reset();
     //Subiendo al vista datos//
      $("#idpersonaadelanto").val(idpersona);
      $("#salarioactual").val(data.Salario);
      
      $("#labelSalarioacutal").html(data.Salario);
      $("#labelDatosPersonales").html(data.Nombre+','+data.Apellidos);

      $("#tbody_tabla_detall").html('');
      
       $('#totalMontoCell').text('');
      $('#modaladelantos').modal('show');

      Extraer_Adelantos_Personal(idpersona);
     
});

function Extraer_Adelantos_Personal(idpersona){
$.ajax({
      url:'../controller/adelanto/ControllerGetAdelantosPersona.php',
       type:'POST',
             data:{idpersona:idpersona}
        }).done(function(resultado){
          var data = JSON.parse(resultado);
          var resquest =data.data;
          var totalMonto = 0;
          var html ="";
          if(resquest.length > 0) { 
            $.each(resquest, function(i, item) {
    
              html += "<tr id='"+item.iddelantos+"'>";  
              html += "<td>" + (i + Number(1)) + "</td>";
              html += "<td hidden>0</td>";

              html += "<td>" + item.Fecharegisto + "</td>";
              html += "<td class='monto-cell'>" + item.Monto + "</td>";
              html += "<td>" + item.yearactual + "</td>";
              html += "<td><button class='btn btn-sm' onclick='remove(this)' data-id='" + item.iddelantos + "'><em class='fa fa-times'></em></button></td>";
              html += "</tr>";
              totalMonto += parseFloat(item.Monto);
             });
             $("#tbody_tabla_detall").append(html);
             // Agregar la fila con el total de Monto
            $('#totalMontoCell').text(totalMonto);
            } else {
             $("#tbody_tabla_detall").append('<tr><td></td><td>No Tiene adelantos.</td><td></td></tr>');
           }
         
       })
      }

function RegistrarNuevoAdelanto(){
  var montoadelanto =$("#montoadelanto").val();
  var salarioactual =$("#salarioactual").val();
  var idpersona =$("#idpersonaadelanto").val();
  if (parseFloat(montoadelanto) <= 0 || parseFloat(montoadelanto)>parseFloat(salarioactual)){
    Swal.fire({position: 'top-end', icon: 'warning', title: 'Advertencia.',text: 'Monto debe ser mayor a: '+montoadelanto+ ' y monto no debe superar el salario actual '+salarioactual+' .',showConfirmButton: false,timer: 1900})
    return;
  }

  if (montoadelanto && idpersona) {
      $.post('../controller/adelanto/ControllerRegistrarAdelanto.php', { montoadelanto:montoadelanto,idpersona:idpersona })
      .done(function(resultado){
        var request = JSON.parse(resultado);
          if (request.data==1) {
            
            add_tr_data_New(montoadelanto,request.ultimoID);
            Swal.fire({ position: 'top-end', icon: 'success', title: 'Éxito.', text: request.msg, showConfirmButton: false, timer: 1500 });
            $("#montoadelanto").val('');
            }else{
             Swal.fire({ position: 'top-end', icon: 'error', title: 'Error.', text: request.msg, showConfirmButton: false, timer: 1500 });
            }
          });

  }else{
   Swal.fire({position: 'top-end', icon: 'warning', title: 'Advertencia.',text: 'Indice no identificado.',showConfirmButton: false,timer: 1500})
  }
}

function  add_tr_data_New(montoadelanto,ultimoID){
var currentDate = new Date(); // Obtener la fecha actual
    var fecha = currentDate.toLocaleDateString(); // Convertir la fecha en formato de cadena
    var year = currentDate.getFullYear(); // Obtener el año actual
       var html ="";
           html += "<tr id='"+ultimoID+"' >";  
           html += "<td>✔</td>";
           html += "<td hidden>0</td>";
           html += "<td>" + fecha + "</td>";
           html += "<td  class='monto-cell'>"+montoadelanto+"</td>";
           html += "<td>" + year + "</td>";
           html += "<td><button class='btn btn-sm' onclick='remove(this)' data-id='" + ultimoID + "'><em class='fa fa-times'></em></button></td>";
           html += "</tr>";
            $('#tbody_tabla_detall').append(html);
        
        //Actualizar monto
        var totalActual = parseFloat($('#totalMontoCell').text());
        var resultado = totalActual + new Number(montoadelanto);
        if (!isNaN(resultado)  && resultado !== undefined) {
            $('#totalMontoCell').text(resultado);
          } 
                 
    }
   



function remove(t) {
  var row = t.parentNode.parentNode;
  var iddelantos = row.getAttribute('id').slice(0);
   var idalterno = t.getAttribute("data-id");
   //console.log(iddelantos+'--'+idalterno);
  var idepersona = $("#idpersonaadelanto").val();

  if (!isNaN(iddelantos) && !isNaN(idepersona)) {
    $.post('../controller/adelanto/ControllerQuitarAdelanto.php', { iddelantos: iddelantos, idepersona: idepersona })
      .done(function(resultado) {
        var request = JSON.parse(resultado);

        if (request.data == 1) {
          var monto = parseFloat($(t).closest('tr').find('.monto-cell').text());
          var totalActual = parseFloat($('#totalMontoCell').text());
          var resultado = totalActual - monto;

          if (!isNaN(resultado) && resultado >= 0 && resultado !== undefined) {
            $('#totalMontoCell').text(resultado);
          }

          Swal.fire({ position: 'top-end', icon: 'success', title: 'Éxito.', text: request.msg, showConfirmButton: false, timer: 1500 });
          row.remove();
        } else {
          Swal.fire({ position: 'top-end', icon: 'error', title: 'Error.', text: request.msg, showConfirmButton: false, timer: 1500 });
        }
      });
  } else {
    Swal.fire({ position: 'top-end', icon: 'warning', title: 'Advertencia.', text: 'Indice no identificado.', showConfirmButton: false, timer: 1500 });
  }
}




//////////////////////////////////////////////////
////////////////////////////////////////////
///////////////////////////////////////////////





$('#tabla_personas').on('click', '.vizualizar', function() {
    var data = tablepersona.row($(this).parents('tr')).data();
   
    if (tablepersona.row(this).child.isShown()) {
        var data = tablepersona.row(this).data();
    }
    var idpersona= data.idpersona;


     $("#modalreportesadelantos").modal({
        backdrop: 'static',
          keyboard: false
      })

      $("#idempleadoextrapago").val(idpersona);
      
      $('#total_acumulado').text('');

     ShowAdelantosPersonaImprimir(idpersona);
      $('#modalreportesadelantos').modal('show');
     
});

function ShowAdelantosPersonaImprimir(idpersona){

 var showtable =$('#tableAdelantroslist').DataTable({
     "footerCallback": function(row, data, start, end, display) {
      var api = this.api();
      var monTotal = api
        .column(3)
        .data()
        .reduce(function(a, b) {
          return parseFloat(a) + parseFloat(b);
        }, 0);

      $(api.column(3).footer()).html('Total: ' + monTotal.toFixed(2));
    },
      "ordering": true,
      "bLengthChange": false,
      "scrollCollapse": true,
      "searching": {
        "regex": true
      },
       "responsive": true,
        dom: 'Bfrtilp',
       
         buttons: ['print', 'pdf', 'csv', 'excel'],
      "lengthMenu": [
      [10, 20, 50, 100, -1],
      [10, 20, 50, 100, "All"]
      ],
      "pageLength": 8,
      "destroy": true,
      "async": false,
      "processing": true,
      "ajax": {
        "url": "../controller/adelanto/controllerShowGetAdelatosReport.php",
        type: 'POST',
        data:{idpersona:idpersona}
      },
      "columns": [
       { "data": null, "render": function (data, type, row, meta) {
        return meta.row + 1; // Agrega el número de registro
        }},
       { "data": "Fecharegisto"},
      { "data": "Moneda"},
      { "data": "Monto"},
         
      {
        "data": "Estado",
        render: function(data, type, row) {
          if (data == 'Activo') {
            return "<span class='badge badge-pill badge-primary'>" + data + "</span>";
          } else {
            return "<span class='badge badge-pill badge-warning'>" + data + "</span>";
          }
        }
      }, { "data": "yearactual"},
        ],
      "language": idioma_espanol,
      select: true
    });
  document.getElementById("tableAdelantroslist_filter").style.display = "none";
    $('input.global_filter').on('keyup click', function() {
      
    });
   // Agregar clase CSS a la fila del total
  var footerNode = showtable.table().footer();
  $(footerNode).addClass('total-row');

}


//////////////////////////////////////////////////////////////////////////////////////////
///////////////////////SECCION DE REPORTES////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////


var tableglobal
function Listar_report_adelantos(){
  var fechainicio = $("#FechaInicio").val();
  var fechafin = $("#FechaFin").val();

  tableglobal=$('#table_reporte_adelantos').DataTable({
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
            "url": "../controller/adelanto/controllerGetReportadelantos.php",
            type: 'POST',
            data:{fechainicio:fechainicio,fechafin:fechafin}
        },
        "columns": [
        { "data": null,"render": function (data, type, row, meta) {return meta.row + 1; }},
        { "data": "idpersona" },
        {"data": null, "render": function (data, type, row) {return row.Nombre + ' ' + row.Apellidos;}},
        { "data": "Dni"},
        { "data": "Salario"},
        { "data": "Monto" },
        {"data": "Fecharegisto"},
        { "data": "Estado"}

        ],
        "language": idioma_espanol,
        select: true
    });
    document.getElementById("table_reporte_adelantos_filter").style.display = "none";
    $('input.global_filter').on('keyup click', function() {
      filterreportadelant();
        
    });
   
    tableglobal.column( 1 ).visible( false );
     $('#btn-place').html(tableglobal.buttons().container());

}

function filterreportadelant() {
  $('#table_reporte_adelantos').DataTable().search($('#global_filter').val(), ).draw();
}
