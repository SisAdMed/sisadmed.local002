const inputs = document.querySelectorAll(".input");

function addcl(){
	let parent = this.parentNode.parentNode;
	parent.classList.add("focus");
}

function remcl(){
	let parent = this.parentNode.parentNode;
	if(this.value == ""){
		parent.classList.remove("focus");
	}
}

inputs.forEach(input => {
	input.addEventListener("focus", addcl);
	input.addEventListener("blur", remcl);
});
document.querySelector('#loginForm').addEventListener('submit', function(e) {
	e.preventDefault();
	login();
})

async function login(){
	let loginForm = document.querySelector('#loginForm');
	const datos = new FormData(loginForm);

	try {
		const url = `${base_url}/Login/ingresar`;
		const respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const resultado = await respuesta.json();
		if(resultado.error){
			new Noty({
				type: 'error',
				text: `${resultado.error}`,
				layout: "topCenter",
				theme: "metroui",
				timeout: 1500
			}).show();
		}else{
			new Noty({
				type: 'success',
				text: `${resultado.msg}`,
				layout: "topCenter",
				theme: "metroui",
				timeout: 1500
			}).show();

			setTimeout(()=>{
				window.location.href = `${base_url}/Perfil`;
			}, 1500)
		}
	} catch (err) {
		console.log(err);
	}
};
