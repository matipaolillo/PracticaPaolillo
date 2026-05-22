document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('registroForm');
    const nombre = document.getElementById('nom');
    const email = document.getElementById('email');
    const password = document.getElementById('pwd');
    const confirmPassword = document.getElementById('pwd-confirm');
    const fileInput = document.getElementById('foto');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        if (!validarFormulario(nombre, email, password, confirmPassword)) {
            return;
        }

        const file = fileInput.files[0];
        if (!file) {
            alert('Selecciona una imagen.');
            return;
        }

        const reader = new FileReader();
        reader.onload = async function (event) {
            const data = {
                usr_name: nombre.value.trim(),
                usr_email: email.value.trim(),
                usr_pass: password.value,
                usr_pass_confirm: confirmPassword.value,
                imagen: event.target.result
            };

            try {
                const response = await fetch('../Controlador/api.php/usuarios', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const resJson = await response.json();
                if (!response.ok) {
                    alert(resJson.error || 'Error al registrar');
                    return;
                }

                
                form.reset();
                window.location.href = 'sesion.php';
                console.log(resJson);
            } catch (error) {
                console.error('Error:', error);
                alert('Error en la solicitud: ' + error.message);
            }
        };

        reader.readAsDataURL(file);
    });

    function validarFormulario(nombre, email, password, confirmPassword) {
        if (nombre.value === "" || email.value === "" || password.value === "" || confirmPassword.value === "") {
            alert("Por favor, completa todos los campos.");
            return false;
        }
        if (password.value.length < 8) {
            alert("La contraseña debe tener al menos 8 caracteres");
            return false;
        }
        if (password.value !== confirmPassword.value) {
            alert("Las contraseñas no coinciden");
            return false;
        }
        return true;
    }
});