

function registerCompany(btn) {
    
   toggleIconChange(btn);
    const formData = new FormData(document.getElementById('companyForm'));
          formData.append('flagPath', document.getElementById('phatCurrentflag').value);
          formData.append('logoPath', document.getElementById('phatCurrentlogo').value);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controller/company/ControllerRegisterCompany.php', true);
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const response = JSON.parse(this.responseText);
            if(response.status)
                Swal.fire({position: 'top-end', icon: 'success', title: 'Éxito !!', text: response.msg, showConfirmButton: false, timer: 1500 })
                getCompanny();
        }else{
          if (this.status === 403) {
            Swal.fire("Mensaje de error", "No Autorizado.", "error");
        } else {
            Swal.fire("Mensaje de error", this.statusText, "error");
        }
    }
};
xhr.send(formData);
 
  toggleIconRollback(btn);
}

function previewImage(input, previewId) {
        var preview = document.getElementById(previewId);
        var file = input.files[0];
        var reader = new FileReader();

        reader.onloadend = function () {
            preview.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "";
        }
    }

    function removeImage(previewId, inputId) {
        var preview = document.getElementById(previewId);
        var input = document.getElementById(inputId);
        preview.src = "";
        input.value = "";
        var value =document.getElementById('phatCurrent'+inputId).value='';
    }





    // Nueva función para establecer la vista previa de la imagen
function setPreviewImage(inputId, previewId, imageUrl) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);

    // Verifica si hay una imagen cargada
    if (input && imageUrl) {
        preview.src = imageUrl;
        preview.style.display = 'block';  // Muestra la vista previa
    } else {
        preview.src = '';  // Limpia la vista previa
        preview.style.display = 'none';  // Oculta la vista previa
    }
}


async function getCompanny() {
    const companyId = 1;
    try {
        const response = await fetch(`../controller/company/ControllerEditCompany.php?id=${companyId}`);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        if (response.status) {
            const {data} = await response.json();
            localStorage.setItem("companny", JSON.stringify(data));
        } else {
          console.log('Error: La compañía no se encontró.');
        }
       
    } catch (error) {
       Swal.fire("Mensaje de error", error.responseText, "error");
    }
  
}