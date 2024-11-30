
var params = {
  date_init: $("#date_ini").val() || null,
  date_end: $("#date_end").val() || null,
  search: $("#global_filter").val(),
  paginate: false,   
  id_user:'',
  people_id:'',
  isDeletPhoto:false,
  isAdmin:true
};


var editando = false;
function openModalUser() {
  editando = false;
  $("#user_modal").modal({
    backdrop: 'static',
    keyboard: false
  })
  $("#tituloModal").text('Nuevo Usuario');
  clearInputModals();
  rolesSelect();
  params.id_user='';
  $('#user_modal').modal('show');
  
}


async function registerUser(btn) {
  var data = {
    iduser: params.id_user,
    name: $("#name").val(),
    lastname: $("#lastname").val(),
    username: $("#username").val().toUpperCase(),
    passwordfirst: $("#passwordfirst").val(),
    passwordsecond: $("#passwordsecond").val(),
    role_id: $("#role_id").val(),
    code: $("#code").val(),
    photo: '',
    isDeletPhoto: params.isDeletPhoto,
    people_id: params.people_id
  };

  if (validarCampos(data, editando)) return;
  data.photo = $('#photo1')[0].files[0];
  const formData = new FormData();
  Object.entries(data).forEach(([key, value]) => { formData.append(key, value); });
  
  toggleIconChange(btn);

  try {
    const response = await fetch(
      editando ? '../controller/user/ControllerUpdateUser.php' : '../controller/user/ControllerRegisterUser.php',
      { method: 'POST', body: formData }
     );

    if (response.ok) {
      const result = await response.json();
      if (result.status) {
        Swal.fire({ position: 'top-end', icon: 'success', title: 'Éxito !!', text: result.msg, showConfirmButton: false, timer: 1500, });
        
         params.isAdmin ===true ? tableuser.ajax.reload() :'';
        
        $('#user_modal').modal('hide');
        clearInputModals();
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
   
  toggleIconRollback(btn);
}



function previewImage(input, index, userphoto = null) {
  var preview = document.getElementById(`preview${index}`);
  var file = input ? input.files[0] : null;
  if (file || userphoto) {
    preview.src = file ? URL.createObjectURL(file) : userphoto;
  } else {
    console.log('No file selected');
  }
   document.getElementById('removeButton').style.display = 'block';
}

function removeImage(index) {
  document.getElementById(`photo${index}`).value = '';
  document.getElementById(`preview${index}`).src = '';
  this.params.isDeletPhoto=true;
  document.getElementById('removeButton').style.display = 'none';
}

var tableuser;
function List_users(params) {
  tableuser = $('#tabla_usuario').DataTable({
    ...datatableConfig,

    "pageLength": 5,
    "destroy": true,
    "async": false,
    "processing": true,
    "ajax": createAjaxConfig('#tabla_usuario', 'user', 'ControllerGetUsuers.php'),
    "columns": [
    { "data": null, "render": function (data, type, row, meta) { return meta.row + 1; } },
    { "data": "iduser" },
    { "data": null, "render": function (data, type, row) { return row.name + ' ' + row.lastname; } },
    { "data": "username" },
    { "data": "namerole" },
    {
      "data": "status",
      "render": function (data, type, row) { return renderEstatus(data, type, row, "status"); }
    },
    {
      "data": "status ",
      "render": function (data, type, row) {
        var buttonClass = row.status == "1" ? "btn-info" : "btn-warning";
        var buttonText = row.status == "0" ? "inactivar" : "activar";

        return `<button  type='button' class='keypass btn btn-secondary btn-sm'><i class='fa fa-key'></i>!</button>
        &nbsp;<button type='button' class='editar btn btn-primary btn-sm'><i class='fa fa-edit' ></i></button>
        &nbsp;<button type='button' class='${buttonText} btn ${buttonClass} btn-sm'><i class='fa fa-eye-slash' aria-hidden='true'></i></button>
        &nbsp;<button  type='button' class='eliminar btn btn-secondary btn-sm'><i class='fa fa-trash'></i></button>`;
      }
    }

    ],
    "language": idioma_espanol,
    select: true
  });
  document.getElementById("tabla_usuario_filter").style.display = "none";
  $('input.global_filter').on('keyup click', function () {
    filterGlobal();
  });

  tableuser.column(1).visible(false);
}


$('#tabla_usuario').on('click', '.editar', function () {
  var data = tableuser.row(tableuser.row(this).child.isShown() ? this : $(this).parents('tr')).data();
  $.post('../controller/user/ControllerShowUser.php', { id: data.iduser })
  .done(function (resultado) {
     var selectElement = document.getElementById("conten_inclid_proples");//ocultar el agregar personas
      selectElement.style.display = "none";
    var response = JSON.parse(resultado);
    if (response.status) showUser(response.data);
  })
  .fail(function (jqXHR, textStatus, errorThrown) {
    if (jqXHR.status === 403) {
      Swal.fire("Mensaje de error", "No Autorizado.", "error");
    } else {
      Swal.fire("Mensaje de error", errorThrown, "error");
    }
  });
});

function rolesSelect(idrole) {
  $.post('../controller/user/ControllerGetRoleSelect.php')
  .done(function (resultado) {
    var response = JSON.parse(resultado);
    if (response.status) {
      let { data } = response;
      var cadena = "";

      if (data.length > 0) {
        for (var i = 0; i < data.length; i++) {

          cadena += "<option value='" + data[i].idroles + "'" +
          (data[i].idroles == idrole ? " selected" : "") + ">" + data[i].namerole + "</option>";
        }
      } else cadena += "<option value=''>NO SE ENCONTRARON REGISTROS</option>";
      $("#role_id").html(cadena);
    }
  })
  .fail(function (jqXHR, textStatus, errorThrown) {
    if (jqXHR.status === 403) {
      Swal.fire("Mensaje de error", "No Autorizado.", "error");
    } else {
      Swal.fire("Mensaje de error", errorThrown, "error");
    }
  });
}


function showUser(user) {
  if (user && Object.keys(user).length > 0) {
    $("#user_modal").modal({
      backdrop: 'static',
      keyboard: false
    });
    editando = true;
    $("#tituloModal").text('Editar Usuario');
    params.id_user=user.iduser;
    people_id: params.people_id
    $("#name").val(user.name);
    $("#lastname").val(user.lastname);
    $("#username").val(user.username);
    $("#code").val(user.code);
    rolesSelect(user.role_id)
    $("#passwordsecond").prop("disabled", true);
    const userphoto = user.photo ? user.photo : "default.png";
    previewImage(null, 1,"../images/user/"+userphoto); //1=indice null =input type file
    $("#passwordfirst").prop("disabled", true);
    $('#user_modal').modal('show');
  } else {
    Swal.fire("Mensaje de error", "No se pudieron cargar los datos del usuario.", "error");
  }
}


function validarCampos(datos, editando) {
console.log(datos)
  if (editando) {
    if (!datos.name || !datos.lastname || !datos.username || !datos.role_id) {
      return Swal.fire("Mensaje de advertencia", "Llene todos los campos, son obligatorios.", "warning");
    }
  } else {
    if (!datos.name || !datos.lastname || !datos.username || !datos.passwordfirst || !datos.passwordsecond || !datos.role_id) {
      return Swal.fire("Mensaje de advertencia", "Llene todos los campos, son obligatorios.", "warning");
    }

    if (datos.passwordfirst !== datos.passwordsecond) {
      return Swal.fire("Mensaje de advertencia", "Las contraseñas no son iguales.", "warning");
    }
  }
  return null;  // Retorna null si todas las validaciones son exitosas
}


$('#tabla_usuario').on('click', '.activar', function () {
  var data = tableuser.row(tableuser.row(this).child.isShown() ? this :
    $(this).parents('tr')).data();
  confirmAction(data.iduser, 'Inactivar');
});

$('#tabla_usuario').on('click', '.inactivar', function () {
  var data = tableuser.row(tableuser.row(this).child.isShown() ? this :
    $(this).parents('tr')).data();
  confirmAction(data.iduser, 'Activar');
});

$('#tabla_usuario').on('click', '.eliminar', function () {
  var data = tableuser.row(tableuser.row(this).child.isShown() ? this :
    $(this).parents('tr')).data();
  confirmAction(data.iduser, 'Eliminar');

});



function confirmAction(iduser, action) {
  Swal.fire({
    title: `¿Está seguro de ${action} al usuario?`,
    text: `Una vez hecho esto el usuario ${action === 'Eliminar' ? 'será dado de baja' : action === 'Activar' ? 'tendrá acceso al sistema' : 'no tendrá acceso al sistema'}`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#0720b7',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Sí !'
  }).then((result) => {
    if (result.value) {
      Modificar_Estatus(iduser, action === 'Activar' ? '1' : action === 'Inactivar' ? '0' : '-1');
    }
  });
}


function Modificar_Estatus(idusuario, estatus) {
  $.post('../controller/user/ControllerStateChange.php', { id: idusuario, entry: estatus })
  .done(function (resultado) {
    var response = JSON.parse(resultado);
    if (response.status) {
      Swal.fire({position: 'top-end', icon: 'success', title: 'Éxito !!', text: response.msg, showConfirmButton: false, timer: 1500 })
      tableuser.ajax.reload();
    } else Swal.fire("Mensaje de Advertencia", response.msg, "warning");
  })
  .fail(function (jqXHR, textStatus, errorThrown) {
    if (jqXHR.status === 403) {
      Swal.fire("Mensaje de error", "No Autorizado.", "error");
    } else {
      Swal.fire("Mensaje de error", errorThrown, "error");
    }
  });
}

function clearInputModals() {
  $("#iduser").val('');
  $("#name").val('');
  $("#lastname").val('');
  $("#username").val('');
  $("#passwordfirst").val('');
  $("#passwordsecond").val('');
  $('#role_id').val(0).trigger('change');
  $("#passwordsecond").prop("disabled", false);
  $("#passwordfirst").prop("disabled", false);
  var fileInput = document.getElementById('photo1').value = '';
  var imgPreview = document.getElementById('preview1').src = '../images/user/default.png';
  params.isDeletPhoto=false;
  params.isAdmin =true
}



function filterGlobal() {
  $('#tabla_usuario').DataTable().search($('#global_filter').val(),).draw();
}


$('#tabla_usuario').on('click', '.keypass', function () {
  var data = tableuser.row(tableuser.row(this).child.isShown() ? this :
    $(this).parents('tr')).data();
  $("#modal_Passwoed").modal({
    backdrop: 'static',
    keyboard: false
  })
  $("#currentpass, #newpasssecond, #newpass").val('');
   params.id_user=data.iduser;
  $('#modal_Passwoed').modal('show');
});


function Change_password(btn) {
  var data = {
    iduser:  params.id_user,
    currentpass: $("#currentpass").val() ?? $("#current_pass").val(),
    newpass: $("#newpass").val() ?? $("#new_pass").val(),
    newpasssecond: $("#newpasssecond").val() ?? $("#newpassse_cond").val(),
  };

  if (!isvalidData(data)) return Swal.fire("Mensaje de error", "Hay errores al intenatar guardar los cambios", "error");

  toggleIconChange(btn);

  $.post('../controller/user/ControllerChangePassword.php', data)
  .done(function (resultado) {
    var response = JSON.parse(resultado);
    if (response.status) {

      $("#currentpass, #newpasssecond, #newpass").val('');params.id_user='';
      $("#current_pass, #newpassse_cond, #new_pass").val('');
      $('#modal_Passwoed').modal('hide');

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
 
  toggleIconRollback(btn)
}

function isvalidData(data) {
  if (data.currentpass == '' || data.newpass ==='' || data.newpasssecond == '') return false;


  if (data.newpass !== data.newpasssecond) {
    Swal.fire("Mensaje de error", "Las contraseñas nuevas no coinciden", "error");
    return false;
  }
  return true; // Todos los datos son válidos
}

function verifyUser(btn) {
  var user = $("#txt_user").val();
  var password = $("#text_paswoed").val();
  if (user.length == 0 || password.length == 0) {
    var html = generateAlert("info", "Oh snap!", "Se requiere nombre de usuario y contraseña.");
    $("#msg_login").html(html); return;
  }
   
  toggleIconChange(btn);
  $.post('../controller/user/ControllerUserVerify.php', { user: user, password: password })
  .done(function (resultado) {
    var response = JSON.parse(resultado);
    var messageType = response.auth ? "success" : "danger";
    var messageText = response.auth ? "Bienvenido" : "Ups!  ";

    var html = generateAlert(messageType, messageText, response.msg);
    $("#msg_login").html(html);

    if (response.auth) location.reload();

  })
  .fail(function (jqXHR, textStatus, errorThrown) {
    const errorMessage = jqXHR.status === 403 ? "No Autorizado." : errorThrown;
    Swal.fire("Mensaje de error", errorMessage, "error");
  });
  toggleIconRollback(btn)
}
function generateAlert(type, strongText, messageText) {
  return `
  <div class='bs-component'>
  <div class='alert alert-dismissible alert-${type}'>
  <button class='close' type='button' data-dismiss='alert'>×</button>
  <strong>${strongText}</strong><a class='alert-link'></a>${messageText}.
  </div>
  </div>
  `;
}





//FUNCIO PARA LISTAR PERSONAL CON ESTADO ON
var peoplesOn;
function peoplesOnSelect(people_id) {
  $.post('../controller/Persona/ControllerGetPeoplesOn.php')
  .done(function (resultado) {
    var response = JSON.parse(resultado);
    if (response.status) {
      let { data } = response;
      peoplesOn = data;
      var cadena = "";

      if (data.length > 0) {
        for (var i = 0; i < data.length; i++) {
          cadena += "<option value='" + data[i].idpersona + "'" +
          (data[i].idpersona == people_id ? " selected" : "") + ">" + data[i].Nombre+''+data[i].Apellidos + "</option>";
        }
      } else cadena += "<option value=''>NO SE ENCONTRARON REGISTROS</option>";
      $("#people_id").html(cadena);
    }
  })
  .fail(function (jqXHR, textStatus, errorThrown) {
    if (jqXHR.status === 403) {
      Swal.fire("Mensaje de error", "No Autorizado.", "error");
    } else {
      Swal.fire("Mensaje de error", errorThrown, "error");
    }
  });
}

function selecPeopleOnChange(selectElement){
   var people_id = selectElement.value;
   if(people_id) filtrarPorId(people_id);
}

function filtrarPorId(id) {
   var people = peoplesOn.filter(function(persona) {
        return persona.idpersona === id;
    });
    if (people.length > 0) {
      params.people_id=people.idpersona;
        $("#name").val(people[0].Nombre); $("#lastname").val(people[0].Apellidos);
    } else {
        console.log('No se encontraron usuarios con el ID especificado.');
    }
}

function toggleSelectVisibility() {
    var selectElement = document.getElementById("people_id");
    var checkbox = document.getElementById("toggleSelect");

    if (checkbox.checked) {
       peoplesOnSelect();
        selectElement.style.display = "block";
    } else {
        peoplesOn=null;
        selectElement.style.display = "none";
    }
}

//////////////////////////////////////////////////PrefilexExternos/////////////////

function openPrefileUser(iduser) {
  $.post('../controller/user/ControllerShowUser.php', { id: iduser })
  .done(function (resultado) {
    var response = JSON.parse(resultado);
    if (response.status) 
      {editando = true;
        let data = response.data;
        params.isAdmin = false;
        params.id_user=data.iduser;
        people_id: data.people_id;
        $("#name").val(data.name);
        $("#lastname").val(data.lastname);
        $("#username").val(data.username);
        $("#code").val(data.code);
        const userphoto = data.photo ? data.photo : "default.png";
        previewImage(null, 1,"../images/user/"+userphoto);
      }else{

      }
  })
  .fail(function (jqXHR, textStatus, errorThrown) {
    if (jqXHR.status === 403) {
      Swal.fire("Mensaje de error", "No Autorizado.", "error");
    } else {
      Swal.fire("Mensaje de error", errorThrown, "error");
    }
  });

}


