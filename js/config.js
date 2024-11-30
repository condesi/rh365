// config.js


function createAjaxConfig(tableName, route, controller) {
    return {
        "url": `../controller/${route}/${controller}`,
        "type": 'POST',
        "success": function (response) {
            if (response.status) {
              $(tableName).DataTable().clear().rows.add(response.data).draw();
            } else {
              Swal.fire("Mensaje de error", response.msg, "error");
            }
             $(tableName+"_processing").css("display", "none");
        },
        "error": function (jqXHR, textStatus, errorThrown) {
          if (jqXHR.status === 403) {
            Swal.fire("Mensaje de error", jqXHR.responseJSON.msg, "error");
             } else {
             Swal.fire("Mensaje de error", errorThrown, "error");
            }
        
            $(tableName+"_processing").css("display", "none");
            $(tableName).DataTable().clear().draw();
           
        }
    };
}


function createAjaxConfigAll(tableName, route, controller,metod,param=null) {
  console.log(route+"--"+controller+'--'+metod)
    return {
        "url": `../controller/${route}/${controller}`,
        "type": metod,
        "data": param,
        "success": function (response) {
         
            if (response.status) {
              $(tableName).DataTable().clear().rows.add(response.data).draw();
            } else {
              Swal.fire("Mensaje de error", response.msg, "error");
            }
             $(tableName+"_processing").css("display", "none");
        },
        "error": function (jqXHR, textStatus, errorThrown) {
          if (jqXHR.status === 403) {
            Swal.fire("Mensaje de error", jqXHR.responseJSON.msg, "error");
             } else {
             Swal.fire("Mensaje de error", errorThrown, "error");
            }
        
            $(tableName+"_processing").css("display", "none");
            $(tableName).DataTable().clear().draw();
           
        }
    };
}

/////////////////////////////////////
//CAMBIO DE ICONOS DE BUTTONES AUTOLOAD/////////
//////////////////////////////////////



///////////////////////////////////////////
////////////loading/////////////////
var contenidoPrincipal = document.getElementById("Contenido_principal");
//var loadingOverlay = document.getElementById("loadingOverlay");

// Función para mostrar el overlay dentro del Contenido_principal
function showLoadingOverlay() {
  $("#contenidoPrincipal").html('');
  $(".overlay_load").fadeIn();
  //if (loadingOverlay && contenidoPrincipal) {
   // contenidoPrincipal.appendChild(loadingOverlay);
   // loadingOverlay.style.display = "flex";
   // contenidoPrincipal.style.background = 'rgba(255, 255, 255, 0.8)'
  //}
}
function hideLoadingOverlay() {
   $(".overlay_load").fadeOut();
 // if (loadingOverlay) {
   // loadingOverlay.style.display = "none";
   // contenidoPrincipal.style.background = ''
  //}
}


/////////////////////////////////////
//CAMBIO DE ICONOS DE BUTTONES AUTOLOAD/////////
//////////////////////////////////////
function toggleIconChange(btn) {
btn.disabled = true;
btn.querySelector('.spinner-border').style.display = 'inline-block';
}
function toggleIconRollback(btn) {
   btn.disabled = false;
  btn.querySelector('.spinner-border').style.display = 'none';
}
/*"<span class='fa-stack fa-lg'>\n\
                            <i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>\n\
                       </span>&emsp;Procesando...."*/

 const idioma_espanol = {
      select: {
      rows: "%d fila seleccionada"
      },
      "sProcessing":     "",
      "sLengthMenu":     "Mostrar _MENU_ registros",
      "sZeroRecords":    "No se encontraron resultados",
      "sEmptyTable":     "Ning&uacute;n dato disponible en esta tabla",
      "sInfo":           "Registros del (_START_ al _END_) total de _TOTAL_ registros",
      "sInfoEmpty":      "Registros del (0 al 0) total de 0 registros",
      "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
      "sInfoPostFix":    "",
      "sSearch":         "Buscar:",
      "sUrl":            "",
      "sInfoThousands":  ",",
      "sLoadingRecords": "<b>No se encontraron datos</b>",
      "oPaginate": {
          "sFirst":    "Primero",
          "sLast":     "Último",
          "sNext":     "Siguiente",
          "sPrevious": "Anterior"
      },
      "oAria": {
          "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
          "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      }
   }

   var exportButtons = [
    {
        extend:  'pdfHtml5',
        text: '<i class="fa fa-file-pdf-o"></i> PDF',
        title: 'REPORTE DE DOCENTES',
        className: 'btn btn-danger',
        style:'background-color:red'
      },{
      extend:    'print',
      text:      '<i class="fa fa-print"></i> Print',
      title: 'REPORTE DE DOCENTES',
      titleAttr: 'Imprimir',
      className: 'btn btn-info'
      },

       {
      extend:    'excel',
      text:      '<i class="fa fa-file-text-o"></i> Exel ',
      title: 'RREPORTE DE DOCENTES',
      titleAttr: 'Excel',
      className: 'btn btn-info'
      },{
      extend:    'csvHtml5',
      text:      '<i class="fa  fa-file-excel-o"></i> Csv',
      title: 'REPORTE DE DOCENTES',
      titleAttr: 'cvs',
      className: 'btn btn-info'
      }
];
var datatableConfig = {
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
};



function SearchVlueSidebar(input){
 var searchValue = input.value.toLowerCase();
  // Obtener todos los elementos de la barra lateral que deseas buscar
  var sidebarItems = document.querySelectorAll('.app-menu__item');
  // Iterar sobre los elementos y mostrar/ocultar según el término de búsqueda
  sidebarItems.forEach(function(item) {
    var label = item.querySelector('.app-menu__label');
    if (label && label.textContent.toLowerCase().includes(searchValue)) {
      item.style.display = 'block'; // Mostrar elemento si coincide con el término de búsqueda
    } else {
      item.style.display = 'none'; // Ocultar elemento si no coincide con el término de búsqueda
    }
  });
}

function ButtondefaultCustomer(){
    return "<button type='button' class='editar btn btn-info btn-sm' title='editar'><i class='fa fa-edit' ></i> Editar</button>"+
     "&nbsp;<button type='button' class='role btn btn-secondary btn-sm'><i class='fa fa-cog' ></i>Accesos</button>"+
    "&nbsp;<button  type='button' class='eliminar btn btn-secondary btn-sm' title='eliminar'><i class='fa fa-trash'></i> Remover</button>";
}

function renderEstatus(data, type, row, columnName) {
    if (row.hasOwnProperty(columnName)) {
        var status = row[columnName].toString();
        if (status == "Activo" || status == 1) {
            return "<span class='badge  badge-primary'>Activo</span>";
        } else if (status == "Inactivo" || status == 0) {
            return "<span class='badge badge-secondary'>Inactivo</span>";
        }
    }
    return ""; // Si la columna no existe o el valor no coincide con "Activo", "Inactivo", "1" o "0", devolvemos un string vacío
}

 function distroysession(){
 $.post('../controller/user/ControllerSessionActive.php',{logout:true})
      .done(function(resultado) {
         window.location.href = '../login/login.php';
      })
      .fail(function(jqXHR, textStatus, errorThrown) {
        if (jqXHR.status === 403) {
          Swal.fire("Mensaje de error","No Autorizado.", "error");
        } else {
          Swal.fire("Mensaje de error", errorThrown, "error");
        }
      });

}

async function clearAndDeleteDatabase() {
  try {
    const request = window.indexedDB.deleteDatabase('companny');

    request.onerror = function(event) {
      Swal.fire("Mensaje de error", "No se pudo eliminar la base de datos", "error");
    };

    request.onsuccess = function(event) {
      Swal.fire("Mensaje de Éxito", "La base de datos se eliminó correctamente", "success");
    };

  } catch (error) {
    console.error("Error al eliminar la base de datos:", error);
    Swal.fire("Mensaje de error", "Ocurrió un error al eliminar la base de datos", "error");
  }
}

//////////////////////////////////////////////////////////
////////////////////////////////IMPRECION////////////////
var filters = { data: [], company: '' };
function filtrarRegistrosPorFecha(data, fechaSeleccionada,btn,companyValue,url) {
 if (!data) {
    console.error('Error: data is undefined');
    return;
  }
  try {
    if (!fechaSeleccionada) return;
    filters.data= data.filter(function (record) {
      return record.fechaoperacion === fechaSeleccionada;
    });
   
   filters.company=companyValue;

     //
    fetch(url, { method: 'POST', headers: {  'Content-Type': 'application/json', },
    body: JSON.stringify(filters), // Convierte el objeto a formato JSON
  })
    .then(response => {
      if (!response.ok) {
        throw new Error(`Error en la solicitud: ${response.status} ${response.statusText}`);
      }
        

      return response.blob();
    })
    .then(blob => {
      const pdfData = URL.createObjectURL(blob);
     const nuevaVentana = window.open(pdfData, '_blank');

      if (!nuevaVentana) {
         Swal.fire("Mensaje de error",'No se pudo abrir la nueva ventana.', "error");
      }
    })
    .catch(error => console.error('Error al generar o obtener el PDF:', error));
  } catch (error) {
    Swal.fire("Mensaje de error",'Error en la función filtrarRegistrosPorFecha:'+error, "error");
  }

  toggleIconRollback(btn);
}


  function mostrarHora() {
    var fecha = new Date();
    var horas = fecha.getHours();
    var minutos = fecha.getMinutes();
    var segundos = fecha.getSeconds();
    var periodo = (horas < 12) ? 'AM' : 'PM';
    horas = (horas > 12) ? horas - 12 : horas;
    horas = (horas == 0) ? 12 : horas;
    horas = (horas < 10) ? '0' + horas : horas;
    minutos = (minutos < 10) ? '0' + minutos : minutos;
    segundos = (segundos < 10) ? '0' + segundos : segundos;
    var horaActual = horas + ':' + minutos + ':' + segundos + ' ' + periodo;
    var dia = fecha.getDate();
    var mes = fecha.getMonth() + 1; 
    var año = fecha.getFullYear();
    dia = (dia < 10) ? '0' + dia : dia;
    mes = (mes < 10) ? '0' + mes : mes;
    var fechaActual = año + '-' + mes + '-' + dia;
    document.getElementById('fecha').textContent = fechaActual ;
    document.getElementById('hora').textContent = horaActual;
}

 var passwordIcon = $(".show-hide-password i");
  $(".show-hide-password").on("click", function() {
    var passwordField = $("#text_paswoed");
    var fieldType = passwordField.attr("type");

    if (fieldType === "password") {
      passwordField.attr("type", "text");
      passwordIcon.removeClass("fa-eye-slash").addClass(" fa-eye");
    } else {
      passwordField.attr("type", "password");
      passwordIcon.removeClass("fa-eye").addClass("fa-eye-slash");
    }
  });
  $("#text_paswoed").on("input", function() {
    if ($(this).val().length > 0) {
      $(".show-hide-password").show();
    } else {
      $(".show-hide-password").hide();
    }
  });