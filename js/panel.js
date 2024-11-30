function List_Panel(){
   table_panel=$('#table_panel').DataTable(
   {
       ...datatableConfig,
        "pageLength": 10,
        "destroy": true,
        "async": false,
        "processing": true,
        dom: 'Bfrtilp',
         buttons:[{
                extend:    'copy',
                text:      '<i class="fa  fa-copy"></i> ',
                 title: 'RESUMEN DE ASISTENCIA GENERALES',
                titleAttr: 'Exportar a copy'
            },
            {
                extend:    'excelHtml5',
                text:      '<i class="fa fa-file-text-o"></i>',
                 title: 'RESUMEN DE ASISTENCIA GENERALES ',
                titleAttr: 'Exportar a Excel'
            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="fa fa-file-text" aria-hidden="true"></i>',
                 title: 'RESUMEN DE ASISTENCIA GENERALES',
                titleAttr: 'Exportar a PDF'
            },
            {
                extend:    'print',
                text:      '<i class="fa fa-print"></i> ',
                 title: 'RESUMEN DE ASISTENCIA GENERALES ',
                titleAttr: 'Imprimir'
            }],
        "ajax": createAjaxConfig('#table_panel', 'entry', 'ControllerPanelSumarry.php'),
        "columns": [
          { "data": null,"render": function (data, type, row, meta) {return meta.row + 1; }},
          {"data": "user_id" },
          {"data": "name"},
          {"data": "lastname"},
          {"data": "code"},
          {"data": "type"},
          {"data": "month"},
          {"data": "total_time"},
           {"defaultContent": "<button type='button' class='detalls btn btn-warning btn-sm' title='Detalles..'><i class='fa fa-search' ></i></button>"
           }              
        ],
        select: true
    });
    document.getElementById("table_panel_filter").style.display = "none";
    $('input.global_filter').on('keyup click', function() {
        filterGlobal();
    });
   
     table_panel.column(1).visible( false );
      $('#btn-place').html(table_panel.buttons().container());

}
function filterGlobal() {
    $('#table_panel').DataTable().search($('#global_filter').val(), ).draw();
}

$('#table_panel').on('click', '.detalls', function() {
    var data = table_panel.row(table_panel.row(this).child.isShown() ? this :
    $(this).parents('tr')).data();

   $("#modal_hoursDetall").modal({
          backdrop: 'static',
          keyboard: false
        })
        listHoursDetall(data.user_id);
       $('#modal_hoursDetall').modal('show');
})



var table_detall;

function listHoursDetall(id_user) {
    table_detall = $('#table_Detall').DataTable({
        ...datatableConfig,
        "pageLength": 5,
        "destroy": true,
        "processing": true,
         "responsive": true,
        dom: 'Bfrtilp',
         buttons: ['print', 'pdf', 'excel'],
        "ajax": {
            "url": "../controller/entry/ControllerPanelSumarryByUser.php",
            "type": 'POST',
            "data": {"id_user": id_user} // Formatear el parámetro correctamente
        },
        "columns": [
            {"data": null, "render": function (data, type, row, meta) {return meta.row + 1; }},
            {"data": "name_shift"},
            {"data": "date"},
             {"data": "name_shift"},
            {"data": "time_entry"},
            {"data": "time_exit"},
             {"data": "total_time"},
            {"data": "day_number"},
        ],
        select: true
    });

    document.getElementById("table_Detall_filter").style.display = "none";
    $('input.global_filter').on('keyup click', function() {
        // Lógica para filtrar la tabla
    });

    table_detall.column(1).visible(false);
}

async function runBackgroundAsync() {
  try {
    const respuesta = await fetch('../controller/entry/ControllerRunBackgroundAsyn.php');
    const resultado = await respuesta.json();
    if(resultado.auth){
      
    }else  Swal.fire("Mensaje de error", errorThrown, "error");
      } catch (error) {
        if (error instanceof SyntaxError) {
          console.error();
          Swal.fire("Mensaje de error", 'Error al intentar actualizar horas de turnos.', "error")
        } else {
          Swal.fire("Mensaje de error", error, "error")
        }
      }
    }