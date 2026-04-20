const form = document.getElementById('registroForm');
const nombre = document.getElementById('nom');
const email = document.getElementById('email');
const password = document.getElementById('pwd');
const confirmPassword = document.getElementById('pwd-confirm');

form.addEventListener('submit',(e)=>{
    e.preventDefault();
    if(nombre.value == "" || email.value == "" || password.value == "" || confirmPassword.value == ""){
        alert("Por favor, completa todos los campos.");
        return;
    }else if (password.value.length < 8) {
                alert("La contraseña debe tener al menos 8 caracteres");
                return;
            } else if (password.value != confirmPassword.value) {
                alert("Las contraseñas no coinciden");
                return;
            }
    form.submit();
});