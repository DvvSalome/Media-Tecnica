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

// Traer todas las solicitudes junto con el nombre del perro
$sql = "SELECT s.id, s.nombre_usuario, s.email, s.telefono, s.mensaje, s.fecha, p.nombre AS perro_nombre
        FROM solicitudes s
        JOIN perros p ON s.perro_id = p.id
        ORDER BY s.fecha DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes de Adopción - Panel Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #d97706;
            --secondary-color: #ea580c;
            --accent-color: #f59e0b;
            --success-color: #059669;
            --warning-color: #f3f4f6;
            --danger-color: #dc2626;
            --dark-color: #374151;
            --light-color: #fefefe;
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --card-shadow-hover: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fefefe 0%, #fef7ed 40%, #fed7aa 80%, #fbbf24 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(217, 119, 6, 0.12) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(245, 158, 11, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(254, 215, 170, 0.06) 0%, transparent 50%);
            z-index: -1;
            animation: backgroundFloat 25s ease-in-out infinite;
        }

        @keyframes backgroundFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(3deg); }
        }

        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .floating-paw {
            position: absolute;
            color: rgba(245, 158, 11, 0.12);
            font-size: 2.5rem;
            animation: float 8s ease-in-out infinite;
        }

        .floating-paw:nth-child(1) {
            top: 15%;
            left: 8%;
            animation-delay: 0s;
        }

        .floating-paw:nth-child(2) {
            top: 25%;
            right: 12%;
            animation-delay: 3s;
        }

        .floating-paw:nth-child(3) {
            bottom: 35%;
            left: 15%;
            animation-delay: 6s;
        }

        .floating-paw:nth-child(4) {
            bottom: 15%;
            right: 20%;
            animation-delay: 2s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
                opacity: 0.1;
            }
            50% {
                transform: translateY(-30px) rotate(180deg);
                opacity: 0.3;
            }
        }

        .navbar-custom {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 247, 237, 0.9)) !important;
            backdrop-filter: blur(20px);
            border-bottom: 3px solid var(--primary-color);
            padding: 1rem 0;
            box-shadow: 0 4px 12px rgba(217, 119, 6, 0.15);
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color) !important;
            text-shadow: 1px 1px 2px rgba(217, 119, 6, 0.1);
        }

        .nav-btn {
            padding: 0.75rem 1.5rem !important;
            border-radius: 12px !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
            border: none !important;
            margin-left: 0.5rem;
        }

        .nav-btn-light {
            background: white !important;
            color: var(--primary-color) !important;
            border: 2px solid var(--primary-color) !important;
        }

        .nav-btn-light:hover {
            background: var(--primary-color) !important;
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(217, 119, 6, 0.3);
        }

        .nav-btn-danger {
            background: linear-gradient(135deg, #fee2e2, #fecaca) !important;
            color: var(--danger-color) !important;
            border: 2px solid var(--danger-color) !important;
        }

        .nav-btn-danger:hover {
            background: var(--danger-color) !important;
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(220, 38, 38, 0.3);
        }

        .main-container {
            padding: 3rem 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .header-section {
            text-align: center;
            margin-bottom: 3rem;
            padding: 2rem 0;
        }

        .header-section h1 {
            font-size: 3.5rem;
            font-weight: 700;
            color: var(--primary-color);
            text-shadow: 1px 1px 2px rgba(217, 119, 6, 0.1);
            margin-bottom: 1rem;
            animation: slideInDown 1s ease-out;
        }

        .header-section .subtitle {
            font-size: 1.3rem;
            color: var(--dark-color);
            font-weight: 400;
            opacity: 0.8;
            animation: fadeIn 1.2s ease-out;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .progress-bar-custom {
            height: 4px;
            background: linear-gradient(90deg, #fed7aa 0%, var(--primary-color) 50%, #fbbf24 100%);
            border-radius: 2px;
            margin: 2rem auto;
            animation: progressFlow 4s ease-in-out infinite;
            width: 60%;
        }

        @keyframes progressFlow {
            0%, 100% { opacity: 0.7; transform: scaleX(0.8); }
            50% { opacity: 1; transform: scaleX(1.1); }
        }

        .table-container {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(217, 119, 6, 0.15);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            animation: slideInUp 0.8s ease-out;
            overflow: hidden;
        }

        .table-container:hover {
            box-shadow: var(--card-shadow-hover);
            transform: translateY(-5px);
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .table-header {
            background: linear-gradient(135deg, #fff7ed 0%, #fed7aa 100%);
            color: var(--dark-color);
            padding: 1.5rem;
            margin: -2rem -2rem 2rem -2rem;
            text-align: center;
            border-radius: 24px 24px 0 0;
            position: relative;
            overflow: hidden;
            border-bottom: 3px solid var(--primary-color);
        }

        .table-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: rotate(45deg);
            animation: shine 4s infinite;
        }

        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            50% { transform: translateX(100%) translateY(100%) rotate(45deg); }
            100% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
        }

        .table-header h2 {
            font-size: 2rem;
            font-weight: 600;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .custom-table {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            border: none;
        }

        .custom-table thead {
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        }

        .custom-table th {
            border: none;
            padding: 1.25rem 1rem;
            font-weight: 600;
            color: var(--dark-color);
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--primary-color);
            position: relative;
        }

        .custom-table th::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent-color);
            transition: width 0.3s ease;
        }

        .custom-table th:hover::after {
            width: 100%;
        }

        .custom-table td {
            border: none;
            padding: 1.25rem 1rem;
            vertical-align: middle;
            color: var(--dark-color);
            border-bottom: 1px solid #f3f4f6;
            transition: all 0.3s ease;
        }

        .custom-table tbody tr {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .custom-table tbody tr:hover {
            background: linear-gradient(135deg, #fff7ed 0%, #fef3c7 100%);
            transform: scale(1.01);
            box-shadow: 0 4px 12px rgba(217, 119, 6, 0.1);
        }

        .id-badge {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-block;
            min-width: 50px;
            text-align: center;
        }

        .dog-name {
            font-weight: 600;
            color: var(--primary-color);
            background: rgba(217, 119, 6, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 12px;
            display: inline-block;
        }

        .contact-info {
            font-size: 0.95rem;
        }

        .email-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .email-link:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        .phone-number {
            background: rgba(5, 150, 105, 0.1);
            color: var(--success-color);
            padding: 0.25rem 0.75rem;
            border-radius: 8px;
            font-weight: 500;
        }

        .message-preview {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            background: #f9fafb;
            padding: 0.75rem;
            border-radius: 12px;
            border-left: 3px solid var(--accent-color);
            font-style: italic;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .message-preview:hover {
            background: #fff7ed;
            border-left-color: var(--primary-color);
            transform: scale(1.02);
        }

        .date-badge {
            background: rgba(115, 115, 115, 0.1);
            color: var(--dark-color);
            padding: 0.5rem 1rem;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--dark-color);
            opacity: 0.7;
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-15px); }
            60% { transform: translateY(-8px); }
        }

        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(217, 119, 6, 0.15);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            animation: fadeInSlide 0.8s ease-out;
        }

        .stat-card:nth-child(1) { animation-delay: 0.2s; }
        .stat-card:nth-child(2) { animation-delay: 0.4s; }
        .stat-card:nth-child(3) { animation-delay: 0.6s; }

        @keyframes fadeInSlide {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--card-shadow-hover);
        }

        .stat-card i {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1.1rem;
            color: var(--dark-color);
            font-weight: 500;
        }

        .modal-custom .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .modal-custom .modal-header {
            background: linear-gradient(135deg, #fff7ed 0%, #fed7aa 100%);
            border-bottom: 2px solid var(--primary-color);
            border-radius: 20px 20px 0 0;
        }

        .modal-custom .modal-title {
            color: var(--primary-color);
            font-weight: 600;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(55, 65, 81, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            backdrop-filter: blur(10px);
        }

        .loading-spinner {
            width: 80px;
            height: 80px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid var(--accent-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-container {
                padding: 1rem;
            }
            
            .header-section h1 {
                font-size: 2.5rem;
            }
            
            .table-container {
                padding: 1rem;
                margin: 0 -0.5rem;
            }
            
            .custom-table {
                font-size: 0.9rem;
            }
            
            .custom-table th,
            .custom-table td {
                padding: 0.75rem 0.5rem;
            }
            
            .stats-section {
                grid-template-columns: 1fr;
            }
        }

        .fade-in-row {
            opacity: 0;
            animation: fadeInRow 0.6s ease-out forwards;
        }

        .fade-in-row:nth-child(1) { animation-delay: 0.1s; }
        .fade-in-row:nth-child(2) { animation-delay: 0.2s; }
        .fade-in-row:nth-child(3) { animation-delay: 0.3s; }
        .fade-in-row:nth-child(4) { animation-delay: 0.4s; }
        .fade-in-row:nth-child(5) { animation-delay: 0.5s; }

        @keyframes fadeInRow {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .paw-trail {
            position: absolute;
            pointer-events: none;
            z-index: 1000;
            color: rgba(245, 158, 11, 0.6);
            font-size: 1.2rem;
            animation: pawTrail 1s ease-out forwards;
        }

        @keyframes pawTrail {
            0% {
                opacity: 1;
                transform: scale(1);
            }
            100% {
                opacity: 0;
                transform: scale(0.5) translateY(-20px);
            }
        }
    </style>
</head>
<body>
    <div class="floating-elements">
        <i class="fas fa-paw floating-paw"></i>
        <i class="fas fa-paw floating-paw"></i>
        <i class="fas fa-paw floating-paw"></i>
        <i class="fas fa-paw floating-paw"></i>
    </div>

    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="panel.php">
                <i class="fas fa-paw"></i> Panel Admin Huellitas
            </a>
            <div class="navbar-nav ms-auto d-flex flex-row">
                <a class="btn nav-btn nav-btn-light" href="index.php">
                    <i class="fas fa-home"></i> Volver al sitio
                </a>
                <a class="btn nav-btn nav-btn-danger" href="logout.php">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <div class="main-container">
        <div class="header-section">
            <h1><i class="fas fa-envelope-heart"></i> Solicitudes de Adopción</h1>
            <p class="subtitle">Gestiona las peticiones de nuestros futuros adoptantes</p>
            <div class="progress-bar-custom"></div>
        </div>

        <!-- Stats Section -->
        <div class="stats-section">
            <div class="stat-card">
                <i class="fas fa-envelope"></i>
                <div class="stat-number"><?= $result->num_rows ?></div>
                <div class="stat-label">Total Solicitudes</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-clock"></i>
                <div class="stat-number">
                    <?php 
                    $today_count = 0;
                    $result->data_seek(0);
                    while ($row = $result->fetch_assoc()) {
                        if (date('Y-m-d', strtotime($row['fecha'])) == date('Y-m-d')) {
                            $today_count++;
                        }
                    }
                    echo $today_count;
                    $result->data_seek(0);
                    ?>
                </div>
                <div class="stat-label">Hoy</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-heart"></i>
                <div class="stat-number">
                    <?php
                    $unique_dogs = [];
                    $result->data_seek(0);
                    while ($row = $result->fetch_assoc()) {
                        $unique_dogs[$row['perro_nombre']] = true;
                    }
                    echo count($unique_dogs);
                    $result->data_seek(0);
                    ?>
                </div>
                <div class="stat-label">Perros Solicitados</div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="table-container">
            <div class="table-header">
                <h2><i class="fas fa-list"></i> Lista de Solicitudes</h2>
            </div>

            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-dog"></i> Perro</th>
                            <th><i class="fas fa-user"></i> Usuario</th>
                            <th><i class="fas fa-envelope"></i> Email</th>
                            <th><i class="fas fa-phone"></i> Teléfono</th>
                            <th><i class="fas fa-comment"></i> Mensaje</th>
                            <th><i class="fas fa-calendar"></i> Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0) { ?>
                            <?php $counter = 0; ?>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr class="fade-in-row" data-message="<?= htmlspecialchars($row['mensaje']) ?>">
                                    <td>
                                        <span class="id-badge"><?= $row['id'] ?></span>
                                    </td>
                                    <td>
                                        <span class="dog-name">
                                            <i class="fas fa-paw"></i> <?= htmlspecialchars($row['perro_nombre']) ?>
                                        </span>
                                    </td>
                                    <td class="contact-info">
                                        <strong><?= htmlspecialchars($row['nombre_usuario']) ?></strong>
                                    </td>
                                    <td class="contact-info">
                                        <a href="mailto:<?= htmlspecialchars($row['email']) ?>" class="email-link">
                                            <?= htmlspecialchars($row['email']) ?>
                                        </a>
                                    </td>
                                    <td class="contact-info">
                                        <span class="phone-number">
                                            <i class="fas fa-phone"></i> <?= htmlspecialchars($row['telefono']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="message-preview" onclick="showFullMessage('<?= htmlspecialchars($row['mensaje']) ?>', '<?= htmlspecialchars($row['nombre_usuario']) ?>')">
                                            <?= mb_substr(htmlspecialchars($row['mensaje']), 0, 50) ?><?= strlen($row['mensaje']) > 50 ? '...' : '' ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="date-badge">
                                            <i class="fas fa-calendar-alt"></i> 
                                            <?= date('d/m/Y', strtotime($row['fecha'])) ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php $counter++; ?>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="7" class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <h3>No hay solicitudes todavía</h3>
                                    <p>¡Las adopciones comenzarán a llegar pronto!</p>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal para mensaje completo -->
    <div class="modal fade modal-custom" id="messageModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-comment-dots"></i> Mensaje de <span id="userName"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="fullMessage" style="line-height: 1.6; font-size: 1.1rem; color: var(--dark-color);"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Variables globales
        const loadingOverlay = document.getElementById('loadingOverlay');

        // Función para mostrar mensaje completo
        function showFullMessage(message, userName) {
            document.getElementById('userName').textContent = userName;
            document.getElementById('fullMessage').innerHTML = message.replace(/\n/g, '<br>');
            const modal = new bootstrap.Modal(document.getElementById('messageModal'));
            modal.show();
        }

        // Efectos de carga para las filas
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('.fade-in-row');
            
            // Stagger animation for rows
            rows.forEach((row, index) => {
                row.style.