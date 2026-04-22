document.addEventListener('DOMContentLoaded', function () {

    function validarFormulario() {
        const form = document.getElementById('registroForm');
        const nombre = document.getElementById('nom');
        const email = document.getElementById('email');
        const password = document.getElementById('pwd');
        const confirmPassword = document.getElementById('pwd-confirm');

        form.addEventListener('submit', (e) => {
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
        });
    }
    function mostrarUsuarioSeleccionado(nombre, imagen) {
        const botones = document.querySelectorAll('.usuario-btn');
            const divSeleccionado = document.getElementById('usuario-seleccionado');
            const nombreSel = document.getElementById('nombre-seleccionado');
            const imgSel = document.getElementById('imagen-seleccionada');
            
            botones.forEach(btn => {
                
                btn.addEventListener('click', function() {
                    botones.forEach(btn => {
                btn.classList.remove('btn-selected');
            });
                    btn.classList.add('btn-selected');

                    const nombre = this.getAttribute('data-nombre');
                    const imagen = this.getAttribute('data-imagen');

                    nombreSel.textContent = nombre;
                    if (imagen) {
                        imgSel.src = '../' + imagen;
                        imgSel.style.display = 'block';
                    } else {
                        imgSel.src = '../uploads/predeterminada.webp';
                        imgSel.style.display = 'block';
                    }
                    divSeleccionado.style.display = 'block';
                });
            });
    }
    mostrarUsuarioSeleccionado();
    validarFormulario();
});