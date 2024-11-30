var params = { id_user:''};
let locationsOptions ={smg:'',latitude:'',longitude:''};
var users;
function list_user_individual(){
  $.post('../controller/user/ControllerGetUsuers.php',{ conpany : '1' })
    .done(function (resultado) {
      var response = JSON.parse(resultado);
      if (response.status) {
        let { data } = response;
        users=data;
         var container = document.getElementById('conteiner_user');
           data.forEach(function(user) {
            var userHTML = generateUserHTML(user);
            container.innerHTML += userHTML;
         });
        
      }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      console.log(jqXHR.status);
      if (jqXHR.status === 403) {
        Swal.fire("Mensaje de error", "No Autorizado.", "error");
      } else {
        Swal.fire("Mensaje de error", errorThrown, "error");
      }
    });

}

/*col-lg-3 col-md-6 col-sm-6 <span class="badge badge-pill ${user.status == 1 ? 'badge-success' : 'badge-warning'} float-right" style="margin-top: -10px">.</span> */

  function generateUserHTML(user) {
   return `
       <div class="col-6 col-md-4 col-lg-2"> 
          <div class="tile">

              <div class="user-profile">
                 <img src="${user.photo ? "../images"+user.photo : '../images/user/profile.png'}" alt="Foto de perfil">
              </div>
              <div class="user-details">
                  <p style="text-transform: uppercase; font-size: 10px;">${user.lastname}</p>
                  <p style="text-transform: uppercase; font-size: 12px;">${user.name}</p>
            
              </div>
              <div class="status">
                  <button class="btn btn-secondary btn-sm btn-lg btn-block" onclick="opemModal(${user.iduser})">Ingresar</button>
              </div>
          </div>
      </div>
   `;
    }

function userFilters(value) {
        var filteredUsers = users.filter(function(user) {
            return user.name.toLowerCase().includes(value) || user.username.toLowerCase().includes(value);
        });
        var container = document.getElementById('conteiner_user');
        container.innerHTML = '';
        filteredUsers.forEach(function(user) {
            var userHTML = generateUserHTML(user);
            container.innerHTML += userHTML;
        });
    }


function opemModal(id_user){
   params.id_user=id_user;
   $("#codeUser").val('');
	$("#entry_modal").modal({
		backdrop: 'static',
		keyboard: false
	})

	$("#tituloModal").text('Marcar Asistencia');
	$('#entry_modal').modal('show');
}

function Register_Asistencia(btn){

	var data = {code: $("#codeUser").val(),type:'normal',id_user: params.id_user };
  
  toggleIconChange(btn);
   $.post('../controller/entry/ControllerEntry.php', data)
  .done(function (resultado) {
    var response = JSON.parse(resultado);
    if (response.status) {

      Swal.fire({ position: 'top-end',icon: 'success', title: 'Éxito !!', text: response.msg, showConfirmButton: false, timer: 1500 })
    } else Swal.fire("Mensaje de Advertencia", response.msg, "warning");
  })
  .fail(function (jqXHR, textStatus, errorThrown) {
    if (jqXHR.status === 403) {
      Swal.fire("Mensaje de error", "No Autorizado.", "error");
    } else {
      Swal.fire("Mensaje de error", errorThrown, "error");
    }
  });
  $("#codeUser").val(''); params.id_user=''; $('#entry_modal').modal('hide');
   
  toggleIconRollback(btn);
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


   function obtenerUbicacion() {
  if (navigator.geolocation) {
       navigator.geolocation.getCurrentPosition(
      function(position) {
        locationsOptions .latitude= position.coords.latitude;
        locationsOptions .longitude=position.coords.longitude;
        locationsOptions .smg= "Ubicación obtenida:";
      },
      function(error) {
        // Manejo de errores
        switch (error.code) {
          case error.PERMISSION_DENIED:
            locationsOptions .smg="El usuario denegó la solicitud de geolocalización. Puedes habilitar la geolocalización manualmente en la configuración del navegador.";
            break;
          case error.POSITION_UNAVAILABLE:
            locationsOptions .smg="Información de ubicación no disponible.";
            break;
          case error.TIMEOUT:
            locationsOptions .smg="La solicitud de geolocalización ha caducado.";
            break;
          case error.UNKNOWN_ERROR:
            locationsOptions .smg="Se produjo un error desconocido al obtener la geolocalización.";
            break;
        }
      }
    );
  } else {
    console.error("Geolocalización no es compatible en este navegador.");
  }
  return locationsOptions ;
}

function generateAlert(type, messageText) {
  return `
  <div class='bs-component'>
  <div class='alert alert-dismissible alert-${type}'>
  <button class='close' type='button' data-dismiss='alert'>×</button>
  <a class='alert-link'></a>${messageText}.
  </div>
  </div>
  `;
}

