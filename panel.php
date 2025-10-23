<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "huellitasdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM perros ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin - Huellitas de Amor</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --warm-cream: #FEFCF8;
            --soft-beige: #F8F6F1;
            --pure-white: #FFFFFF;
            --light-gray: #F5F5F5;
            --accent-brown: #8B6F47;
            --accent-soft: #C8A882;
            --accent-light: #E5D4B1;
            --text-dark: #333333;
            --text-muted: #666666;
            --success-color: #6B8F4A;
            --warning-color: #B8860B;
            --danger-color: #A0522D;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--warm-cream) 0%, var(--soft-beige) 100%);
            min-height: 100vh;
            overflow-x: auto;
            color: var(--text-dark);
        }

        /* Subtle animated background particles */
        .bg-particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .particle {
            position: absolute;
            background: rgba(200, 168, 130, 0.08);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }

        .particle:nth-child(1) { width: 20px; height: 20px; left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { width: 15px; height: 15px; left: 20%; animation-delay: 2s; }
        .particle:nth-child(3) { width: 25px; height: 25px; left: 70%; animation-delay: 4s; }
        .particle:nth-child(4) { width: 18px; height: 18px; left: 80%; animation-delay: 1s; }
        .particle:nth-child(5) { width: 22px; height: 22px; left: 90%; animation-delay: 3s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.3; }
            50% { transform: translateY(-80px) rotate(180deg); opacity: 0.6; }
        }

        /* Glass morphism navbar */
        .navbar-glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--accent-light);
            box-shadow: 0 4px 20px rgba(139, 111, 71, 0.1);
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.4rem;
            color: var(--accent-brown) !important;
            text-shadow: 1px 1px 3px rgba(255,255,255,0.8);
            animation: subtlePulse 3s infinite;
        }

        @keyframes subtlePulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }

        /* Enhanced buttons with earthy tones */
        .btn-gradient {
            background: linear-gradient(135deg, var(--accent-brown), var(--accent-soft));
            border: none;
            color: white;
            font-weight: 500;
            border-radius: 12px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }

        .btn-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-gradient:hover::before {
            left: 100%;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(139, 111, 71, 0.3);
            color: white;
        }

        .btn-success-custom {
            background: linear-gradient(135deg, var(--success-color), #7BA05B);
            border: none;
            color: white;
            font-weight: 500;
            border-radius: 12px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }

        .btn-info-custom {
            background: linear-gradient(135deg, var(--warning-color), var(--accent-soft));
            border: none;
            color: white;
            font-weight: 500;
            border-radius: 12px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }

        .btn-danger-custom {
            background: linear-gradient(135deg, var(--danger-color), #CD853F);
            border: none;
            color: white;
            font-weight: 500;
            border-radius: 12px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }

        /* Glass morphism container */
        .glass-container {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            border: 1px solid rgba(200, 168, 130, 0.2);
            box-shadow: 0 8px 25px rgba(139, 111, 71, 0.1);
            padding: 2rem;
            margin: 2rem auto;
            animation: slideInUp 0.6s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Enhanced table */
        .table-glass {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 
                0 15px 35px rgba(139, 111, 71, 0.15),
                0 0 0 1px rgba(200, 168, 130, 0.2);
            border: none;
            position: relative;
        }

        .table-glass::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent-light), var(--accent-soft), var(--accent-brown));
            animation: shimmer 4s linear infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .table-glass thead {
            background: linear-gradient(135deg, var(--accent-brown), var(--accent-soft));
            color: white;
        }

        .table-glass thead th {
            border: none;
            font-weight: 500;
            padding: 1rem;
            position: relative;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }

        .table-glass tbody tr {
            transition: all 0.3s ease;
            animation: fadeInRow 0.5s ease-out backwards;
        }

        .table-glass tbody tr:nth-child(even) {
            background: rgba(248, 246, 241, 0.3);
            animation-delay: 0.1s;
        }

        .table-glass tbody tr:nth-child(odd) {
            animation-delay: 0.2s;
        }

        @keyframes fadeInRow {
            from {
                opacity: 0;
                transform: translateX(-15px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .table-glass tbody tr:hover {
            background: rgba(200, 168, 130, 0.15);
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(139, 111, 71, 0.1);
        }

        .table-glass td {
            border: none;
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid rgba(139, 111, 71, 0.08);
            color: var(--text-dark);
        }

        /* Status badges with earthy colors */
        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85rem;
            display: inline-block;
            animation: bounceIn 0.5s ease-out;
        }

        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.02); }
            70% { transform: scale(0.98); }
            100% { transform: scale(1); opacity: 1; }
        }

        .status-disponible {
            background: linear-gradient(135deg, var(--success-color), #7BA05B);
            color: white;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }

        .status-adoptado {
            background: linear-gradient(135deg, var(--warning-color), var(--accent-soft));
            color: white;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }

        /* Enhanced image effects */
        .image-container {
            position: relative;
            display: inline-block;
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(139, 111, 71, 0.8);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all 0.3s ease;
            color: white;
            font-size: 1.1rem;
        }

        .image-container:hover .image-overlay {
            opacity: 1;
        }

        .image-container:hover .dog-image {
            transform: scale(1.05);
        }

        .dog-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            transition: all 0.3s ease;
            border: 2px solid var(--accent-light);
            cursor: pointer;
            box-shadow: 0 3px 10px rgba(139, 111, 71, 0.2);
        }

        .dog-image:hover {
            border-color: var(--accent-soft);
            box-shadow: 0 5px 15px rgba(200, 168, 130, 0.3);
        }

        /* Action buttons */
        .action-btn {
            margin: 0 0.15rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .action-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.3s, height 0.3s;
        }

        .action-btn:hover::before {
            width: 200px;
            height: 200px;
        }

        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        /* Header animations */
        .header-title {
            background: linear-gradient(135deg, var(--accent-brown), var(--accent-soft));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 600;
            font-size: 2.2rem;
            text-align: center;
            margin-bottom: 2rem;
            animation: slideInDown 0.8s ease-out;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Stats cards */
        .stats-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            border: 1px solid var(--accent-light);
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            animation: zoomIn 0.5s ease-out;
            color: var(--text-dark);
            box-shadow: 0 6px 20px rgba(139, 111, 71, 0.1);
        }

        .stats-card:hover {
            transform: translateY(-3px) scale(1.01);
            box-shadow: 0 10px 25px rgba(139, 111, 71, 0.15);
            background: rgba(255, 255, 255, 0.9);
        }

        @keyframes zoomIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .stats-icon {
            font-size: 2.2rem;
            margin-bottom: 1rem;
            color: var(--accent-soft);
            transition: all 0.3s ease;
        }

        .stats-card:hover .stats-icon {
            color: var(--accent-brown);
            transform: scale(1.1);
        }

        /* Search container */
        .search-container {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            border: 1px solid var(--accent-light);
            padding: 1.5rem;
            margin-bottom: 2rem;
            animation: slideInLeft 0.6s ease-out;
        }

        .search-input {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid var(--light-gray);
            border-radius: 20px;
            padding: 0.7rem 1.2rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(139, 111, 71, 0.08);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent-soft);
            box-shadow: 0 5px 15px rgba(200, 168, 130, 0.2);
            transform: translateY(-1px);
            background: var(--pure-white);
        }

        /* Enhanced 3D buttons */
        .btn-3d {
            background: linear-gradient(135deg, var(--success-color), #7BA05B);
            border: none;
            color: white;
            font-weight: 500;
            padding: 0.8rem 1.8rem;
            border-radius: 12px;
            position: relative;
            transition: all 0.3s ease;
            box-shadow: 
                0 6px 15px rgba(107, 143, 74, 0.3),
                0 3px 6px rgba(107, 143, 74, 0.2);
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }

        .btn-3d:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 
                0 10px 20px rgba(107, 143, 74, 0.4),
                0 5px 10px rgba(107, 143, 74, 0.3);
            color: white;
        }

        .btn-info-3d {
            background: linear-gradient(135deg, var(--warning-color), var(--accent-soft));
            color: white;
            box-shadow: 
                0 6px 15px rgba(184, 134, 11, 0.3),
                0 3px 6px rgba(184, 134, 11, 0.2);
        }

        .btn-info-3d:hover {
            box-shadow: 
                0 10px 20px rgba(184, 134, 11, 0.4),
                0 5px 10px rgba(184, 134, 11, 0.3);
            color: white;
        }

        /* Floating action buttons */
        .floating-actions {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }

        .fab {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            box-shadow: 0 6px 15px rgba(139, 111, 71, 0.3);
            transition: all 0.3s ease;
            text-decoration: none;
            color: white;
            font-size: 1.3rem;
        }

        .fab:hover {
            transform: scale(1.08) rotate(5deg);
            box-shadow: 0 8px 20px rgba(139, 111, 71, 0.4);
        }

        .fab-primary {
            background: linear-gradient(135deg, var(--success-color), #7BA05B);
            color: white;
        }

        .fab-secondary {
            background: linear-gradient(135deg, var(--accent-brown), var(--accent-soft));
            color: white;
        }

        /* ID badges */
        .id-badge {
            background: linear-gradient(135deg, var(--accent-soft), var(--accent-brown));
            color: white;
            font-size: 0.85rem;
            padding: 0.4rem 0.8rem;
            border-radius: 15px;
            font-weight: 500;
        }

        .size-badge {
            background: linear-gradient(135deg, var(--text-muted), var(--text-dark));
            color: white;
            padding: 0.3rem 0.7rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        /* Loading overlay */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            border-radius: 15px;
        }

        .spinner {
            width: 35px;
            height: 35px;
            border: 3px solid var(--light-gray);
            border-top: 3px solid var(--accent-brown);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Tooltip styles */
        .tooltip-custom {
            position: relative;
        }

        .tooltip-custom::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(139, 111, 71, 0.9);
            color: white;
            padding: 0.5rem 0.8rem;
            border-radius: 8px;
            font-size: 0.8rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
        }

        .tooltip-custom:hover::after {
            opacity: 1;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .header-title {
                font-size: 1.8rem;
            }
            
            .floating-actions {
                bottom: 20px;
                right: 20px;
            }
            
            .fab {
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
            }

            .stats-card {
                margin-bottom: 1rem;
            }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--light-gray);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--accent-soft), var(--accent-brown));
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, var(--accent-brown), var(--accent-soft));
        }

        /* Subtle paw animation */
        .paw-print {
            display: inline-block;
            animation: pawBounce 2s ease-in-out infinite;
        }

        @keyframes pawBounce {
            0%, 20%, 60%, 100% { transform: translateY(0); }
            40% { transform: translateY(-6px); }
            80% { transform: translateY(-3px); }
        }
    </style>
</head>
<body>
    <!-- Subtle animated background particles -->
    <div class="bg-particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <!-- Enhanced navbar -->
    <nav class="navbar navbar-expand-lg navbar-glass fixed-top">
        <div class="container">
            <a class="navbar-brand" href="panel.php">
                <i class="fas fa-paw paw-print me-2"></i>Panel Admin Huellitas de Amor
            </a>
            <div class="navbar-nav mx-auto d-none d-lg-flex">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0" style="background: transparent;">
                        <li class="breadcrumb-item"><a href="#" style="color: var(--accent-brown); text-decoration: none;"><i class="fas fa-home me-1"></i>Dashboard</a></li>
                        <li class="breadcrumb-item active" style="color: var(--text-muted);">Gestión de Mascotas</li>
                    </ol>
                </nav>
            </div>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item me-2">
                    <a class="btn btn-gradient" href="index.php">
                        <i class="fas fa-home me-2"></i>Volver al sitio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-danger-custom action-btn" href="logout.php">
                        <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container" style="margin-top: 100px;">
        <!-- Header -->
        <h1 class="header-title">
            <i class="fas fa-dog me-3" style="color: var(--accent-brown);"></i>Gestión de Mascotas
        </h1>

        <!-- Stats cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stats-card">
                    <i class="fas fa-heart stats-icon"></i>
                    <h3 class="fw-bold" style="color: var(--accent-brown);"><?= $result->num_rows ?></h3>
                    <p class="mb-0" style="color: var(--text-muted);">Total de Mascotas</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <i class="fas fa-home stats-icon"></i>
                    <h3 class="fw-bold" style="color: var(--accent-brown);">
                        <?php 
                        $disponibles = $conn->query("SELECT COUNT(*) as count FROM perros WHERE estado = 'disponible'");
                        echo $disponibles->fetch_assoc()['count'];
                        ?>
                    </h3>
                    <p class="mb-0" style="color: var(--text-muted);">Disponibles</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <i class="fas fa-check-circle stats-icon"></i>
                    <h3 class="fw-bold" style="color: var(--accent-brown);">
                        <?php 
                        $adoptados = $conn->query("SELECT COUNT(*) as count FROM perros WHERE estado = 'adoptado'");
                        echo $adoptados->fetch_assoc()['count'];
                        ?>
                    </h3>
                    <p class="mb-0" style="color: var(--text-muted);">Adoptados</p>
                </div>
            </div>
        </div>

        <!-- Search and filter bar -->
        <div class="search-container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text" style="background: rgba(255,255,255,0.9); border: 2px solid var(--light-gray); border-right: none; border-radius: 20px 0 0 20px;">
                            <i class="fas fa-search" style="color: var(--accent-brown);"></i>
                        </span>
                        <input type="text" class="search-input" placeholder="Buscar mascota..." id="searchInput" style="border-radius: 0 20px 20px 0; border-left: none;">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select search-input" id="filterStatus">
                        <option value="">Todos los estados</option>
                        <option value="disponible">Disponibles</option>
                        <option value="adoptado">Adoptados</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn w-100" style="background: linear-gradient(135deg, var(--accent-soft) 0%, var(--accent-brown) 100%); color: white; border-radius: 20px; font-weight: 500;" onclick="clearFilters()">
                        <i class="fas fa-refresh me-2"></i>Limpiar
                    </button>
                </div>
            </div>
        </div>

        <!-- Action buttons -->
        <div class="glass-container">
            <div class="mb-4 text-center">
                <a href="agregar_perro.php" class="btn btn-3d me-3 tooltip-custom" data-tooltip="Agregar nueva mascota">
                    <i class="fas fa-plus-circle me-2"></i>Agregar Nuevo Perro
                </a>
                <a href="solicitudes.php" class="btn btn-info-3d action-btn tooltip-custom" data-tooltip="Ver solicitudes pendientes">
                    <i class="fas fa-envelope me-2"></i>Ver Solicitudes de Adopción
                </a>
            </div>

            <!-- Enhanced table -->
            <div class="table-container">
                <div class="loading-overlay" id="loadingOverlay">
                    <div class="spinner"></div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-glass">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag me-2"></i>ID</th>
                                <th><i class="fas fa-tag me-2"></i>Nombre</th>
                                <th><i class="fas fa-birthday-cake me-2"></i>Edad</th>
                                <th><i class="fas fa-dna me-2"></i>Raza</th>
                                <th><i class="fas fa-ruler me-2"></i>Tamaño</th>
                                <th><i class="fas fa-info-circle me-2"></i>Estado</th>
                                <th><i class="fas fa-camera me-2"></i>Foto</th>
                                <th><i class="fas fa-cogs me-2"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()) { ?>
                            <tr class="table-row" data-status="<?= $row['estado'] ?>">
                                <td class="data-cell">
                                    <span class="id-badge">
                                        #<?= $row['id'] ?>
                                    </span>
                                </td>
                                <td class="fw-bold data-cell" style="color: var(--text-dark);">
                                    <i class="fas fa-dog me-2" style="color: var(--accent-soft);"></i>
                                    <?= htmlspecialchars($row['nombre']) ?>
                                </td>
                                <td class="data-cell">
                                    <span style="color: var(--text-muted); font-weight: 500;">
                                        <i class="fas fa-birthday-cake me-1" style="color: var(--accent-soft);"></i>
                                        <?= $row['edad'] ?>
                                    </span>
                                </td>
                                <td class="data-cell">
                                    <span style="color: var(--text-dark); font-weight: 500;">
                                        <?= htmlspecialchars($row['raza']) ?>
                                    </span>
                                </td>
                                <td class="data-cell">
                                    <span class="size-badge">
                                        <?= $row['tamano'] ?>
                                    </span>
                                </td>
                                <td class="data-cell">
                                    <span class="status-badge status-<?= $row['estado'] ?>">
                                        <?= $row['estado'] == 'disponible' ? '<i class="fas fa-heart me-1"></i> Disponible' : '<i class="fas fa-home me-1"></i> Adoptado' ?>
                                    </span>
                                </td>
                                <td class="data-cell">
                                    <?php if($row['foto']){ ?>
                                        <div class="image-container position-relative">
                                            <img src="<?= $row['foto'] ?>" class="dog-image" alt="<?= htmlspecialchars($row['nombre']) ?>">
                                            <div class="image-overlay">
                                                <i class="fas fa-search-plus"></i>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="dog-image d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, var(--light-gray) 0%, var(--soft-beige) 100%);">
                                            <i class="fas fa-camera" style="color: var(--text-muted);"></i>
                                        </div>
                                    <?php } ?>
                                </td>
                                <td class="data-cell">
                                    <div class="btn-group" role="group">
                                        <a href="editar_perro.php?id=<?= $row['id'] ?>" class="btn action-btn tooltip-custom" 
                                           style="background: linear-gradient(135deg, var(--accent-soft), var(--accent-brown)); color: white; border-radius: 8px 0 0 8px;" 
                                           data-tooltip="Editar información">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="eliminar_perro.php?id=<?= $row['id'] ?>" class="btn action-btn tooltip-custom" 
                                           style="background: linear-gradient(135deg, var(--danger-color), #CD853F); color: white; border-radius: 0 8px 8px 0;" 
                                           data-tooltip="Eliminar mascota" 
                                           onclick="return confirm('¿Estás seguro de que quieres eliminar a <?= htmlspecialchars($row['nombre']) ?>?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating action buttons -->
    <div class="floating-actions d-none d-lg-block">
        <a href="agregar_perro.php" class="fab fab-primary tooltip-custom" data-tooltip="Agregar mascota">
            <i class="fas fa-plus"></i>
        </a>
        <a href="solicitudes.php" class="fab fab-secondary tooltip-custom" data-tooltip="Solicitudes">
            <i class="fas fa-envelope"></i>
        </a>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Search functionality
        function initSearch() {
            const searchInput = document.getElementById('searchInput');
            const filterStatus = document.getElementById('filterStatus');
            const tableRows = document.querySelectorAll('.table-row');

            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase();
                const statusFilter = filterStatus.value;

                tableRows.forEach((row, index) => {
                    const name = row.cells[1].textContent.toLowerCase();
                    const race = row.cells[3].textContent.toLowerCase();
                    const status = row.dataset.status;
                    
                    const matchesSearch = name.includes(searchTerm) || race.includes(searchTerm);
                    const matchesStatus = !statusFilter || status === statusFilter;
                    
                    if (matchesSearch && matchesStatus) {
                        row.style.display = '';
                        row.style.animation = `fadeInRow 0.5s ease-out ${index * 0.05}s both`;
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            searchInput.addEventListener('input', filterTable);
            filterStatus.addEventListener('change', filterTable);
        }

        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('filterStatus').value = '';
            document.querySelectorAll('.table-row').forEach(row => {
                row.style.display = '';
            });
        }

        // Loading animation
        document.addEventListener('DOMContentLoaded', function() {
            const loadingOverlay = document.getElementById('loadingOverlay');
            loadingOverlay.style.display = 'flex';
            
            setTimeout(() => {
                loadingOverlay.style.opacity = '0';
                setTimeout(() => {
                    loadingOverlay.style.display = 'none';
                    initSearch();
                }, 300);
            }, 800);
        });

        // Smooth scroll behavior
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Table row interactions
        document.querySelectorAll('.table tbody tr').forEach((row, index) => {
            row.style.animationDelay = `${index * 0.05}s`;
            
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-1px)';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-glass');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                navbar.style.boxShadow = '0 6px 25px rgba(139, 111, 71, 0.15)';
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.8)';
                navbar.style.boxShadow = '0 4px 20px rgba(139, 111, 71, 0.1)';
            }
        });

        // Dynamic particle generation
        function createParticle() {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.width = particle.style.height = (Math.random() * 8 + 12) + 'px';
            particle.style.animationDuration = (Math.random() * 4 + 6) + 's';
            particle.style.animationDelay = Math.random() * 2 + 's';
            
            document.querySelector('.bg-particles').appendChild(particle);
            
            setTimeout(() => {
                particle.remove();
            }, 12000);
        }

        // Create new particles periodically
        setInterval(createParticle, 6000);

        // Real-time clock
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('es-ES', { 
                hour: '2-digit', 
                minute: '2-digit'
            });
            
            let clockElement = document.getElementById('liveClock');
            if (!clockElement) {
                clockElement = document.createElement('span');
                clockElement.id = 'liveClock';
                clockElement.style.cssText = `
                    color: var(--text-muted);
                    font-size: 0.85rem;
                    font-weight: 400;
                    margin-left: 1rem;
                `;
                document.querySelector('.navbar-brand').appendChild(clockElement);
            }
            clockElement.textContent = ` | ${timeString}`;
        }

        setInterval(updateClock, 60000);
        updateClock();

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'n') {
                e.preventDefault();
                window.location.href = 'agregar_perro.php';
            }
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                window.location.href = 'solicitudes.php';
            }
        });
    </script>
</body>
</html>