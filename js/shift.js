

function get_AllShifts() {
  $.post('../controller/shift/ControllerShowShifts.php',{ type : 'normal' })
    .done(function (resultado) {
      var response = JSON.parse(resultado);
      if (response.status) {
        let { data } = response;
        if(data.length>0){
        	var html ="";
        	$.each(data, function(i, item) {
        
            html += "<tr id='indece" + item.day_number + "'>";
              html += "<td style='display:none;' class='hidden-id'>" + item.id + "</td>";
            html += "<td>" + item.day_number + "</td>";
            html += "<td> <label class='form-control form-control-sm' >" + item.day_name + "</label> </td>";
            html += "<td><div class='form-group'><input class='form-control form-control-sm' id='morningentrytime' value='" + item.morning_entry_time + "' type='time' disabled></div> </td>";
            html += "<td><div class='form-group'><input class='form-control form-control-sm' id='morningexittime' value='" + item.morning_exit_time + "' type='time' disabled></div> </td>";
            html += "<td>||</td>";
            html += "<td><div class='form-group'><input class='form-control form-control-sm' id='afternoonentrytime' value='" + item.afternoon_entry_time + "' type='time' disabled></div> </td>";
            html += "<td><div class='form-group'><input class='form-control form-control-sm' id='afternoonexittime' value='" + item.afternoon_exit_time + "' type='time' disabled></div> </td>";
            html += "<td><button class='btn btn-secondary btn-sm' onclick='edit(this)'><em class='fa fa-edit'></em></button></td>";
            html += "</tr>";
        	});

        	$("#tbody_tabla_shifts").append(html)
        }
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

function saveChanges(btn) {
let lastModified= saveEdits();
if(lastModified.length===0) return Swal.fire("Mensaje de Advertencia","Sin datos para actualizar.", "warning");

  toggleIconChange(btn);
  clearStyle(lastModified);
 $.ajax({
    type: 'POST',
    url: '../controller/shift/ControllerUpdateShift.php',
    data: JSON.stringify(lastModified),
    contentType: 'application/json', // Añade esta línea
    success: function(resultado) {
        var response = JSON.parse(resultado);
        if (response.status) {
            Swal.fire({position: 'top-end', icon: 'success', title: 'Éxito !!', text: response.msg, showConfirmButton: false, timer: 1500 })
              desabledInput();
        } else if(response.number) {
            $("#indece" + response.number).css("background", "#f8d7da");
             Swal.fire("Mensaje de Advertencia", response.msg, "warning");
        } else {
            Swal.fire("Mensaje de Advertencia", response.msg, "warning");
        }
    },
     error: function(jqXHR, textStatus, errorThrown) {
        if (jqXHR.status === 403) {
            Swal.fire("Mensaje de error", "No Autorizado.", "error");
        } else {
            Swal.fire("Mensaje de error", jqXHR.responseText+','+errorThrown+','+textStatus, "error");
        }
    }
});
 
  toggleIconRollback(btn);
}

 
function saveEdits() {
var editedData = [];
  $("#tbody_tabla_shifts tr").each(function() {
    var row = $(this);
    var isDisabled = row.find("td:nth-child(4) input").prop("disabled");
    if (!isDisabled) {
      
    var id = row.find("td.hidden-id").text();
    var dayNumber = row.find("td:nth-child(2)").text();
    var nameday = row.find("td:nth-child(3) label").text();
    var morningEntryTime = row.find("td:nth-child(4) input").val();
    var morningExitTime = row.find("td:nth-child(5) input").val();
    var afternoonEntryTime = row.find("td:nth-child(7) input").val();
    var afternoonExitTime = row.find("td:nth-child(8) input").val();

    var rowData = {id: id,dayNumber: dayNumber,nameday: nameday,morningEntryTime: morningEntryTime,
        morningExitTime: morningExitTime,afternoonEntryTime: afternoonEntryTime,afternoonExitTime: afternoonExitTime
    };

    var index = editedData.findIndex(item => item.dayNumber === dayNumber); if (index !== -1) return;
     editedData.push(rowData);

    }
   });
   return editedData;
}

function edit(btn) {
    var row = $(btn).closest("tr");
    row.find('input[type="time"]').prop('disabled', false);
    $("#btnregister").prop('disabled', false);
}

function desabledInput(){
  $("#tbody_tabla_shifts tr").each(function(i,item) {
    var row = $(this);
    row.find('input[type="time"]').prop('disabled', true);
    $("#indece"+new Number(i+1)).removeAttr("style");
});
    $("#btnregister").prop('disabled', true);
}

function clearStyle(lastModified){
  for (var i = 0; i < lastModified.length; i++) {
    $("#indece"+new Number(lastModified[i].dayNumber)).removeAttr("style");
}

}



