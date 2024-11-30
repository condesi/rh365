
var checkpoint={id:''}
var tlb_checkpoint;

function openModalCheckpoint(){
	 $("#checkpoint_modal").modal({
	      backdrop: 'static',
	      keyboard: false
	    })
        clean_input_values();
	   $("#tituloModal").text('Nuevo Punto de control');
	   $('#checkpoint_modal').modal('show');
	   getLocation();
}

function getCheckpoint(){
  var params = { date_init: $("#date_ini").val() || null, date_end: $("#date_end").val() || null,  search: $("#global_filter").val()};
	tlb_checkpoint = $('#tbl_checkpoint').DataTable({
	    ...datatableConfig,
	    "pageLength": 10,
	    "destroy": true,
	    "processing": true,
	    "ajax": createAjaxConfigAll('#tbl_checkpoint', 'checkpoint', 'controllerGet.php','GET',params),
	    "columns": [
	    { "data": null, "render": function (data, type, row, meta) { return meta.row + 1; } },
	    { "data": "id" },
	    { "data": "name" },
	    { "data": "longitude" },
	    { "data": "latitude" },
	    { "data": "haversine" },
	    { "data": "threshold" },
	    {
	     "defaultContent": "<button type='button' class='edit btn btn-primary btn-sm'><i class='fa fa-edit' ></i></button>&nbsp;<button  type='button' class='remove btn btn-secondary btn-sm'><i class='fa fa-trash'></i></button>"
	    }

	    ],
	    "language": idioma_espanol,
	    select: true
	  });
	  document.getElementById("tbl_checkpoint_filter").style.display = "none";
	  $('input.global_filter').on('keyup click', function () {
	    filtercheckpoints();
	  });

	 tlb_checkpoint.column(1).visible(false);

}

function filtercheckpoints() {
  $('#tbl_checkpoint').DataTable().search($('#global_filter').val()).draw();
}

function registerCheckpoint(btn){
 var data = {
        id: checkpoint.id,
        longitude: $("#longitude_checkpoit").val(),
        latitude: $("#latitude_checkpoit").val(),
        status: $("#status_checkpoit").val(),
        name: $("#name_checkpoit").val().toUpperCase(),
        haversine: $("#haversine_checkpoit").val() || '',
        threshold: $("#threshold_checkpoit").val() || '',
    };
    try{
    	toggleIconChange(btn);
 
	    $.post('../controller/checkpoint/controllerPost.php', data)
	        .done(function (resultado) {
	            var response = JSON.parse(resultado);
	            if (response.status) { Swal.fire({ position: 'top-end', icon: 'success', title: 'Éxito !!', text: response.msg, showConfirmButton: false, timer: 1500 });
	                $('#checkpoint_modal').modal('hide');
	                 tlb_checkpoint.ajax.reload();
	                clean_input_values();
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
     }catch (error) {
        Swal.fire("Mensaje de error", error.message, "error"); 
    }
     toggleIconRollback(btn);

}

$('#tbl_checkpoint').on('click', '.edit', function () {
  var data = tlb_checkpoint.row(tlb_checkpoint.row(this).child.isShown() ? this : $(this).parents('tr')).data();
  $.get('../controller/checkpoint/controllerGet.php', { id: data.id })
  .done(function (resultado) {
        var response = JSON.parse(resultado);
        if (response.status)   showCheckpoint(response.data);
        else  Swal.fire("Mensaje de error", response.msg, "error");
        })
  .fail(function (jqXHR, textStatus, errorThrown) {
    if (jqXHR.status === 403) {
      Swal.fire("Mensaje de error", "No Autorizado.", "error");
    } else {
      Swal.fire("Mensaje de error", errorThrown, "error");
    }
  });
});

function showCheckpoint(_data) {
	 const status = document.getElementById('status');
	data= _data[0];
  if (data && Object.keys(data).length > 0) {
    $("#checkpoint_modal").modal({
      backdrop: 'static',
      keyboard: false
    });
    $("#tituloModal").text('Editando puntos de control: '+ data.name);
    checkpoint.id=data.id;
     status.textContent = `Latitud: ${data.latitude} °, Longitud: ${data.longitude} °`;
    $("#longitude_checkpoit").val(data.longitude);
		$("#latitude_checkpoit").val(data.latitude);
		$("#status_checkpoit").val(data.status);
		$("#name_checkpoit").val(data.name);
		$("#haversine_checkpoit").val(data.haversine);
		$("#threshold_checkpoit").val(data.threshold);
    $('#checkpoint_modal').modal('show');

  } else {
    Swal.fire("Mensaje de error", "No se pudieron cargar los datos del usuario.", "error");
  }
}


$('#tbl_checkpoint').on('click', '.remove', function () {
   var data = tlb_checkpoint.row(tlb_checkpoint.row(this).child.isShown() ? this :
    $(this).parents('tr')).data();

     Swal.fire({
        title: 'Esta seguro de eliminar?',
        text: "Una vez hecho esto no podrás recuperar.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0720b7',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Si !'
    }).then((result) => {
        if (result.value) {
            removecheckpoint(data.id);
        }
    }) 
});

function removecheckpoint(id){
$.get('../controller/checkpoint/controllerDelete.php',{id:id})
      .done(function(resultado) {
       var response = JSON.parse(resultado);
        if(response.status){ 
          Swal.fire({position: 'top-end',icon: 'success',title: 'Éxito !!',text: response.msg,showConfirmButton: false,timer: 1500})
          tlb_checkpoint.ajax.reload();
        }else{ Swal.fire("Mensaje de error", response.msg, "error");}
      })
      .fail(function(jqXHR, textStatus, errorThrown) {
        if (jqXHR.status === 403) {
          Swal.fire("Mensaje de error","No Autorizado.", "error");
        } else {
          Swal.fire("Mensaje de error", errorThrown, "error");
        }
      });
}


function clean_input_values(){
	checkpoint.id='';
	$("#longitude_checkpoit").val('');
	$("#latitude_checkpoit").val('');
	$("#status_checkpoit").val('');
	$("#name_checkpoit").val('');
	$("#haversine_checkpoit").val('');
	$("#threshold_checkpoit").val('');
}


 function getLocation() {

    const status = document.getElementById('status');
        $("#latitude_checkpoit").val('');
        $("#longitude_checkpoit").val('');

     if (!"geolocation" in navigator) {
	   	status.textContent = "Tu navegador no soporta el acceso a la ubicación. Intenta con otro";
	   }
    if (!navigator.geolocation) {
        status.textContent = 'La geolocalización no es soportada por tu navegador.';
        return;
    }

    status.textContent = 'Localizando…';

    navigator.geolocation.getCurrentPosition((position) => {
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;
        const accuracy = position.coords.accuracy;

        status.textContent = '';
        status.textContent = `Latitud: ${latitude} °, Longitud: ${longitude} ° , Precisión: ${accuracy} metros`;
        $("#latitude_checkpoit").val(latitude);
        $("#longitude_checkpoit").val(longitude);
        $("#haversine_checkpoit").val(accuracy);
        $("#threshold_checkpoit").val(100);
    }, (error) => {
        switch(error.code) {
            case error.PERMISSION_DENIED:
                status.textContent = "Permiso denegado por el usuario.";
                break;
            case error.POSITION_UNAVAILABLE:
                status.textContent = "Información de ubicación no disponible.";
                break;
            case error.TIMEOUT:
                status.textContent = "La solicitud de ubicación ha expirado.";
                break;
            case error.UNKNOWN_ERROR:
                status.textContent = "Un error desconocido ocurrió.";
                break;
        }
    });
}


