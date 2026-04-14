//Controlar tiempo de expiración de la sesion
n = `${time_logout}`;
var l = document.getElementById("number");
let msg = document.getElementById("timeout");
var id = window.setInterval(function(){
	document.onmousemove = function(){
		if(msg.style.visibility == "visible"){
			msg.style.visibility = 'hidden';
		}
		n = `${time_logout}`;
	};		
	l.innerText = n;
	n--;	
	if(n == 30){
		if(msg.style.visibility == "hidden"){
			msg.style.visibility = 'visible';
		}
	}
	if(n == 0){
		if(msg.style.visibility == "visible"){
			msg.style.visibility = 'hidden';
		}
		Swal.fire({
			icon: 'error',
			title: 'Oops...',
			text: 'Su sesión ha expirado por motivos de inactividad',			
		}).then((result) => {
			if(result.isConfirmed){
				window.location.href = `${base_url}/Logout`;	
			}			
		});
	}
},1000);