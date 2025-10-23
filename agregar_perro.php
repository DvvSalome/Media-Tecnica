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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $edad = $_POST['edad'];
    $raza = $_POST['raza'];
    $tamano = $_POST['tamano'];
    $descripcion = $_POST['descripcion'];
    $estado = $_POST['estado'];

    // Subida de foto
    $foto = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto = 'img/' . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], $foto);
    }

    $stmt = $conn->prepare("INSERT INTO perros (nombre, edad, raza, tamano, descripcion, estado, foto) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisssss", $nombre, $edad, $raza, $tamano, $descripcion, $estado, $foto);
    $stmt->execute();
    $stmt->close();

    header("Location: panel.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Perro - Panel Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #a08669ff;
            --secondary-color: #8b817cff;
            --accent-color: #c2a36dff;
            --success-color: #059669;
            --warning-color: #f3f4f6;
            --danger-color: #9c5252ff;
            --dark-color: #374151;
            --light-color: #fefefe;
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(161, 117, 117, 0.05);
            --card-shadow-hover: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fefefe 0%, #fef7ed 40%, #b9a083ff 80%, #ccc9c2ff 100%);
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

        .main-container {
            width: 100%;
            min-height: 100vh;
            padding: 2rem;
            display: flex;
            flex-direction: column;
        }

        .header-section {
            text-align: center;
            margin-bottom: 3rem;
            padding: 2rem 0;
        }

        .header-section h1 {
            font-size: 4rem;
            font-weight: 700;
            color: var(--primary-color);
            text-shadow: 1px 1px 2px rgba(217, 119, 6, 0.1);
            margin-bottom: 1rem;
            animation: slideInDown 1s ease-out;
        }

        .header-section .subtitle {
            font-size: 1.4rem;
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

        .form-wrapper {
            flex: 1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        .form-column {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(172, 145, 115, 0.15);
            border-radius: 24px;
            padding: 3rem;
            box-shadow: var(--card-shadow);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: slideInUp 0.8s ease-out;
            height: fit-content;
        }

        .form-column:hover {
            box-shadow: var(--card-shadow-hover);
            transform: translateY(-8px);
        }

        .form-column.left {
            animation-delay: 0.2s;
        }

        .form-column.right {
            animation-delay: 0.4s;
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

        .column-header {
            background: linear-gradient(135deg, #fff7ed 0%, #fed7aa 100%);
            color: var(--dark-color);
            padding: 1.5rem;
            margin: -3rem -3rem 2rem -3rem;
            text-align: center;
            border-radius: 24px 24px 0 0;
            position: relative;
            overflow: hidden;
            border-bottom: 3px solid var(--primary-color);
        }

        .column-header::before {
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

        .column-header h2 {
            font-size: 1.8rem;
            font-weight: 600;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .form-section {
            margin-bottom: 2.5rem;
            opacity: 0;
            animation: fadeInSlide 0.8s ease-out forwards;
        }

        .form-section:nth-child(1) { animation-delay: 0.6s; }
        .form-section:nth-child(2) { animation-delay: 0.8s; }
        .form-section:nth-child(3) { animation-delay: 1s; }
        .form-section:nth-child(4) { animation-delay: 1.2s; }
        .form-section:nth-child(5) { animation-delay: 1.4s; }

        @keyframes fadeInSlide {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.1rem;
        }

        .form-label i {
            color: var(--primary-color);
            font-size: 1.3rem;
            width: 24px;
            text-align: center;
        }

        .form-control, .form-select {
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            font-size: 1.1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            width: 100%;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15), 0 4px 12px rgba(245, 158, 11, 0.2);
            transform: translateY(-2px);
            background: white;
        }

        .form-control:hover, .form-select:hover {
            border-color: var(--accent-color);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(217, 119, 6, 0.12);
        }

        .textarea-custom {
            resize: vertical;
            min-height: 180px;
            line-height: 1.6;
        }

        .file-upload-section {
            grid-column: 1 / -1;
            margin-top: 2rem;
        }

        .file-upload-wrapper {
            position: relative;
            overflow: hidden;
            border: 2px dashed #fbbf24;
            border-radius: 20px;
            padding: 4rem 2rem;
            text-align: center;
            transition: all 0.4s ease;
            background: linear-gradient(45deg, #fefefe, #fff7ed);
            cursor: pointer;
            min-height: 250px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .file-upload-wrapper:hover {
            border-color: var(--primary-color);
            background: linear-gradient(45deg, #fff7ed, #fed7aa);
            transform: scale(1.01);
            box-shadow: 0 8px 16px rgba(217, 119, 6, 0.15);
        }

        .file-upload-wrapper.drag-over {
            border-color: var(--success-color);
            background: #f0fdf4;
            transform: scale(1.02);
            box-shadow: 0 12px 20px rgba(5, 150, 105, 0.15);
        }

        .file-upload-content i {
            font-size: 4rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            animation: bounce 2s infinite;
        }

        .file-upload-content h5 {
            font-size: 1.4rem;
            color: #92400e;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .file-upload-content p {
            color: #a16207;
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-15px); }
            60% { transform: translateY(-8px); }
        }

        .file-upload-input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-name {
            margin-top: 1.5rem;
            font-weight: 600;
            color: var(--success-color);
            font-size: 1.1rem;
            display: none;
            padding: 1rem;
            background: rgba(252, 211, 77, 0.2);
            border-radius: 12px;
            border: 1px solid var(--success-color);
        }

        .actions-section {
            grid-column: 1 / -1;
            margin-top: 3rem;
            text-align: center;
            padding: 2rem;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 247, 237, 0.8));
            border-radius: 20px;
            border: 1px solid rgba(217, 119, 6, 0.2);
        }

        .btn-group-custom {
            display: flex;
            gap: 2rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-custom {
            padding: 1.25rem 3rem;
            border-radius: 16px;
            font-weight: 600;
            font-size: 1.1rem;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            min-width: 180px;
            justify-content: center;
        }

        .btn-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.6s;
        }

        .btn-custom:hover::before {
            left: 100%;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
            color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            font-weight: 700;
            border: 2px solid var(--primary-color);
        }

        .btn-primary-custom:hover {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(217, 119, 6, 0.25);
            color: white;
        }

        .btn-secondary-custom {
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            color: var(--dark-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            font-weight: 600;
            border: 2px solid #d1d5db;
        }

        .btn-secondary-custom:hover {
            background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            color: var(--dark-color);
            text-decoration: none;
            border-color: var(--primary-color);
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

        .floating-paw:nth-child(5) {
            top: 60%;
            left: 50%;
            animation-delay: 4s;
        }

        .floating-paw:nth-child(6) {
            top: 40%;
            right: 40%;
            animation-delay: 1s;
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

        .input-group-custom {
            position: relative;
        }

        .input-icon {
            position: absolute;
            right: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            font-size: 1.3rem;
            z-index: 10;
            transition: all 0.3s ease;
        }

        .form-control:focus + .input-icon {
            color: var(--success-color);
            transform: translateY(-50%) scale(1.2);
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

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(146, 64, 14, 0.8);
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

        .required-indicator {
            color: var(--danger-color);
            margin-left: 0.5rem;
            font-size: 1.2rem;
        }

        .form-control::placeholder {
            color: #9ca3af;
            font-style: italic;
            opacity: 0.7;
        }

        .size-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            font-size: 1rem;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .form-wrapper {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .file-upload-section,
            .actions-section {
                grid-column: 1;
            }
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 1rem;
            }
            
            .header-section h1 {
                font-size: 2.5rem;
            }
            
            .header-section .subtitle {
                font-size: 1.1rem;
            }
            
            .form-column {
                padding: 2rem 1.5rem;
            }
            
            .btn-group-custom {
                flex-direction: column;
                gap: 1rem;
            }
            
            .btn-custom {
                width: 100%;
            }
        }

        .input-animation {
            position: relative;
            overflow: hidden;
        }

        .input-animation::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent-color), var(--primary-color));
            transition: width 0.3s ease;
        }

        .input-animation:focus-within::after {
            width: 100%;
        }

        .success-indicator {
            position: absolute;
            right: 4rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--success-color);
            font-size: 1.2rem;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .form-control:valid + .input-icon + .success-indicator {
            opacity: 1;
            transform: translateY(-50%) scale(1.1);
        }

        .paw-trail {
            position: absolute;
            pointer-events: none;
            z-index: 1000;
            color: rgba(245, 158, 11, 0.6);
            font-size: 1.5rem;
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
        <i class="fas fa-paw floating-paw"></i>
        <i class="fas fa-paw floating-paw"></i>
    </div>

    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <div class="main-container">
        <div class="header-section">
            <h1><i class="fas fa-dog"></i> Huellitas de Amor</h1>
            <p class="subtitle">Registra un nuevo compañero en busca de hogar</p>
            <div class="progress-bar-custom"></div>
        </div>

        <form method="POST" enctype="multipart/form-data" id="dogForm">
            <div class="form-wrapper">
                <!-- Columna Izquierda: Información Básica -->
                <div class="form-column left">
                    <div class="column-header">
                        <h2><i class="fas fa-id-card"></i> Información Básica</h2>
                    </div>

                    <div class="form-section">
                        <label class="form-label">
                            <i class="fas fa-tag"></i>
                            Nombre del Perro
                            <span class="required-indicator">*</span>
                        </label>
                        <div class="input-group-custom input-animation">
                            <input 
                                type="text" 
                                name="nombre" 
                                class="form-control" 
                                required 
                                placeholder="Ej: Max, Luna, Rocky, Bella..."
                                autocomplete="off"
                            >
                            <i class="fas fa-heart input-icon"></i>
                            <i class="fas fa-check success-indicator"></i>
                        </div>
                    </div>

                    <div class="form-section">
                        <label class="form-label">
                            <i class="fas fa-birthday-cake"></i>
                            Edad en Años
                            <span class="required-indicator">*</span>
                        </label>
                        <div class="input-group-custom input-animation">
                            <input 
                                type="number" 
                                name="edad" 
                                class="form-control" 
                                required 
                                min="0" 
                                max="20"
                                placeholder="¿Cuántos años tiene?"
                            >
                            <i class="fas fa-calendar-alt input-icon"></i>
                            <i class="fas fa-check success-indicator"></i>
                        </div>
                    </div>

                    <div class="form-section">
                        <label class="form-label">
                            <i class="fas fa-dna"></i>
                            Raza
                            <span class="required-indicator">*</span>
                        </label>
                        <div class="input-group-custom input-animation">
                            <input 
                                type="text" 
                                name="raza" 
                                class="form-control" 
                                required 
                                placeholder="Ej: Labrador, Mestizo, Golden Retriever..."
                            >
                            <i class="fas fa-paw input-icon"></i>
                            <i class="fas fa-check success-indicator"></i>
                        </div>
                    </div>

                    <div class="form-section">
                        <label class="form-label">
                            <i class="fas fa-ruler-combined"></i>
                            Tamaño
                            <span class="required-indicator">*</span>
                        </label>
                        <select name="tamano" class="form-select" required>
                            <option value="">Selecciona el tamaño del perro</option>
                            <option value="Pequeño" class="size-option">🐕 Pequeño (&lt; 10kg)</option>
                            <option value="Mediano" class="size-option">🐕‍🦺 Mediano (10-25kg)</option>
                            <option value="Grande" class="size-option">🐕‍🔍 Grande (&gt; 25kg)</option>
                        </select>
                    </div>
                </div>

                <!-- Columna Derecha: Detalles y Estado -->
                <div class="form-column right">
                    <div class="column-header">
                        <h2><i class="fas fa-clipboard-list"></i> Detalles y Estado</h2>
                    </div>

                    <div class="form-section">
                        <label class="form-label">
                            <i class="fas fa-heart"></i>
                            Descripción y Personalidad
                        </label>
                        <textarea 
                            name="descripcion" 
                            class="form-control textarea-custom" 
                            rows="6"
                            placeholder="Describe su personalidad, características especiales, si se lleva bien con niños o otros animales, necesidades médicas, hábitos, etc. ¡Ayuda a las familias a conocerlo mejor!"
                        ></textarea>
                    </div>

                    <div class="form-section">
                        <label class="form-label">
                            <i class="fas fa-info-circle"></i>
                            Estado de Adopción
                            <span class="required-indicator">*</span>
                        </label>
                        <select name="estado" class="form-select" required>
                            <option value="">Selecciona el estado actual</option>
                            <option value="disponible">✅ Disponible para Adopción</option>
                            <option value="adoptado">❤️ Ya Adoptado</option>
                        </select>
                    </div>

                    <div class="form-section file-upload-section">
                        <label class="form-label">
                            <i class="fas fa-camera"></i>
                            Fotografía del Perro
                        </label>
                        <div class="file-upload-wrapper" id="fileUploadWrapper">
                            <input 
                                type="file" 
                                name="foto" 
                                class="file-upload-input" 
                                accept="image/*"
                                id="fotoInput"
                            >
                            <div class="file-upload-content">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <h5>Sube una foto adorable</h5>
                                <p>Arrastra aquí o haz clic para seleccionar</p>
                                <small style="color: #a16207;">Formatos: JPG, PNG, GIF (máx. 5MB)</small>
                            </div>
                            <div class="file-name" id="fileName"></div>
                        </div>
                    </div>
                </div>

                <!-- Sección de Acciones -->
                <div class="actions-section">
                    <div class="btn-group-custom">
                        <button type="submit" class="btn-custom btn-primary-custom" id="submitBtn">
                            <i class="fas fa-save"></i>
                            Guardar Nuevo Perro
                        </button>
                        <a href="panel.php" class="btn-custom btn-secondary-custom">
                            <i class="fas fa-arrow-left"></i>
                            Volver al Panel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Variables globales
        const fileUploadWrapper = document.getElementById('fileUploadWrapper');
        const fotoInput = document.getElementById('fotoInput');
        const fileName = document.getElementById('fileName');
        const submitBtn = document.getElementById('submitBtn');
        const loadingOverlay = document.getElementById('loadingOverlay');
        const dogForm = document.getElementById('dogForm');

        // Drag and drop effects
        fileUploadWrapper.addEventListener('dragover', (e) => {
            e.preventDefault();
            fileUploadWrapper.classList.add('drag-over');
        });

        fileUploadWrapper.addEventListener('dragleave', () => {
            fileUploadWrapper.classList.remove('drag-over');
        });

        fileUploadWrapper.addEventListener('drop', (e) => {
            e.preventDefault();
            fileUploadWrapper.classList.remove('drag-over');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fotoInput.files = files;
                updateFileName(files[0].name);
            }
        });

        // File input change
        fotoInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                updateFileName(e.target.files[0].name);
            } else {
                fileName.style.display = 'none';
            }
        });

        function updateFileName(name) {
            fileName.innerHTML = `<i class="fas fa-image"></i> ${name}`;
            fileName.style.display = 'block';
            fileName.style.animation = 'slideInDown 0.5s ease-out';
        }

        // Form submission with loading
        dogForm.addEventListener('submit', (e) => {
            loadingOverlay.style.display = 'flex';
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
            submitBtn.disabled = true;
        });

        // Input animations and validations
        const inputs = document.querySelectorAll('.form-control, .form-select');
        
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', () => {
                input.parentElement.parentElement.style.transform = 'scale(1)';
                
                if (input.value.trim() !== '') {
                    input.style.borderColor = 'var(--success-color)';
                    input.style.boxShadow = '0 0 0 3px rgba(252, 211, 77, 0.2)';
                } else if (input.hasAttribute('required')) {
                    input.style.borderColor = '#fed7aa';
                    input.style.boxShadow = 'none';
                }
            });
            
            input.addEventListener('input', () => {
                if (input.value.trim() !== '') {
                    input.style.borderColor = 'var(--success-color)';
                } else {
                    input.style.borderColor = '#fed7aa';
                }
            });
        });

        // Auto-resize textarea
        const textarea = document.querySelector('textarea');
        if (textarea) {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        }

        // Mouse trail effect
        document.addEventListener('mousemove', (e) => {
            if (Math.random() > 0.95) {
                createPawTrail(e.clientX, e.clientY);
            }
        });

        function createPawTrail(x, y) {
            const paw = document.createElement('div');
            paw.className = 'paw-trail';
            paw.innerHTML = '<i class="fas fa-paw"></i>';
            paw.style.left = x + 'px';
            paw.style.top = y + 'px';
            document.body.appendChild(paw);
            
            setTimeout(() => {
                paw.remove();
            }, 1000);
        }

        // Form validation enhancement
        const form = document.getElementById('dogForm');
        form.addEventListener('submit', (e) => {
            const requiredInputs = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredInputs.forEach(input => {
                if (!input.value.trim()) {
                    input.style.borderColor = 'var(--danger-color)';
                    input.style.animation = 'shake 0.5s ease-in-out';
                    isValid = false;
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                loadingOverlay.style.display = 'none';
                submitBtn.innerHTML = '<i class="fas fa-save"></i> Guardar Nuevo Perro';
                submitBtn.disabled = false;
            }
        });

        // Add shake animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-10px); }
                75% { transform: translateX(10px); }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>