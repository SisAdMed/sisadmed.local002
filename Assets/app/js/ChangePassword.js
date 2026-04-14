//Validar campos para ver si se prosigue el proceso de cambio
$(function() {
	$("form[name='my_form']").validate({        
		rules: {
			password: "required",
			repassword: "required",           
		},
		messages: {
			password: "Debe especificar un Password",
			repassword: "Debe confirmar el Password ingresado",            
		},
		submitHandler: function(form) {
			form.btnChange();
		}
	});
});
//Al presionar el Boton cambiar password
$("#btnChange").click(function(event){
	event.preventDefault();		
	password = $("#password").val();
	repassword = $("#repassword").val();	
	if(password.length == 0){	
		$("#password").focus();			
		Swal.fire({
			icon: 'error',
			title: 'Oops...',
			text: 'Debe especificar un Password.',				
		});		
	}else if(repassword.length == 0){
		$("#repassword").focus();		
		Swal.fire({
			icon: 'error',
			title: 'Oops...',
			text: 'Debe especificar la confirmación del Password.',				
		});	
	}else if(password != repassword){
		Swal.fire({
			icon: 'error',
			title: 'Oops...',
			text: 'Las contraseñas indicadas no coinciden, por favor valide y vuelva a intentar.',				
		});
	}else if(password == repassword){
		Swal.fire({
			title: '¿Está seguro que desea cambiar su contraseña?',
			showDenyButton: true,			
			confirmButtonText: 'Guardar',
			denyButtonText: `No Guardar`,
		}).then((result) => {
  			/* Read more about isConfirmed, isDenied below */
			if (result.isConfirmed) {
				let password = $("#password").val();
				const cla_new = validate_change(password);				
				if(cla_new){					
					Swal.fire('Contraseña actualizada satisfactoriamente! Debe salir e iniciar sesión nuevamente.', '', 'success')

				}else{
					Swal.fire('Se produjo un error al guarda la contraseña, por favor intente mas tarde.', '', 'info')
				};										
			} else if (result.isDenied) {
				Swal.fire('Los cambios no serán guardados', '', 'info')
			};
		});
	};
});
//
async function validate_change(password){
    const xPassword = await cam_clav(password);     
    return xPassword;
}
//validar que se haya realizado el cambio de clave
var cam_clav = async function (cla_new){    
    var datos = new FormData();    
    datos.append('password', cla_new);
    try{
        const url = `${base_url}/Login/ChangePassword`;          
        var respuesta = await fetch (url, {
            method: "POST",
            body: datos,
        });                   
        var resultado = await respuesta.json();         
        if(resultado){
        	return new Promise((resolve, reject) => {
            setTimeout(() => {
                resolve(resultado);
	            }, 1);
	        }) ;  
        }                 
    }catch (err){
        console.log(err);
    }
}