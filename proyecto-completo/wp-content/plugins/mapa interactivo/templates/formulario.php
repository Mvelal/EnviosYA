<!--
*   Nombre: formulario.php
*   Uso: Estructura HTML del formulario en Modal
-->


<!-- Contenedor del Modal (inicialmente oculto) -->
<div class="modal modal--hidden" id="formModal">
    <div class="modal__content">
        <button class="modal__close" id="closeModalBtn" aria-label="Cerrar modal">&times;</button>

        <!-- Formulario de Pasos -->
        <form id="formSteps" class="form-steps" novalidate>
            <input type="hidden" name="post_id" id="form-post-id">

            <!-- Barra de Progreso -->
            <div class="form-steps__progress-bar">
                <div class="form-steps__indicator form-steps__indicator--active">1</div>
                <div class="form-steps__line"></div>
                <div class="form-steps__indicator">2</div>
                <div class="form-steps__line"></div>
                <div class="form-steps__indicator">3</div>
            </div>

            <!-- PASO 1: SELECCIÓN DE TIPO (ACTUALIZADO CON RADIO BUTTONS) -->
            <div class="form-steps__step form-steps__step--active">
                <h3 class="form-steps__title" id="formTitle">Selecciona el tipo de registro</h3>
                <div class="selection-container">
                    <!-- Opción Individual -->
                    <label class="selection-card" for="choice-individual">
                        <input type="radio" name="form_type" id="choice-individual" value="individual" class="selection-card__radio">
                        <img src="http://exploraya.local/wp-content/uploads/2025/06/solo.webp" alt="Registro Individual">
                        <span>Individual</span>
                    </label>
                    <!-- Opción Grupal -->
                    <label class="selection-card" for="choice-grupal">
                        <input type="radio" name="form_type" id="choice-grupal" value="grupal" class="selection-card__radio">
                        <img src="http://exploraya.local/wp-content/uploads/2025/06/grupal.webp" alt="Registro Grupal">
                        <span>Grupal</span>
                    </label>
                </div>
            </div>

            <!-- PASO 2: DATOS DE LOS PARTICIPANTES (CONDICIONAL) -->
            <div class="form-steps__step">
                <h3 class="form-steps__title">Ingresa los datos</h3>
                <!-- Campos para MODO INDIVIDUAL -->
                <div class="conditional-fields" data-visible-if="individual">
                    <div class="form-steps__field">
                        <label for="individual-nombre">Nombre Completo:</label>
                        <input type="text" id="individual-nombre" class="form-steps__input" name="nombre_individual" required>
                    </div>
                    <div class="form-steps__field">
                        <label for="individual-edad">Edad:</label>
                        <input type="number" id="individual-edad" class="form-steps__input" name="edad_individual" required>
                    </div>
                </div>
                <!-- Campos para MODO GRUPAL -->
                <div class="conditional-fields" data-visible-if="grupal">
                    <div id="group-members-container"></div>
                    <button type="button" id="add-member-btn" class="form-steps__button form-steps__button--add">+ Añadir Persona</button>
                </div>
            </div>

            <!-- PASO 3: CORREO (COMÚN PARA AMBOS) -->
            <div class="form-steps__step">
                <h3 class="form-steps__title">Información de Contacto</h3>
                <div class="form-steps__field">
                    <label for="correo">Correo Electrónico de Contacto:</label>
                    <input type="email" id="correo" class="form-steps__input" name="correo_contacto" required>
                </div>
            </div>

            <!-- Navegación -->
            <div class="form-steps__nav">
                <button type="button" class="form-steps__button form-steps__button--previous" id="btnAnterior">Anterior</button>
                <button type="button" class="form-steps__button" id="btnSiguiente">Siguiente</button>
                <button type="submit" class="form-steps__button" id="btnEnviar">Enviar</button>
            </div>
        </form>
    </div>
</div>