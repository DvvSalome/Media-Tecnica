🐾 Huellitas de Amor – Sistema de Adopción de Perros

Sistema web desarrollado en PHP y MySQL para gestionar la adopción de perros. Permite que el admin agregue, edite y elimine perros, y que los usuarios vean los perros disponibles para adopción.

📂 Estructura de archivos
/huellitas_de_amor
│
├─ index.php           # Página principal pública
├─ panel.php           # Panel de administración
├─ agregar_perro.php   # Agregar un nuevo perro
├─ editar_perro.php    # Editar perro existente
├─ eliminar_perro.php  # Eliminar perro
├─ ver_perro.php       # Detalles individuales de cada perro (opcional)
├─ logout.php          # Cierra la sesión del admin
├─ /img/               # Carpeta para fotos de los perros
└─ README.md           # Este archivo

💻 Requisitos

PHP 7.4 o superior

MySQL / MariaDB

Servidor local (XAMPP, WAMP, MAMP, Laragon)

Navegador moderno

🛠 Instalación

Clonar o descargar el proyecto en la carpeta raíz de tu servidor local (htdocs o www).

Crear la base de datos MySQL:

CREATE DATABASE huellitasdb;
USE huellitasdb;

CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE perros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    edad INT NOT NULL,
    raza VARCHAR(100) NOT NULL,
    tamano VARCHAR(50) NOT NULL,
    descripcion TEXT,
    estado VARCHAR(50) DEFAULT 'disponible',
    foto VARCHAR(255),
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


Crear un usuario admin inicial:

<?php
// Crear contraseña hasheada
echo password_hash("tu_contraseña", PASSWORD_DEFAULT);
?>


Luego insertar en la tabla admin:

INSERT INTO admin (username, password) VALUES ('admin', 'hash_generado');

⚙ Configuración

Editar las credenciales de conexión en index.php, panel.php, agregar_perro.php, editar_perro.php, eliminar_perro.php:

$servername = "localhost";
$username = "root";     // tu usuario MySQL
$password = "";         // tu contraseña MySQL
$dbname = "huellitasdb";


Crear carpeta img/ con permisos de escritura para subir fotos de perros.

🚀 Uso
Usuario

Abrir index.php.

Ver listado de perros disponibles.

Hacer clic en “Conocer más” para detalles del perro (opcional).

Admin

usuario:admin
clave:1234

Ingresar con usuario y contraseña en el modal de login del index.php o ir directamente al panel.php si ya está logueado.

En el panel puede:

Agregar nuevos perros

Editar información de perros existentes

Eliminar perros

Volver al sitio público

Cerrar sesión

📌 Notas

Las fotos se almacenan en la carpeta img/.

Asegurarse de que la carpeta img/ tenga permisos de escritura.

La contraseña del admin está hasheada por seguridad.

El sistema utiliza Bootstrap 5 para diseño responsivo y modales.

💖 Autor

Huellitas de Amor – Proyecto de adopción de perros.
Desarrollado por: yamilferreyra@gmail.com