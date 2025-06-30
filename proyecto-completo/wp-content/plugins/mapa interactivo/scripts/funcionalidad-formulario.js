/*
*   Nombre: funcionalidad-formulario.js
*   Uso: Funcionalidad del formulario de contacto y modal
*/




/**
 * Script principal para gestionar la lógica de un formulario multipasos con campos dinámicos y un modal emergente.
 *
 * Este script se ejecuta una vez que el DOM ha sido cargado, y maneja las siguientes funcionalidades:
 *
 * 1. MODAL:
 *    - Abre y cierra el modal que contiene el formulario usando clases CSS.
 *    - Permite cerrar el modal al hacer clic fuera del contenido o presionar la tecla Escape.
 *
 * 2. FORMULARIO MULTIPASOS:
 *    - Controla los pasos del formulario, los botones de navegación (anterior, siguiente, enviar)
 *      y los indicadores visuales de progreso.
 *    - Valida los campos requeridos en cada paso, solo si están visibles.
 *    - Muestra u oculta secciones condicionales basadas en la selección del usuario.
 *    - Agrega y elimina dinámicamente campos para miembros adicionales (máximo 5).
 *    - Reinicia el formulario al abrir el modal, limpiando selección, errores y miembros.
 *
 * 3. ENVÍO DEL FORMULARIO:
 *    - Al llegar al último paso y validar correctamente, el formulario se envía vía AJAX
 *      usando una función externa (`enviarAjax`) hacia la acción `enviar_correo`.
 *    - Muestra una alerta de confirmación y cierra el modal una vez enviado.
 *
 * El código está optimizado para ser reutilizable, modular y fácil de escalar.
 */


document.addEventListener('DOMContentLoaded', () => {

    // ---- LÓGICA DEL MODAL ----
    const formModal = document.getElementById('formModal');
    const closeModalBtn = document.getElementById('closeModalBtn');

    const openModal = () => {
        if (formModal) formModal.classList.remove('modal--hidden');
    };
    
    const closeModal = () => {
        if (formModal) formModal.classList.add('modal--hidden');
    };

    // ---- LÓGICA DEL FORMULARIO ----
    const form = document.getElementById('formSteps');

    if (form) {
        // ---- SELECCIÓN DE ELEMENTOS ----
        const steps = form.querySelectorAll('.form-steps__step');
        const indicators = form.querySelectorAll('.form-steps__indicator');
        const btnAnterior = document.getElementById('btnAnterior');
        const btnSiguiente = document.getElementById('btnSiguiente');
        const btnEnviar = document.getElementById('btnEnviar');
        
        const selectionCards = form.querySelectorAll('.selection-card');
        const conditionalFields = form.querySelectorAll('.conditional-fields');
        const addMemberBtn = document.getElementById('add-member-btn');
        const membersContainer = document.getElementById('group-members-container');
        const MAX_MEMBERS = 5;
        let memberCount = 0;
        let currentStep = 0;

        // ---- FUNCIÓN DE VALIDACIÓN (NUEVA) ----
        const validateStep = (stepIndex) => {
            let isValid = true;
            const currentStepElement = steps[stepIndex];
            
            const inputsToValidate = currentStepElement.querySelectorAll('input[required], textarea[required], select[required]');

            inputsToValidate.forEach(input => {

                if (input.offsetParent !== null) {
                    const fieldContainer = input.closest('.form-steps__field');
                    if (!input.checkValidity()) {
                        isValid = false;
                        input.classList.add('form-steps__input--invalid');
                        if (fieldContainer) fieldContainer.classList.add('is-invalid');
                    } else {
                        input.classList.remove('form-steps__input--invalid');
                        if (fieldContainer) fieldContainer.classList.remove('is-invalid');
                    }
                }
            });

            return isValid;
        };

        // ---- FUNCIÓN PARA REINICIAR EL FORMULARIO ----
        const resetForm = (postId) => {
            form.reset(); 
            const postIdInput = document.getElementById('form-post-id');
            if (postIdInput) postIdInput.value = postId;

            currentStep = 0;
            showStep(currentStep);

            if(membersContainer) membersContainer.innerHTML = '';
            memberCount = 0;
            if(addMemberBtn) addMemberBtn.hidden = false;

            selectionCards.forEach(c => c.classList.remove('selected'));
            conditionalFields.forEach(group => group.style.display = 'none');

            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            form.querySelectorAll('.form-steps__input--invalid').forEach(el => el.classList.remove('form-steps__input--invalid'));
        };

        // ---- LÓGICA DE SELECCIÓN (PASO 1) ----
        form.addEventListener('change', (event) => {
            if (event.target.name === 'form_type') {
                const selectedCard = event.target.closest('.selection-card');
                form.querySelectorAll('.selection-card').forEach(c => c.classList.remove('selected'));
                if (selectedCard) selectedCard.classList.add('selected');
                if(btnSiguiente) btnSiguiente.disabled = false;
            }
        });

        // ---- LÓGICA PARA CAMPOS DINÁMICOS (PASO 2) ----
        const addMemberField = () => {
            if (memberCount >= MAX_MEMBERS) return;
            memberCount++;
            const newMemberRow = document.createElement('div');
            newMemberRow.className = 'member-row';

            newMemberRow.innerHTML = `
                <div class="form-steps__field">
                    <label for="miembro-nombre-${memberCount}">Nombre Miembro ${memberCount}:</label>
                    <input type="text" id="miembro-nombre-${memberCount}" name="miembro_nombre[]" class="form-steps__input" required>
                </div>
                <div class="form-steps__field">
                    <label for="miembro-edad-${memberCount}">Edad:</label>
                    <input type="number" id="miembro-edad-${memberCount}" name="miembro_edad[]" class="form-steps__input" required>
                </div>
                <button type="button" class="form-steps__button form-steps__button--remove">X</button>
            `;
            if(membersContainer) membersContainer.appendChild(newMemberRow);
            if (memberCount >= MAX_MEMBERS) {
                if(addMemberBtn) addMemberBtn.hidden = true;
            }
        };

        if (addMemberBtn) addMemberBtn.addEventListener('click', addMemberField);
        
        if (membersContainer) {
            membersContainer.addEventListener('click', (event) => {
                if (event.target.classList.contains('form-steps__button--remove')) {
                    event.target.closest('.member-row').remove();
                    memberCount--;
                    if(addMemberBtn) addMemberBtn.hidden = false;
                }
            });
        }
        
        // ---- LÓGICA DE NAVEGACIÓN ----
        const showStep = (index) => {
            steps.forEach((step, i) => step.classList.toggle('form-steps__step--active', i === index));
            indicators.forEach((indicator, i) => {
                indicator.classList.remove('form-steps__indicator--active', 'form-steps__indicator--completed');
                if (i < index) indicator.classList.add('form-steps__indicator--completed');
                else if (i === index) indicator.classList.add('form-steps__indicator--active');
            });
            
            if(btnAnterior) btnAnterior.style.display = index === 0 ? 'none' : 'inline-block';
            if(btnSiguiente) btnSiguiente.style.display = index === steps.length - 1 ? 'none' : 'inline-block';
            if(btnEnviar) btnEnviar.style.display = index === steps.length - 1 ? 'inline-block' : 'none';

            const checkedRadio = form.querySelector('input[name="form_type"]:checked');
            if (index === 0) {
                if(btnSiguiente) btnSiguiente.disabled = !checkedRadio;
            } else {
                if(btnSiguiente) btnSiguiente.disabled = false;
            }
        };

        const handleNextStep = () => {
            if (!validateStep(currentStep)) {
                alert("hay errores en tus campos, verifica");
                return; 
            }

            const checkedRadio = form.querySelector('input[name="form_type"]:checked');
            const choice = checkedRadio ? checkedRadio.value : null;

            if (currentStep === 0 && choice) {
                conditionalFields.forEach(group => {
                    group.style.display = group.dataset.visibleIf === choice ? 'block' : 'none';
                });
                if (choice === 'grupal' && memberCount === 0) {
                    addMemberField();
                }
            }
            
            if (currentStep < steps.length - 1) {
                currentStep++;
                showStep(currentStep);
            }
        };

        if(btnSiguiente) btnSiguiente.addEventListener('click', handleNextStep);
        if(btnAnterior) {
            btnAnterior.addEventListener('click', () => {
                if (currentStep > 0) {
                    currentStep--;
                    showStep(currentStep);
                }
            });
        }

        form.addEventListener('submit', (e) => {
            e.preventDefault();

            if (validateStep(currentStep)) {
                const formData = new FormData(form);
                for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
                }
                formData.append('action', 'enviar_correo'); // acción para admin-ajax.php

                enviarAjax('enviar_correo', formData, function(data) {
                    if (data) {
                        console.log(data);
                    }
                });

                alert('¡Formulario enviado!');
                closeModal();
            }
        });

        
        showStep(currentStep);

        // ---- LÓGICA DEL MODAL (Parte 2 - Eventos) ----
        document.body.addEventListener('click', function(event) {
            const button = event.target.closest('.openModalBtn');
            if (button) {
                const postId = button.dataset.titulo;
                resetForm(postId);
                openModal();
            }
        });
    }

    if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
    if (formModal) {
        formModal.addEventListener('click', (event) => {
            if (event.target === formModal) closeModal();
        });
    }
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && formModal && !formModal.classList.contains('modal--hidden')) {
            closeModal();
        }
    });

});
