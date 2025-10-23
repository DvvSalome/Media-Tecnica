<?php
$servername = "localhost";
$username = "root";   // tu usuario MySQL
$password = "";       // tu contraseña MySQL
$dbname = "huellitasdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener ID del perro
if (!isset($_GET['id'])) {
    die("❌ Perro no especificado.");
}
$perro_id = intval($_GET['id']);

// Buscar perro
$sql = "SELECT * FROM perros WHERE id = $perro_id LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("❌ No se encontró el perrito.");
}
$perro = $result->fetch_assoc();

// Procesar formulario de adopción
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_usuario = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $mensaje = $_POST['mensaje'];

    $stmt = $conn->prepare("INSERT INTO solicitudes (perro_id, nombre_usuario, email, telefono, mensaje) 
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $perro_id, $nombre_usuario, $email, $telefono, $mensaje);

    if ($stmt->execute()) {
        $exito = "✅ Tu solicitud fue enviada. Nos pondremos en contacto pronto.";
    } else {
        $error = "❌ Hubo un error al enviar tu solicitud.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($perro['nombre']) ?> - Huellitas de Amor 🐾</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --warm-cream: #FEFCF8;
            --soft-beige: #F8F6F1;
            --pure-white: #FFFFFF;
            --light-gray: #F5F5F5;
            --accent-brown: #8B6F47;
            --accent-soft: #C8A882;
            --text-dark: #333333;
            --text-muted: #666666;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: var(--warm-cream);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            color: var(--text-dark);
        }

        /* Floating paws animation - more subtle */
        .floating-hearts {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .heart {
            position: absolute;
            color: var(--accent-soft);
            font-size: 14px;
            animation: float 15s infinite linear;
            opacity: 0.03;
        }

        @keyframes float {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 0.03;
            }
            90% {
                opacity: 0.03;
            }
            100% {
                transform: translateY(-100px) rotate(360deg);
                opacity: 0;
            }
        }

        /* Navbar styles */
        .navbar {
            background: var(--pure-white) !important;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            border-bottom: 1px solid var(--soft-beige);
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.4rem;
            color: var(--accent-brown) !important;
            transition: color 0.3s ease;
        }

        .navbar-brand:hover {
            color: var(--accent-soft) !important;
        }

        .btn-back {
            background: var(--soft-beige);
            border: 1px solid var(--accent-soft);
            color: var(--accent-brown);
            border-radius: 20px;
            padding: 8px 16px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .btn-back:hover {
            background: var(--accent-soft);
            color: white;
            text-decoration: none;
        }

        /* Dog card styles */
        .dog-card {
            background: var(--pure-white);
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            border: 1px solid var(--soft-beige);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .dog-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .dog-image {
            border-radius: 15px;
            transition: transform 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .dog-image:hover img {
            transform: scale(1.05);
        }

        .dog-image img {
            transition: transform 0.3s ease;
        }

        .dog-info h2 {
            color: var(--accent-brown);
            font-weight: 700;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .dog-info h2::after {
            content: '🐾';
            position: absolute;
            right: -30px;
            top: 0;
            animation: bounce 3s infinite;
            opacity: 0.7;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-8px);
            }
            60% {
                transform: translateY(-4px);
            }
        }

        .info-badge {
            background: var(--accent-soft);
            color: white;
            padding: 8px 16px;
            border-radius: 15px;
            display: inline-block;
            margin: 5px;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(200, 168, 130, 0.2);
            transition: all 0.3s ease;
        }

        .info-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 12px rgba(200, 168, 130, 0.3);
            background: var(--accent-brown);
        }

        .description-box {
            background: var(--soft-beige);
            border-left: 4px solid var(--accent-soft);
            padding: 1.5rem;
            border-radius: 12px;
            margin: 1.5rem 0;
        }

        .available-badge {
            background: var(--accent-soft);
            color: white;
            padding: 8px 12px;
            border-radius: 15px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Form styles */
        .adoption-form {
            background: var(--pure-white);
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--soft-beige);
            position: relative;
        }

        .form-title {
            color: var(--accent-brown);
            font-weight: 600;
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-control {
            border: 2px solid var(--light-gray);
            border-radius: 12px;
            padding: 12px 16px;
            transition: all 0.3s ease;
            background: var(--pure-white);
        }

        .form-control:focus {
            border-color: var(--accent-soft);
            box-shadow: 0 0 10px rgba(200, 168, 130, 0.15);
            background: var(--warm-cream);
        }

        .form-label {
            color: var(--accent-brown);
            font-weight: 500;
            margin-bottom: 8px;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--accent-soft), var(--accent-brown));
            border: none;
            border-radius: 20px;
            padding: 15px 30px;
            font-weight: 600;
            color: white;
            box-shadow: 0 4px 15px rgba(200, 168, 130, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(200, 168, 130, 0.3);
        }

        /* Alert styles */
        .alert-success {
            background: linear-gradient(135deg, #E8F5E8, var(--soft-beige));
            border: 1px solid #C3E6C3;
            border-radius: 15px;
            color: #2d5016;
            animation: slideIn 0.5s ease;
        }

        .alert-danger {
            background: linear-gradient(135deg, #FFE8E8, var(--soft-beige));
            border: 1px solid #FFB3B3;
            border-radius: 15px;
            color: #8B0000;
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Footer */
        footer {
            background: linear-gradient(135deg, var(--accent-brown), #5A4A3A);
            position: relative;
        }

        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--accent-soft);
            opacity: 0.5;
        }

        /* Decorative elements - more subtle */
        .paw-print {
            position: absolute;
            color: var(--accent-soft);
            opacity: 0.03;
            font-size: 2rem;
            animation: rotate 20s infinite linear;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .section-divider {
            height: 1px;
            background: var(--accent-soft);
            margin: 3rem 0;
            border-radius: 1px;
            opacity: 0.3;
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .dog-card {
                margin: 1rem 0;
            }
            
            .dog-info h2::after {
                right: -20px;
                font-size: 0.8em;
            }

            .btn-back {
                font-size: 0.9rem;
                padding: 6px 12px;
            }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--light-gray);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--accent-soft);
            border-radius: 5px;
            opacity: 0.7;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--accent-brown);
            opacity: 1;
        }
    </style>
</head>
<body>

<!-- Floating paws background -->
<div class="floating-hearts" id="floatingHearts"></div>

<!-- Decorative paw prints -->
<div class="paw-print" style="top: 15%; left: 8%;"><i class="fas fa-paw"></i></div>
<div class="paw-print" style="top: 70%; right: 8%;"><i class="fas fa-paw"></i></div>
<div class="paw-print" style="bottom: 25%; left: 12%;"><i class="fas fa-paw"></i></div>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-paw" style="color: var(--accent-soft);"></i> Huellitas de Amor
        </a>
        <div class="ms-auto">
            <a href="javascript:history.back()" class="btn-back">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</nav>

<!-- FICHA DEL PERRITO -->
<div class="container py-5" style="position: relative; z-index: 2;">
    <div class="dog-card p-4">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <div class="dog-image position-relative">
                    <?php if ($perro['foto']) { ?>
                        <img src="<?= $perro['foto'] ?>" 
                             class="img-fluid rounded shadow-sm w-100" 
                             alt="<?= htmlspecialchars($perro['nombre']) ?>" 
                             style="height: 400px; object-fit: cover;">
                    <?php } else { ?>
                        <div class="bg-light text-muted text-center d-flex align-items-center justify-content-center rounded" style="height: 400px; font-size: 1.2rem;">
                            <div>
                                <i class="fas fa-camera fa-3x mb-3" style="color: var(--accent-soft);"></i>
                                <br>Sin foto disponible
                            </div>
                        </div>
                    <?php } ?>
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="available-badge">
                            <i class="fas fa-heart"></i> Disponible
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 dog-info">
                <h2 class="display-4 mb-4"><?= htmlspecialchars($perro['nombre']) ?></h2>
                
                <div class="mb-4">
                    <span class="info-badge">
                        <i class="fas fa-birthday-cake"></i> <?= $perro['edad'] ?> años
                    </span>
                    <span class="info-badge">
                        <i class="fas fa-dog"></i> <?= htmlspecialchars($perro['raza']) ?>
                    </span>
                    <span class="info-badge">
                        <i class="fas fa-ruler-vertical"></i> <?= $perro['tamano'] ?>
                    </span>
                </div>

                <div class="description-box">
                    <p class="lead mb-0">
                        <?= nl2br(htmlspecialchars($perro['descripcion'])) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section divider -->
<div class="container">
    <div class="section-divider"></div>
</div>

<!-- FORMULARIO DE ADOPCIÓN -->
<div class="container py-5" style="position: relative; z-index: 2;">
    <div class="adoption-form mx-auto p-5" style="max-width: 700px;">
        <h3 class="form-title display-6">
            <i class="fas fa-paw" style="color: var(--accent-soft);"></i>
            ¡Quiero adoptar a <?= htmlspecialchars($perro['nombre']) ?>!
        </h3>
        
        <?php if (isset($exito)) { ?>
            <div class="alert alert-success text-center mb-4">
                <i class="fas fa-check-circle"></i> <?= $exito ?>
            </div>
        <?php } elseif (isset($error)) { ?>
            <div class="alert alert-danger text-center mb-4">
                <i class="fas fa-exclamation-circle"></i> <?= $error ?>
            </div>
        <?php } ?>

        <form method="POST" class="needs-validation" novalidate>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label">
                        <i class="fas fa-user" style="color: var(--accent-soft);"></i> Tu nombre
                    </label>
                    <input type="text" name="nombre" class="form-control form-control-lg" required>
                    <div class="invalid-feedback">Por favor ingresa tu nombre.</div>
                </div>
                <div class="col-md-6 mb-4">
                    <label class="form-label">
                        <i class="fas fa-envelope" style="color: var(--accent-soft);"></i> Email
                    </label>
                    <input type="email" name="email" class="form-control form-control-lg" required>
                    <div class="invalid-feedback">Por favor ingresa un email válido.</div>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="form-label">
                    <i class="fas fa-phone" style="color: var(--accent-soft);"></i> Teléfono
                </label>
                <input type="tel" name="telefono" class="form-control form-control-lg">
            </div>
            
            <div class="mb-4">
                <label class="form-label">
                    <i class="fas fa-comment" style="color: var(--accent-soft);"></i> Mensaje
                </label>
                <textarea name="mensaje" class="form-control form-control-lg" rows="5" 
                          placeholder="Contanos por qué querés adoptar a <?= htmlspecialchars($perro['nombre']) ?> y cómo podés brindarle un hogar lleno de amor..."></textarea>
            </div>
            
            <div class="text-center">
                <button type="submit" class="btn btn-submit btn-lg px-5">
                    <i class="fas fa-paper-plane"></i> 
                    <span>Enviar solicitud de adopción</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- FOOTER -->
<footer class="text-white text-center py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <p class="mb-2">
                    <i class="fas fa-paw" style="color: var(--accent-soft);"></i>
                    Huellitas de Amor - Conectando corazones desde 2020 
                </p>
                <p class="mb-0 small">
                    © <span id="currentYear"></span> Todos los derechos reservados. 
                    Hecho con <i class="fas fa-heart" style="color: var(--accent-soft);"></i> para nuestros amigos peludos.
                </p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Set current year
document.getElementById('currentYear').textContent = new Date().getFullYear();

// Floating paws animation - less frequent
function createHeart() {
    const paw = document.createElement('div');
    paw.className = 'heart';
    paw.innerHTML = '<i class="fas fa-paw"></i>';
    paw.style.left = Math.random() * 100 + '%';
    paw.style.animationDuration = (Math.random() * 5 + 12) + 's';
    paw.style.fontSize = (Math.random() * 4 + 12) + 'px';
    
    document.getElementById('floatingHearts').appendChild(paw);
    
    setTimeout(() => {
        paw.remove();
    }, 18000);
}

// Create paws less frequently
setInterval(createHeart, 8000);

// Form validation and submission
document.querySelector('form').addEventListener('submit', function(e) {
    if (!this.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    this.classList.add('was-validated');
});

// Add hover effects to info badges
document.querySelectorAll('.info-badge').forEach(badge => {
    badge.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-2px) scale(1.02)';
    });
    
    badge.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
    });
});

// Add subtle focus effect on form inputs
document.querySelectorAll('.form-control').forEach(input => {
    input.addEventListener('focus', function() {
        this.style.transform = 'translateY(-1px)';
    });
    
    input.addEventListener('blur', function() {
        this.style.transform = 'translateY(0)';
    });
});
</script>

</body>
</html>