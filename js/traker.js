


function attendanceTracker(btn){
    let companny = localStorage.getItem("companny");
    companny = JSON.parse(companny);

      var entradaRadio = document.querySelector('input[name="attendance"][value="entrada"]').checked;
      var salidaRadio = document.querySelector('input[name="attendance"][value="salida"]').checked;
      attendance= salidaRadio== true ? 'exit':'entry';
      var code = $("#code_user").val(); 


    if(companny && companny.isGeoLocation){
        var pointCheck = $("#checkbox_maps").is(":checked"); 
        console.log(pointCheck)
         if(!pointCheck) return showNotification('info', 'Seleccione checkbox maps.', '✔');
        attendanceGeolocation(btn,code,attendance);

    } else if(companny && companny.isByCheckpoints){
        var pointCheck = $("#checkbox_maps").is(":checked");
        if(!pointCheck) return showNotification('info', 'Seleccione checkbox maps.', '✔');
       console.log(pointCheck)
        getCheckpointsUserByCode(btn,code,attendance);

    }else{
       
        //isNormalAccess
        registerAttendance(btn,attendance,code);
    }
   


}

async function getCheckpointsUserByCode(btn, code,attendance) {
  try {
    toggleIconChange(btn);
    const response = await fetch(`../controller/checkpointUser/controllerGetCheckpointByCode.php?code=${code}`);
     if (!response.ok) throw new Error('Network response was not ok');
       const result = await response.json();
        if (result.status) {
           let { data } = result;
           if (data.length === 0) throw new Error('No tienes puestos de control asignados a tu responsabilidad!');
           if (data.length > 1) console.log('El usuario tiene más de un puesto');

          var dto = {
            lat: data[0].latitude,
            lng: data[0].longitude,
            threshold: data[0].threshold,
            accuracy: data[0].haversine,
            user: result.user
          };

          let puestoDeControl = { lat: dto.lat, lng: dto.lng, threshold: dto.threshold, accuracy: dto.accuracy };
          const geoLocation = await getGeoLocation();
          if (geoLocation) {
                const distancia = calculateDistance(geoLocation, puestoDeControl);
                const coordenadas = `Latitud: ${geoLocation.lat}, Longitud: ${geoLocation.lng}`;
                let mensajeRadio;

                if (Number(distancia) <= Number(dto.threshold)) {
                  showNotification('success', 'Ubicación correcta', `Estas dentro de un radio de ${Math.round(distancia)} metros.`);
                  
                  registerAttendanceCheckpoint(btn, dto, geoLocation,attendance,code);
                } else {
                    showNotification('error', 'Estas fuera del rango', ` a (${Math.round(distancia)} metros)`);
                 
                }
          }
    } else {
      showNotification('warning', 'Advertencia', result.msg);
    }
  } catch (error) {
    Swal.fire("Mensaje de error", error.message, "error");
  }
  toggleIconRollback(btn);
}


 function calculateDistance(pointCurrent, puntoB) {
// Obtener los márgenes de error de cada punto
   // const accuracyCurrent = pointCurrent.accuracy || 0; // Si no se proporciona un margen de error, se asume 0
   // const accuracyB = puntoB.accuracy || 0;

    // Calcular el radio de la Tierra ajustado teniendo en cuenta los márgenes de error
   // const R = 6371e3 + Number(accuracyCurrent) + Number(accuracyB); // Sumar los márgenes de error de ambos puntos
      const R = 6371e3; // Radio de la Tierra en metros
      const lat1 = pointCurrent.lat * Math.PI / 180; // Convertir latitud de grados a radianes
      const lat2 = puntoB.lat * Math.PI / 180;
      const deltaLat = (puntoB.lat - pointCurrent.lat) * Math.PI / 180;
      const deltaLng = (puntoB.lng - pointCurrent.lng) * Math.PI / 180;
      const a = Math.sin(deltaLat / 2) * Math.sin(deltaLat / 2) +
                Math.cos(lat1) * Math.cos(lat2) *
                Math.sin(deltaLng / 2) * Math.sin(deltaLng / 2);
      const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

      return R * c; // Distancia en metros
  }

function showNotification(icon, title, message) {

  Swal.fire({ toast: true, position: 'top-right',icon: icon, title: title, text: message, showConfirmButton: false, timer: 2000 });
}





async function getGeoLocation() {
  if (!("geolocation" in navigator)) {
    showNotification('error', 'Navegador no soporta', 'Intenta con otro.');
    return null;
  }

  if (!navigator.geolocation) {
    showNotification('error', 'La geolocalización', 'No es soportada por tu navegador.');
    return null;
  }

  const options = { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 };
  try {
    const position = await new Promise((resolve, reject) => {
      navigator.geolocation.getCurrentPosition(resolve, reject, options);
    });
    const pointCurrent = {
      lat: position.coords.latitude,
      lng: position.coords.longitude,
      accuracy: position.coords.accuracy
    };
    return pointCurrent;
  } catch (error) {
    switch (error.code) {
      case error.PERMISSION_DENIED:
        showNotification('error', 'Permiso denegado por el usuario', '');
        break;
      case error.POSITION_UNAVAILABLE:
        showNotification('error', 'Información de ubicación no disponible.', '');
        break;
      case error.TIMEOUT:
        showNotification('error', 'La solicitud de ubicación ha expirado.', '');
        break;
      case error.UNKNOWN_ERROR:
        showNotification('error', 'Un error desconocido ocurrió..', '');
        break;
    }
    return null;
  }
}



async function registerAttendanceCheckpoint(btn, dto,location,attendance,code) {
    try {
         console.log("INGRESO POR PUESTOS DE CONTROL");
        $("#msg_login").html('');
        
        const response = await fetch('../controller/geolocation/controllerTraker.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({ code: code, type: 'normal', attendance: attendance,id_user:dto.user.iduser,location: JSON.stringify(location) })
        });

        if (!response.ok)  throw new Error('Network response was not ok');
        const result = await response.json();
        if (response.ok) {
          result.user= dto.user;
           if(result.status)  procesRequest(result);
            if(!result.status)  return  Swal.fire("Mensaje Informaciòn", result.msg, "info");
        } else {
          Swal.fire("Error Message", result.msg, "error");
        }
    } catch (error) {
        Swal.fire("Mensaje de error", error.message, "error");
    } finally {
       toggleIconRollback(btn);
        $("#code_user").val('');
    }
}

async function attendanceGeolocation(btn, code, attendance) {
    try {
        console.log("INGRESO CON GEOLOCALIZACIÓN");
        $("#msg_login").html('');
        const location = await getGeoLocation();
       
        if (!location) return showNotification('error', 'Geolocalizacion no encontado.', '');

        const response = await fetch(`../controller/checkpointUser/controllerGetCheckpointByCode.php?code=${code}`);
        if (!response.ok) throw new Error('Network response was not ok, controllerGetCheckpointByCode.php');
        const result = await response.json();
        let { user } = result.status === true ? result : null;

        const _response = await fetch('../controller/geolocation/controllerTraker.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                code: code,
                type: 'normal',
                attendance: attendance,
                id_user: user.iduser,
                location: JSON.stringify(location)
            })
        });

        if (!_response.ok) throw new Error('Network response was not ok ,controllerTraker.php');

        const requet = await _response.json();
        if (_response.ok) {
           requet.user=user;
            if(requet.status)  procesRequest(requet);
             if(!requet.status)  return  Swal.fire("Mensaje Informaciòn", requet.msg, "info");
        } else {
            Swal.fire("Error Message", requet.msg, "error");
        }
    } catch (error) {
        Swal.fire("Mensaje de error", error.message, "error");
    } finally {
        toggleIconRollback(btn);
        $("#code_user").val('');
    }
}


async function registerAttendance(btn, attendance, code) {
    
  try {
     console.log("INGRESO NORMAL");
    $("#msg_login").html('');
         toggleIconChange(btn);
         const response = await fetch('../controller/entry/controllerTracker.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams({ code, type: 'normal', attendance })
    });
       const result = await response.json();
        if (response.ok) {
           
           if(result.status)  procesRequest(result);
           if(!result.status)  return  Swal.fire("Mensaje Informaciòn", result.msg, "info");
         

        } else {
          Swal.fire("Error Message", result.msg, "error");
        }
  } catch (error) {

     Swal.fire("Error Message", error.message, "error");
  } finally {
    toggleIconRollback(btn);
    $("#code_user").val('');
  }
}


function procesRequest(response){
     let backgroundColor='';
      let {user}= response;
     switch (response.tipo) {
            case 'success':
              typeAttendance='ENTRADA';
              backgroundColor='#d4edda';
              break;
            case 'warning':
              typeAttendance='SALIDA';
              backgroundColor='#fff3cd';
              break;
            case 'info':
               typeAttendance='ACTUALIZACIÓN';
               backgroundColor='#d1ecf1';
              break;
            default:
              break;
          }

        $("#modal_attendance").modal({
          backdrop: 'static',
          keyboard: false
        })

        $('#modal_attendance .modal-content').css('background-color', backgroundColor);
        $("#action_attendance").text(typeAttendance);
        photo = user.photo === ''? '../../images/user/profile.png' : user.photo;

        var rutaSinPuntos = photo.replace(/\.\.\//g, "");

        $('#phot_user_attendance').attr('src', '../'+rutaSinPuntos);
        $('#data_peope').html(user.name+' '+ user.lastname);
        $('#date_register').html(response.time);
        speech(typeAttendance,user);

    setTimeout(function() {
      console.log("cerrando...")
    $('#modal_attendance').modal('hide');
     }, 5000); // 5000 milisegundos = 5 segundos
}

function speech(typeAttendance,user){
  if (user) {
    let text = `${typeAttendance} CORRECTO ${user.name} ${user.lastname}`;
    const utterance = new SpeechSynthesisUtterance(text);
    // Opcional: establecer la voz y el idioma
    utterance.lang = 'es-ES'; // Español de España
    utterance.volume = 1; // Volumen (0 a 1)
    utterance.rate = 1; // Velocidad (0.1 a 10)
    utterance.pitch = 1; // Tono (0 a 2)
    // Usar la API de Speech Synthesis para leer el texto
    window.speechSynthesis.speak(utterance);
} else {
    alert('Por favor, escribe un nombre.');
}
}

var tbl_trakes;
function getTrakers(){
	 var filters = {
	  date_init: $("#start_date").val() || null,
	  date_end: $("#end_date").val() || null,
	  search: $("#global_filter").val()
	 
	};

   tbl_trakes = $('#tbl_trakes').DataTable({
	    ...datatableConfig,
	    "pageLength": 10,
	    "destroy": true,
	    "processing": true,
	    "ajax": createAjaxConfigAll('#tbl_trakes', 'geolocation', 'getTrakers.php','GET',filters),
	    "columns": [

	    { "data": null, "render": function (data, type, row, meta) { return meta.row + 1; } },
	    { "data": "id" },
	    { "data": null, "render": function (data, type, row) { return row.name + ' ' + row.lastname; } },
	    { "data": "day_name" },
	    { "data": "time_entry" },
	     { "data": "time_exit" },
	     { "data": "latitude" },
	     { "data": "Longitude" },
         { "data": "name_shift_es" },
	     { "data": "time_exit",
	     "render": function(data, type, row) { return data === null ? '<span class="badge badge-success"><em class="fa fa-arrow-circle-down"></em>&nbsp;Entrada</span>' : '<span class="badge badge-secondary"><em class="fa fa-circle-o-notch" style="color:red;" ></em>&nbsp;Salida</span>';}
	     },
	     { "data": "created_at" },
         {
         "defaultContent": "<button type='button' class='maspOk btn btn-primary btn-sm'><i class='fa fa-street-view' ></i></button>"
        }
	     
	    ],
	    "language": idioma_espanol,
	    select: true,
	   dom: 'Bfrtip',
	    buttons: [
            {
                extend: 'copy',
                text: '<i class="fa fa-files-o"></i>',
                title: 'REPORTE DE ASISTENGIAS CON GEOLOCALIZACIÓN'
            },
            {
                extend: 'excel',
                text: '<i class="fa fa-file-excel-o"></i>',
                title: 'REPORTE DE ASISTENGIAS CON GEOLOCALIZACIÓN'
            }
        ]
      });
	  document.getElementById("tbl_trakes_filter").style.display = "none";
	  $('input.global_filter').on('keyup click', function () {
	    filtercheckpoints();
	  });

	 tbl_trakes.column(1).visible(false);
	  $('#btn-place').html(tbl_trakes.buttons().container());
      
}

$('#tbl_trakes').on('click', '.maspOk', function () {
  var data = tbl_trakes.row(tbl_trakes.row(this).child.isShown() ? this : $(this).parents('tr')).data();
  console.log(data)
  openModalMasp(data.latitude,data.Longitude);
});




function openModalMasp(lat,lng){
     if(!lat && !lng){
    showNotification('warning', 'Advertencia', "no hay cordenadas para mostrar la mapa.");
    return;
  }

    $("#show_maps_modal").modal({
    backdrop: 'static',
    keyboard: false
  })
  $("#tituloModal_mps").text('ubicación actual');
  $('#show_maps_modal').modal('show');
  iniciarMap(lat,lng);
}

function iniciarMap(lat,lng){

    var coord = {lat:Number(lat)  ,lng: Number(lng)};
    var map = new google.maps.Map(document.getElementById('map'),{
      zoom: 10,
      center: coord
    });
    var marker = new google.maps.Marker({
      position: coord,
      map: map
    });
}





function filtercheckpoints() {
  $('#tbl_trakes').DataTable().search($('#global_filter').val()).draw();
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

function openModal(id){
    console.log(id)
}