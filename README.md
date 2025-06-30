# Proyecto WordPress - Exploraya

Este proyecto forma parte de un ejercicio técnico que consiste en desarrollar un **theme personalizado desde cero** en WordPress, junto a un **plugin propio** que incluye un mapa interactivo con formulario en pasos.

---

## 📌 Instrucciones de instalación

Existen **3 formas de instalar el proyecto**:

---

### 🟢 Opción 1: Usar la carpeta `proyecto-completo`

1. Descarga y descomprime la carpeta `proyecto-completo`.
2. Copia todos los archivos dentro de una instalación local o servidor.
3. Configura el archivo `wp-config.php` con tus datos de base de datos.
4. Accede al sitio y verifica que el plugin y theme estén activos.

---

### 🟠 Opción 2: Instalación limpia de WordPress

1. Instala WordPress desde cero.
2. Para instalar el plugin:
   - Comprime la carpeta `mapa-interactivo` en `.zip`.
   - Ve al panel de administración → Plugins → Añadir nuevo → Subir plugin.
3. Para instalar el tema:
   - Comprime la carpeta `exploraya` en `.zip`.
   - Ve a Apariencia → Temas → Añadir nuevo → Subir tema.
4. Crea una nueva página y pega el siguiente shortcode:

   [mapa_interactivo]

⚠️ IMPORTANTE: Elimina los paréntesis y cualquier texto adicional del nombre de las carpetas antes de comprimirlas (mapa-interactivo, exploraya).

--- 

### 🔵 Opción 3: Restauración con UpdraftPlus

1. Realiza una instalación limpia de WordPress.

2. Instala el plugin UpdraftPlus (https://co.wordpress.org/plugins/updraftplus/).

3. Copia los archivos que están dentro de la carpeta updraftplus (incluida en este repositorio) dentro del directorio de backups de UpdraftPlus.

4. Desde el panel de UpdraftPlus, selecciona "Restaurar" y sigue los pasos.

---

### 🎨 Compilar archivos SCSS

Para compilar los archivos SCSS del proyecto (ubicados en mapa-interactivo/sass):

1. Instalar Node.js y npm
    - Descarga e instala desde: https://nodejs.org
    - Verifica que está instalado: npm -v
    - Instalar SASS: npm install -g sass
    - Compilar SCSS a CSS con watch: sass sass:css --watch

---

### 📦 Dependencias necesarias

- WordPress 6.0 o superior

- **Plugins recomendados**:
    - Elementor (opcional, como apoyo visual)

- **Librerías utilizadas**:
    - Leaflet.js – para el mapa interactivo
    - jQuery – incluido por defecto en WordPress

    Ambas están incluidas directamente dentro del plugin mapa-interactivo, no necesitas instalarlas por separado.

---

### 🗺️ Página de ejemplo

Tienes dos formas de visualizar la funcionalidad del mapa y formulario:


1. En línea
🔗 https://exploraya.wevvy.co/

2. En instalación propia
    - Crea una nueva página en WordPress e inserta el shortcode:
        [mapa_interactivo]

        - Esto mostrará un mapa con marcadores desde el CPT. Al hacer clic en un marcador:
        - Se muestra información de la ubicación.
        - Se abre un formulario en pasos en un modal.
        - El primer paso tiene dos botones con imagen. El flujo del formulario depende de la opción  elegida.
        - El formulario se envía vía AJAX sin recarga.

⚠️ **IMPORTANTE**: Recomiendo probarlo desde la primera opcion.


### 🔐 Accesos

- Para entrar a la pagina las credenciales son:
    1. En https://exploraya.wevvy.co/ : 
        - Usuario: admin
        - Password: WbP5IH5S4QZZCuBChYQK0iYN

    2. En local
        - Usuario: admin
        - Password: admin
