


var editando=false;
var tablerole;

function openModalRole(){
      editando=false;
	   $("#modal_role").modal({
	      backdrop: 'static',
	      keyboard: false
	    })
     clean_input_values();
	   $("#tituloModal").text('Nuevo Rol');
	   $('#modal_role').modal('show');
}

function Register_role(){
var data = { id: $("#idrole").val(), namerole: $("#namerole").val().toUpperCase()};
$.post(editando ? '../controller/role/ControllerroleUpdate.php' : '../controller/role/ControllerroleRegister.php',data)
      .done(function(resultado) {
       var response = JSON.parse(resultado);
        if(response.status){ 
        	Swal.fire({position: 'top-end', icon: 'success',title: 'Éxito !!',text: response.msg,showConfirmButton: false,timer: 1500})
          tablerole.ajax.reload();
         $('#modal_role').modal('hide');
         clean_input_values();
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
  $("#idrole").val('');
  $("#namerole").val('');
  editando=false;
}

function List_roler(){
   tablerole=$('#table_role').DataTable(
   {
       ...datatableConfig,
        "pageLength": 5,
        "destroy": true,
        "async": false,
        "processing": true,
        "ajax": createAjaxConfig('#table_role', 'role', 'ControllerGetRoles.php'),
        "columns": [
          { "data": null,"render": function (data, type, row, meta) {return meta.row + 1; }},
          {"data": "idroles" },
          {"data": "namerole"},
          {"data": "created_at"},
          {"data": "updated_at"},
          { "data": "status",
           "render": function (data, type, row) { return renderEstatus(data, type, row, "status");}
          },
           {"defaultContent": "<button type='button' class='editar btn btn-info btn-sm' title='editar'><i class='fa fa-edit' ></i> Editar</button>"+
     "&nbsp;<button type='button' class='role btn btn-secondary btn-sm'><i class='fa fa-cog' ></i>Accesos</button>"+
    "&nbsp;<button  type='button' class='eliminar btn btn-secondary btn-sm' title='eliminar'><i class='fa fa-trash'></i> Remover</button>"
  }              
        ],
        select: true
    });
    document.getElementById("table_role_filter").style.display = "none";
    $('input.global_filter').on('keyup click', function() {
        filterGlobal();
    });
   
     tablerole.column(1).visible( false );

}

function filterGlobal() {
    $('#table_role').DataTable().search($('#global_filter').val(), ).draw();
}

$('#table_role').on('click', '.editar', function() {
    var data = tablerole.row(tablerole.row(this).child.isShown() ? this :
    $(this).parents('tr')).data();

    editando = true;
    $("#idrole").val(data.idroles);
    $("#namerole").val(data.namerole);

    $("#modal_role").modal({
        backdrop: 'static',
       keyboard: false
      })
     $("#tituloModal").text('Editando Rol: '+data.namerole);
     $('#modal_role').modal('show');
})

$('#table_role').on('click', '.eliminar', function() {
    var data = tablerole.row(tablerole.row(this).child.isShown() ? this :
    $(this).parents('tr')).data();

     Swal.fire({
        title: 'Esta seguro de dar baja?',
        text: "Una vez hecho esto no podrás recuperar.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0720b7',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Si!'
    }).then((result) => {
        if (result.value) {
            removeRole(data.idroles);
        }
    }) 
})

function removeRole(idrole){
$.post('../controller/role/ControllerroleRemove.php',{idrole:idrole})
      .done(function(resultado) {
       var response = JSON.parse(resultado);
        if(response.status){ 
          Swal.fire({position: 'top-end',icon: 'success',title: 'Éxito !!',text: response.msg,showConfirmButton: false,timer: 1500})
          tablerole.ajax.reload();
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

$('#table_role').on('click', '.role', function () {
  var data = tablerole.row(tablerole.row(this).child.isShown() ? this :
    $(this).parents('tr')).data();
  accessRole(data.idroles);

});

function accessRole(idrole) {

  $.post('../controller/role/ControllerAccess.php', { idrole: idrole })
    .done(function (resultado) {
      if (resultado) {
        document.getElementById("form_acces").innerHTML = resultado;
        $("#access_user").modal("show");

      } else Swal.fire("Mensaje de Advertencia", resultado.msg, "warning");
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      if (jqXHR.status === 403) {
        Swal.fire("Mensaje de error", "No Autorizado.", "error");
      } else {
        Swal.fire("Mensaje de error", errorThrown, "error");
      }
    });

}

function registerAccessUser() {
    const http = new XMLHttpRequest();
    const frm = document.getElementById("form_acces");
    const url = "../controller/role/ControllerRegisterAccess.php";

    http.open("POST", url);
    http.onreadystatechange = function () {
        if (http.readyState == 4) {
            if (http.status == 200) {
                const response = JSON.parse(http.responseText);
                if (response.status) {
                    $("#access_user").modal("hide");
                    Swal.fire({ position: 'top-end', icon: 'success', title: 'Éxito !!', text: response.msg, showConfirmButton: false, timer: 1500 });
                } else {
                    Swal.fire("Mensaje de error", response.msg, "error");
                }
            } else if (http.status === 403) {
                Swal.fire("Mensaje de error", "No Autorizado.", "error");
            } else {
                Swal.fire("Mensaje de error", "Ha ocurrido un error.", "error");
            }
        }
    };

    http.send(new FormData(frm));
}
