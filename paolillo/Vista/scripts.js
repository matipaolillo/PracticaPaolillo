document.addEventListener('DOMContentLoaded', function () {

const form = document.getElementById('registroForm');
const loginForm = document.getElementById('loginForm');

    loginForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const email = document.getElementById('login_email');
        const password = document.getElementById('login_password');
        fetch('../Controlador/api.php/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email, password })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'perfil.php';
            } else {
                alert('Error en el inicio de sesión');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });

    });
    form.addEventListener('submit',async (e) => {
    
        
        const nombre = document.getElementById('nom');
        const email = document.getElementById('email');
        const password = document.getElementById('pwd');
        const confirmPassword = document.getElementById('pwd-confirm');
        
        if(validarFormulario()){
            const fileInput = form.imagen;
            const file = fileInput.files[0];
            const reader = new FileReader();
                reader.onload = function(event) {
                    const data = {
                    nombre: nombre.value,
                    email: email.value,
                    password: password.value,
                    confirmPassword: confirmPassword.value
                };
            };
        console.log(JSON.stringify(data));
        fetch('../Controlador/api.php/usuarios', {

            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => async function() {
            const resJson = await response.json();
            if (!response.ok) {
                throw new Error(resJson.error || 'Error desconocido');
                
            }
            return resJson;
        }).then(success => {
            alert('Registro exitoso');
            form.reset();
            window.location.href = 'perfil.php';
        }   
        ).catch(error => {
            console.log('Error: ' + error.message);
        });
        
        reader.readAsDataURL(file);

    }else {
        alert('Error en el formulario');
        
    }
    });
    function validarFormulario() {
            e.preventDefault();
            if (nombre.value == "" || email.value == "" || password.value == "" || confirmPassword.value == "") {
                alert("Por favor, completa todos los campos.");
                return;
            } else if (password.value.length < 8) {
                alert("La contraseña debe tener al menos 8 caracteres");
                return;
            } else if (password.value != confirmPassword.value) {
                alert("Las contraseñas no coinciden");
                return;
            }
            form.submit();
        }
    
    


});