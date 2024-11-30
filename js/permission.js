//Listar tipos de permisos
var tbl_permission;

function list_Permission(params) {
   var params = {
  date_init: $("#date_ini").val() || null,
  date_end: $("#date_end").val() || null,
  search: $("#global_filter").val()
 
};

tbl_permission = $('#tbl_categoryPermission').DataTable({
    ...datatableConfig,
    "pageLength": 10,
    "destroy": true,
    "processing": true,
    "ajax": createAjaxConfig('#tbl_categoryPermission', 'permission', 'ControllerGetCategory.php',params),
    "columns": [
     { "data": null, "render": function (data, type, row, meta) { return meta.row + 1; } },
    { "data": "id" },
    { "data": "name" },
    { "data": "description" },
    { "data": "number_day" },
    { "data": "status" },
    {
     "defaultContent": "<button  class='edit_category btn btn-info btn-sm'><i class='fa fa-edit' title='editar'></i></button>"
    }

    ],
    "language": idioma_espanol,
    select: true
  });
  document.getElementById("tbl_categoryPermission_filter").style.display = "none";
  $('input.global_filter').on('keyup click', function () {
    filtertcategoryPermission();
  });

 tbl_permission.column(1).visible(false);

}
function filtertcategoryPermission(){

  $('#tbl_categoryPermission').DataTable().search($('#global_filter').val()).draw();
}

$('#tbl_categoryPermission').on('click', '.edit_category', function () {
  var data_category = tbl_permission.row(tbl_permission.row(this).child.isShown() ? this : $(this).parents('tr')).data();
  $.get('../controller/permission/ControllerShowPermission.php', { id: data_category.id })
    .done(function (result_category) {
      var response_category = JSON.parse(result_category);
      if (response_category.status) showCategory(response_category.data);
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      if (jqXHR.status === 403) {
        Swal.fire("Mensaje de error", "No Autorizado.", "error");
      } else {
        Swal.fire("Mensaje de error", errorThrown, "error");
      }
    });
});

var cate_pers={};
function showCategory(type){
 if (type && Object.keys(type).length > 0) {
    $("#modal_category_permiss").modal({
      backdrop: 'static',
      keyboard: false
    });
    editing_category = true;
    cate_pers.id_category = type.id;
    $("#name_category").val(type.name);
    $("#day_permission").val(type.number_day);
    $("#status_permiss").val(type.status);
    $("#description_permiss").val(type.description);
    $("#modal_category").modal({
      backdrop: 'static',
      keyboard: false
    });
    $("#tituloModalCategory").text('Editando : ' + type.name);
    $('#modal_category_permiss').modal('show');
  } else {
    Swal.fire("Mensaje de error", "No se pudieron cargar los datos de la categoría.", "error");
  }
}

function Register_type(btn){
	var data_type = {
    id: cate_pers.id_category,
    name: $("#name_category").val().toUpperCase(),
    day: $("#day_permission").val(),
    status: $("#status_permiss").val(),
    description: $("#description_permiss").val()
  };

   if (data_type.name.length === 0) return nameErrorCategory.textContent = 'El nombre de la categoría es requerido.';
   nameErrorCategory.textContent='';
   if (data_type.day.length === 0) return nameErrorDay.textContent = 'la cantidad de dias son requerridos.';
   nameErrorDay.textContent='';

toggleIconChange(btn)
$.post('../controller/permission/ControllerUpdateOnRegister.php', data_type)
    .done(function (result) {
      var response = JSON.parse(result);
      if (response.status) {
        Swal.fire({ position: 'top-end', icon: 'success', title: 'Éxito !!', text: response.msg, showConfirmButton: false, timer: 1500 });
        tbl_permission.ajax.reload();
        $('#modal_category_permiss').modal('hide');
        cleanInputValuesPermission();
      } else {
        Swal.fire("Mensaje de error", response.msg, "error");
      }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      if (jqXHR.status === 403) {
        Swal.fire("Mensaje de error", "No Autorizado.", "error");
      } else {
        Swal.fire("Mensaje de error", errorThrown, "error");
      }
    });

toggleIconRollback(btn);

}
function cleanInputValuesPermission(){
    cate_pers.id_category = '';
    $("#name_category").val('');
    $("#day_permission").val('');
    $("#status_permiss").val('');
    $("#description_permiss").val(''); 
    nameErrorCategory.textContent='';
    nameErrorDay.textContent='';
}

/////////////////////////////////////
////Lista de personal///////////////
///////////////////////////////////

var tlb_personalPermission;
function Listar_Personas_Permission(){
  tlb_personalPermission=$('#tbl_permission').DataTable({
    "ordering": false,
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
      "url": "../controller/permission/ControllerGelPeroplesOn.php",
      type: 'POST' 
    },
    
    "columns": [
    { "data": null,"render": function (data, type, row, meta) {return meta.row + 1; }},
    { "data": "idpersona"},
     {"data": null, "render": function (data, type, row) {return row.Nombre + ' ' + row.Apellidos;}},
    { "data": "name_permiss"},
    { "data": "period"},
    { "data": "quantity"},
    {"data": 'null', "render": function (data, type, row) {return  row.number_day != null ? '('+row.number_day + ' /' + row.availability+')' :'' ; }},
    { "data": "isactive",
      render: function(data, type, row) {
        if (data == 2) return "<span class='badge badge-pill badge-warning'>P. Compled</span>";
        if (data == 1) return "<span class='badge badge-pill badge-primary'>En permiso</span>";
        if (data==null) return "";
      },
    },

    { "data": "isactive",
          render: function(data, type, row) {
                if (data == 1) {
                    return "<button  class='send_permission btn btn-primary btn-sm'><i class='fa fa-plus-circle' ></i></button>&nbsp;<button  class='permiss_edit btn btn-secondary btn-sm'><i class='fa fa-edit' ></i></button>";
                    
                } else {
                    return "<button  class='send_permission btn btn-primary btn-sm'><i class='fa fa-plus-circle' ></i></button>";
                }
            }}
            
  
    ,
    ],
    "language": idioma_espanol,
    select: true
  });
  document.getElementById("tbl_permission_filter").style.display = "none";
  $('input.global_filter').on('keyup click', function() {
    filterGlobal();
  });
  tlb_personalPermission.column( 1).visible( false );

}

function filterGlobal() {
  $('#tbl_permission').DataTable().search($('#global_filter').val(), ).draw();
}


function removeImageRoom(index) {
  document.getElementById(`photoRoom${index}`).value = '';
  document.getElementById(`previewRoom${index}`).src = '';
  permission.isDeletePhoto = true;
  document.getElementById('removeButton').style.display = 'none';

}
function previewImageRoom(input, index, roomphoto = null) {
  var preview = document.getElementById(`previewRoom${index}`);
  var file = input ? input.files[0] : null;
  if (file || roomphoto) {
    preview.src = file ? URL.createObjectURL(file) : roomphoto;
    document.getElementById('removeButton').style.display = 'block';
  } else {
    console.log('No file selected');
  }
}


var permission={id:'' ,dayConsumess:0,currentPermis:'',daysRestanting:0};

$('#tbl_permission').on('click', '.send_permission', function() {
var data = tlb_personalPermission.row(tlb_personalPermission.row(this).child.isShown() ? this :
    $(this).parents('tr')).data();
    
     $("#permission_modal").modal({
        backdrop: 'static',
          keyboard: false
      })
      typePermission();
       clearInputFrom();
      permission.id_persona=data.idpersona;//id persona
      permission.daysRestanting=data.availability;
      $('#tituloModal').html( data.name_permiss != null ? data.name_permiss+ '('+data.number_day +'/'+data.availability +')' : 'Nuevo permiso');
      $('#people_name_per').val(data.Nombre + ' ' + data.Apellidos);
      $('#address_people').val(data.Direccion);
      $('#day_alibes_per').val(0);
       permission.currentPermis =data.id_tp_per;
      
       previewImageRoom(null,1, "../images/user/default.png");
      $('#permission_modal').modal('show');
   
  });


var typeTemp;
function typePermission(idcategory) {
  $.post('../controller/permission/ControllerGetCategory.php')
  .done(function (resultado) {
    var response = JSON.parse(resultado);
    if (response.status) {
      let { data } = response;
      typeTemp=data;
      var cadena = "<optgroup label='Tipos'> <option value='' selected>--SELECCIONE--</option>  </optgroup>"; // Adding default option
      if (data.length > 0) {
        for (var i = 0; i < data.length; i++) {
          cadena += "<option value='" + data[i].id + "'" +
          (data[i].id == idcategory ? " selected" : "") + ">" + data[i].name + "</option>";
        }
      } else cadena += "<option value=''>NO REGISTERS FOUND</option>";
      $("#id_typePermission").html(cadena);
    }
  })
  .fail(function (jqXHR, textStatus, errorThrown) {
    if (jqXHR.status === 403) {
      Swal.fire("Error Message", "Unauthorized.", "error");
    } else {
      Swal.fire("Error Message", errorThrown, "error");
    }
  });
}

function selectTypePermission(elem){
  var id_type = elem.value;
  if(id_type) showDivsBasedOnType(id_type) ;
   var filteredData = typeTemp.filter(function(item) { return item.id == id_type;  });
  

   if(permission.currentPermis && filteredData[0].id == permission.currentPermis){
    $("#available_label").text(permission.daysRestanting); 
   }else{
     $("#available_label").text(filteredData[0].number_day);
   }

   permission.number_corespondi=filteredData[0].number_day;
   $("#corresponding_days").text("Dias: "+filteredData[0].number_day )

  
}

function permissionCurrent(id_type){
   var filteredData = typeTemp.filter(function(item) { return item.id == id_type;  });

}

function datesInabeles() {
return  [
     new Date('2023-01-01'), // Año Nuevo
     new Date('2023-04-14'), // Viernes Santo
     new Date('2023-05-01'), // Día del Trabajo
     new Date('2023-07-20'), // Día de la Independencia
     new Date('2023-08-07'), // Batalla de Boyacá
     new Date('2023-12-25'), // Navidad
     new Date('2024-01-01'), // Año Nuevo
     new Date('2024-04-05'), // Miércoles Santo
     new Date('2024-05-01'), // Día del Trabajo
     new Date('2024-07-20'), // Día de la Independencia
     new Date('2024-08-07'), // Batalla de Boyacá
     new Date('2024-12-25')  // Navidad
// Agrega más fechas feriadas si es necesario
];
}

function handleTimeChange() {
  var startTime = $("#start_time").val();
  var endTime = $("#end_time").val();



    if (startTime && endTime) {
       // Validate if both times are in correct format (HH:MM) 
      var timePattern = /^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/; 
      if (timePattern.test(startTime) && timePattern.test(endTime)) {
     
      let differenceMinutes= calculateHourDifference(startTime, endTime);
       let  formattedHours= formatTimeDifference(differenceMinutes);

       permission.hoursPermission = formattedHours; //horas de permiso
       $("#remaining_label").text("Per: "+ formattedHours + " Horas");

        //operacion de horas
        var days =permission.number_corespondi;//15
         var restHours= restarMinutosADias(days, differenceMinutes);
         $("#used_label").text(restHours);
        
       } else {
        console.log("Invalid time format. Please use HH:MM format.");
       }
}
}

function restarMinutosADias(dias, totalDeMinutos) {
    // Convertir los días a minutos
    var minutesTotal = dias * 24 * 60;
    // Restar los minutos especificados
    minutesTotal -= totalDeMinutos;
    // Calcular los días restantes
    var diasRestantes = Math.floor(minutesTotal / (24 * 60));
    // Calcular las horas restantes
    var hoursRemaining = Math.floor((minutesTotal % (24 * 60)) / 60);
    // Calcular los minutos restantes
    var minutesRemaining = Math.floor(minutesTotal % 60);
    // Calcular los segundos restantes
    var seconsRemaining = '00';
    var formateHrs= hoursRemaining + ':' + minutesRemaining + ':' + seconsRemaining;

    permission.remainingHours = {'day':diasRestantes,'hours':formateHrs};//dias y horas restantes
    // Formatear la respuesta
    var respuesta = diasRestantes + " Días " + hoursRemaining + ":" + minutesRemaining + ":" + seconsRemaining + " Hrs";
    
    return respuesta;
}



//original
function formatTimeDifference(minutes) {
    var hours = Math.floor(minutes / 60);
    var remainingMinutes = minutes % 60;
    var remainingSeconds = Math.round((minutes % 1) * 60);
    
    if (hours < 0) {
        hours = 0; // Si los minutos son menores a 60, no hay horas restadas
    }
    
    var formattedHours = ('0' + hours).slice(-2);
    var formattedMinutes = ('0' + remainingMinutes).slice(-2);
    var formattedSeconds = ('0' + remainingSeconds).slice(-2);
    
    return formattedHours + ':' + formattedMinutes + ':' + formattedSeconds;
}

function handleDateChange() {
    var startDate = $("#start_date").val();
    var endDate = $("#end_date").val();

    if (startDate && endDate) {
        // Validate if both dates are in correct format (YYYY-MM-DD)
        var datePattern = /^\d{4}-\d{2}-\d{2}$/; // Regular expression for YYYY-MM-DD format
        if (datePattern.test(startDate) && datePattern.test(endDate)) {
           
             let _days = calculateDaysDifference(startDate,endDate);
             permission.daysPermission =_days;
             $("#remaining_label").text("Permiso  "+ _days + " días");

             //operacion con fechas
            
              
              permission.remainingDays = permission.number_corespondi - _days;  //Dias restantes
              console.log(permission)
              $("#used_label").text(permission.remainingDays+ " Días 00:00:00 Hrs");
             

        } else {
            console.log("Invalid date format. Please use YYYY-MM-DD format.");
            // You can display an error message or take other actions as needed
        }
    }
}


function calculateHourDifference(startTime, endTime) {
    var startTimeParts = startTime.split(":");
    var endTimeParts = endTime.split(":");
    var startDate = new Date();
    var endDate = new Date();
    startDate.setHours(parseInt(startTimeParts[0]), parseInt(startTimeParts[1]), 0, 0);
    endDate.setHours(parseInt(endTimeParts[0]), parseInt(endTimeParts[1]), 0, 0);

    // Si startTime es mayor que endTime, ajustamos endDate para el siguiente día
    if (startDate > endDate) {
        endDate.setDate(endDate.getDate() + 1);
    }

    var differenceMilliseconds = endDate.getTime() - startDate.getTime();
    var differenceMinutes = differenceMilliseconds / (1000 * 60); // Convertir a minutos
    return differenceMinutes;
}

function calculateDaysDifference(startDate,endDate) {
  const fechaInicioObjeto = new Date(startDate);
  const fechaFinalObjeto = new Date(endDate);

    // Calcula la diferencia en días
      let diferencia = 0;
      while (fechaInicioObjeto <= fechaFinalObjeto) {
      // Excluye los días sábado, domingo y feriados
      if (
      fechaInicioObjeto.getDay() !== 0 && // Domingo
      fechaInicioObjeto.getDay() !== 6 && // Sábado
      !esFeriado(fechaInicioObjeto)
      ) {
       diferencia++;
      }
      fechaInicioObjeto.setDate(fechaInicioObjeto.getDate() + 1);
    }
    
     return diferencia;
}
// Función para verificar si una fecha es un feriado
function esFeriado(fecha) {
    var fechasFeriados= datesInabeles();
    for (let i = 0; i < fechasFeriados.length; i++) {
      if (
        fecha.getFullYear() === fechasFeriados[i].getFullYear() &&
        fecha.getMonth() === fechasFeriados[i].getMonth() &&
        fecha.getDate() === fechasFeriados[i].getDate()
        ) {
        return true;
    }
    }
    return false;
}


 
 async function register_permission(btn){
    
    permission.type_id = $("#id_typePermission").val();
    permission.startDate = $("#start_date").val() || '';
    permission.endDate = $("#end_date").val() || '';
    permission.startTime = $("#start_time").val() || '';
    permission.endTime = $("#end_time").val() || '';
    permission.photo = $('#photoRoom1')[0].files[0];
   /*
    permission.daysPermission ;//dias de permiso
    permission.hoursPermission;//horas de permiso
    permission.number_corespondi;//dias del tipo del permiso
    permission.id_persona; //id persona
    permission.remainingDays; //dias restantes y horas restantes
    permission.remainingHours; //dias y horas restantes
    */
    console.log(permission)
    const formData = new FormData();
   Object.entries(permission).forEach(([key, value]) => { formData.append(key, value); });
   formData.append("json_remainingHours",JSON.stringify(permission.remainingHours));
    //formData.append("json_remainingDays",JSON.stringify(permission.remainingDays));
   toggleIconChange(btn)
    try {
    const response = await fetch('../controller/permission/ControllerRegister.php', { method: 'POST', body: formData } );

    if (response.ok) {
      const result = await response.json();
      if (result.status) {
        Swal.fire({ position: 'top-end', icon: 'success', title: 'Éxito !!', text: result.msg, showConfirmButton: false, timer: 1500, });
     
         tlb_personalPermission.ajax.reload();
        clearInputFrom();
        $('#permission_modal').modal('hide');
         
      } else {
        Swal.fire('Mensaje de error', result.msg, 'error');
      }
    } else {
      if (response.status === 403) {
        Swal.fire('Mensaje de error', 'No Autorizado.', 'error');
      } else {
        const errorText = await response.text();
        console.log(errorText);
        Swal.fire('Mensaje de error', errorText, 'error');
      }
    }
  } catch (error) {
    Swal.fire('Mensaje de error', error.message, 'error');
  }
  
toggleIconRollback(btn)

 }



$('#tbl_permission').on('click', '.permiss_edit', function() {
var data = tlb_personalPermission.row(tlb_personalPermission.row(this).child.isShown() ? this :
    $(this).parents('tr')).data();

   $.get('../controller/permission/controllerGelPermisionByIdPeople.php',{idpersona:data.idpersona, id_permission:data.id_permission})
      .done(function(resultado) {
       var response = JSON.parse(resultado);
       console.log(response)
        if(response.status){ 
          let {data} =response;
          procesDataPermissionByPeople(data);

        }else{ Swal.fire("Mensaje de error", response.msg, "error");}
      })
      .fail(function(jqXHR, textStatus, errorThrown) {
        if (jqXHR.status === 403) {
          Swal.fire("Mensaje de error","No Autorizado.", "error");
        } else {
          Swal.fire("Mensaje de error", errorThrown, "error");
        }
      });
   
  });

function procesDataPermissionByPeople(perms){
    permission={};
   $("#permission_modal").modal({
        backdrop: 'static',
          keyboard: false
      })
      typePermission(perms.type_id);
      permission.id = perms.id_permission
      permission.id_persona=perms.idpersona;//id persona
      permission.dayConsumess= perms.quantity;
      permission.currentPermis=perms.type_id;
      permission.number_day=perms.number_day;
      permission.daysPermission = perms.quantity;//dias de permiso
     //permission.hoursPermission;//horas de permiso
      permission.number_corespondi= perms.availability;//dias del tipo del permiso
      $("#id_typePermission").prop("disabled", true);
      $("#start_date").prop("disabled", true);
      $("#start_time").prop("disabled", true);
    
     //permission.remainingDays= {'day':perms.availability,'hours':'00:00:00'}; //dias restantes y horas restantes
    //permission.remainingHours; //dias y horas restantes

      $('#tituloModal').html(perms.name_permiss);
      $('#people_name_per').val(perms.Nombre + ' ' + perms.Apellidos);
      $('#address_people').val(perms.Direccion);
      $('#corresponding_days').html(perms.number_day);
      $('#available_label').html(perms.availability);
      $('#remaining_label').html(perms.quantity);
      $('#used_label').html(perms.availability);

      let parts = perms.period.split('~').map(part => part.trim());
      let startDate = parts[0]; // Fecha de inicio
      let endDate = parts[1]; // Fecha de fin

      if (perms.type_id == 1 || perms.type_id == 3 || perms.type_id == 4){
         $('#horaDiv').show();
         $('#fechaDiv').hide();
         $('#start_time').val(startDate);
         $('#end_time').val(endDate);
      } 
      else{
         $('#horaDiv').hide();
        $('#fechaDiv').show();
        $('#start_date').val(startDate);
        $('#end_date').val(endDate);
      } 
       const photo_ = perms.photo ? perms.photo : "../images/user/default.png";
       console.log(photo_)
       previewImageRoom(null, 1,photo_); //1=indice null =input type file

       $('#permission_modal').modal('show');



}


function clearInputFrom(){
  $("#start_date").val('');
 $("#end_date").val('');
 $("#start_time").val('');
 $("#end_time").val('');
  //clear input
 $("#corresponding_days").html('');
 $("#remaining_label").html('');
 $("#used_label").html('');
 $("#available_label").html('');
 //crear objetos
  permission={}
  $("#id_typePermission").prop("disabled", false);
  $("#start_date").prop("disabled", false);
  $("#start_time").prop("disabled", false);
}



 function showDivsBasedOnType(id_type) {
  //clear ounput
 $("#start_date").val('');
 $("#end_date").val('');
 $("#start_time").val('');
 $("#end_time").val('');
  //clear input
 $("#corresponding_days").html('');
 $("#remaining_label").html('');
 $("#used_label").html('');
 $("#available_label").html('');
 //crear objetos
  permission.daysPermission ='';//dias de permiso
  permission.hoursPermission='';//horas de permiso
  permission.number_corespondi='';//dias del tipo del permiso
  permission.remainingDays=''; //dias restantes
  permission.remainingHours='';//dias y horas restantes


    if (id_type == 1 || id_type == 3 || id_type == 4) {
         // Mostrar campos de hora y ocultar campos de fecha
        $('#horaDiv').show();
        $('#fechaDiv').hide();
    } else {
        // Mostrar campos de fecha y ocultar campos de hora
        $('#horaDiv').hide();
        $('#fechaDiv').show();
      
    }
}


async function inspectBackgroundAsync() {
  try {
    const respuesta = await fetch('../controller/permission/ControllerRunBackgroundAsyn.php');
    const resultado = await respuesta.json();
    if(resultado.status){
      
    }else  Swal.fire("Mensaje de error", resultado.msg, "error");
      } catch (error) {
        if (error instanceof SyntaxError) {
          
          Swal.fire("Mensaje de error", 'Error al intentar actualizar horas de turnos.', "error")
        } else {
          Swal.fire("Mensaje de error", error, "error")
        }
      }
    }

    var tbl_report;
    function report_permission(){
      var date_init = $("#FechaInicio").val();
      var date_end = $("#FechaFin").val();
    
      tbl_report=$('#tbl_reportPermission').DataTable({
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
                     title: 'REPORTE PERMISOS PERSONAL - 2024',
                    titleAttr: 'Exportar a copy'
                },
                {
                    extend:    'excelHtml5',
                    text:      '<i class="fa fa-file-text-o"></i>',
                     title: 'REPORTE PERMISOS PERSONAL - 2024 ',
                    titleAttr: 'Exportar a Excel'
                },
                {
                    extend:    'pdfHtml5',
                    text:      '<i class="fa fa-file-text" aria-hidden="true"></i>',
                     title: 'REPORTE PERMISOS PERSONAL - 2024',
                    titleAttr: 'Exportar a PDF'
                },
                {
                    extend:    'print',
                    text:      '<i class="fa fa-print"></i> ',
                     title: 'REPORTE PERMISOS PERSONAL - 2024 ',
                    titleAttr: 'Imprimir'
                }],
            "lengthMenu": [
                [10, 20, 50, 100, -1],
                [10, 20, 50, 100, "All"]
            ],
            "pageLength": 10,
            "destroy": true,
            "processing": true,
            "ajax": {
                "url": "../controller/permission/ControllerGetReportPermis.php",
                type: 'POST',
                data:{date_init:date_init,date_end:date_end,search:''}
            },
            "columns": [
            { "data": null,"render": function (data, type, row, meta) {return meta.row + 1; }},
            { "data": "idpersona" },
            {"data": null, "render": function (data, type, row) {return row.Nombre + ' ' + row.Apellidos;}},
            { "data": "name_permiss"},
            { "data": "period"},
             { "data": "number_day"},
            { "data": "quantity" },
            {"data": "availability"},
            
            { "data": null,"render": function (data, type, row, meta) {return row.isactive==2? 'Complet.': 'In. Per'; }},
            { "data": "current_year"},
            ],
            "language": idioma_espanol,
            select: true
        });
        document.getElementById("tbl_reportPermission_filter").style.display = "none";
        $('input.global_filter').on('keyup click', function() {
          filterreportPermis();
            
        });
       
        tbl_report.column( 1 ).visible( false );
         $('#btn-place').html(tbl_report.buttons().container());
    
    }
    
    function filterreportPermis() {
      $('#tbl_reportPermission').DataTable().search($('#global_filter').val(), ).draw();
    }