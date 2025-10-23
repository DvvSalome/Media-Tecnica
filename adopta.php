<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "huellitasdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// --- Procesar login ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM admin WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_pass);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($pass, $hashed_pass)) {
        $_SESSION['admin_id'] = $id;
        $_SESSION['username'] = $user;

        // Redirigir directamente al panel
        header("Location: panel.php");
        exit;
    } else {
        $login_error = "❌ Usuario o contraseña incorrectos";
    }
    $stmt->close();
}

// Traer solo los perros disponibles
$result = $conn->query("SELECT * FROM perros WHERE estado = 'disponible' ORDER BY fecha_publicacion DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>UniteDogs 🐾</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/footer.css">
</head>
<body class="bg-gray-100">

<!-- NAVBAR -->
    <nav class="bg-white shadow-lg border-b fixed w-full top-0 z-50 backdrop-blur-md">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between py-4">
                    <a href="index.html" class="text-xl font-bold text-blue-600 hover:text-blue-700 transition">
                        <img alt="UniteDogs logo" class="h-20" src="imgfront/logoU.png"/>
                    </a>
                    
                    <!-- Mobile menu button -->
                    <button class="lg:hidden block text-gray-700 focus:outline-none" onclick="toggleMobileMenu()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <!-- Desktop menu -->
                    <div class="hidden lg:flex items-center space-x-6">
                        <!-- Navigation Links -->
                        <a href="index.php" class="text-gray-700 hover:text-blue-600 font-medium transition">Inicio</a>
                        <a href="quienes.php" class="text-gray-700 hover:text-blue-600 font-medium transition">¿Quiénes somos?</a>
                        <a href="adopta.php" class="text-gray-700 hover:text-blue-600 font-medium transition">Adopta ya</a>
        
                        <div class="flex items-center space-x-3 border-l pl-6">
                            <?php if (isset($_SESSION['admin_id'])) { ?>
                                <span class="text-gray-600 text-sm">
                                    Bienvenido, <?= htmlspecialchars($_SESSION['username']) ?>
                                </span>
                                <a class="bg-blue-600 text-white px-4 py-2 rounded font-medium hover:bg-blue-700 transition" href="panel.php">Ir al Panel</a>
                                <a class="bg-red-600 text-white px-4 py-2 rounded font-medium hover:bg-red-700 transition" href="logout.php">Cerrar Sesión</a>
                            <?php } else { ?>
                                <button class="bg-blue-600 text-white px-4 py-2 rounded font-medium hover:bg-blue-700 transition" onclick="openLoginModal()">
                                    Ingresar al Sistema
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- Mobile menu -->
                <div id="mobileMenu" class="lg:hidden hidden pb-4 border-t border-gray-200 mt-4">
                    <!-- Mobile Navigation Links -->
                    <div class="space-y-2 mb-4">
                        <a href="index.php class="block text-gray-700 hover:text-blue-600 font-medium py-2 px-2 rounded hover:bg-gray-50 transition">Inicio</a>
                        <a href="quienes.php" class="block text-gray-700 hover:text-blue-600 font-medium py-2 px-2 rounded hover:bg-gray-50 transition">¿Quiénes somos?</a>
                        <a href="adopta.php" class="block text-gray-700 hover:text-blue-600 font-medium py-2 px-2 rounded hover:bg-gray-50 transition">Adopta ya</a>
                        <a href="#" class="block text-gray-700 hover:text-blue-600 font-medium py-2 px-2 rounded hover:bg-gray-50 transition">De regreso a casa</a>
                     
                    </div>
                    
                    <!-- Mobile Admin/Login Section -->
                    <div class="pt-4 border-t border-gray-200">
                        <?php if (isset($_SESSION['admin_id'])) { ?>
                            <div class="text-gray-600 text-sm mb-3 px-2">
                                Bienvenido, <?= htmlspecialchars($_SESSION['username']) ?>
                            </div>
                            <a class="block bg-blue-600 text-white px-4 py-2 rounded font-medium hover:bg-blue-700 transition mb-2" href="panel.php">Ir al Panel</a>
                            <a class="block bg-red-600 text-white px-4 py-2 rounded font-medium hover:bg-red-700 transition" href="logout.php">Cerrar Sesión</a>
                        <?php } else { ?>
                            <button class="block bg-blue-600 text-white px-4 py-2 rounded font-medium hover:bg-blue-700 transition w-full" onclick="openLoginModal()">
                                Ingresar al Sistema
                            </button>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </nav>

        <!-- LOGIN MODAL -->
        <div id="loginModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4">
                <form method="POST">
                    <div class="border-b px-6 py-4">
                        <h3 class="text-lg font-semibold">Ingresar al Sistema</h3>
                        <button type="button" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600" onclick="closeLoginModal()">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="px-6 py-4">
                        <?php if (isset($login_error)) { ?>
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                <?= $login_error ?>
                            </div>
                        <?php } ?>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Usuario</label>
                            <input type="text" name="username" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Contraseña</label>
                            <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500" required>
                        </div>
                        <input type="hidden" name="login" value="1">
                    </div>
                    <div class="px-6 py-4 border-t">
                        <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded font-medium hover:bg-blue-700 transition">
                            Ingresar
                        </button>
                    </div>
                </form>
            </div>
        </div>

<!-- HERO -->
<header class="relative w-full h-screen flex flex-col justify-center items-center overflow-hidden px-6 sm:px-12 md:px-24 select-none">
   <!-- Large central image -->
   <img alt="Perro y gato felices abrazados en un campo soleado con flores amarillas y cielo azul claro" class="absolute inset-0 w-full h-full object-cover object-center opacity-30 scale-105 animate-pulse" height="900" loading="lazy" src="https://storage.googleapis.com/a1aa/image/8cae5773-644a-4a04-8858-6459ab0785fa.jpg" width="1200"/>
   <!-- Overlay -->
   <div class="absolute inset-0 bg-gradient-to-b from-yellow-100/70 via-yellow-200/60 to-yellow-50/80 backdrop-blur-sm z-10 rounded-3xl shadow-lg"></div>
   <!-- Content -->
   <div class="relative z-20 max-w-4xl text-center">
    <h1 class="font-pacifico text-yellow-800 text-5xl sm:text-6xl md:text-7xl drop-shadow-lg leading-tight tracking-wide">
     Adopta un amigo,<br/>
     cambia una vida para siempre
    </h1>
    <p class="mt-6 text-yellow-700 text-lg sm:text-xl md:text-2xl font-semibold drop-shadow-md max-w-3xl mx-auto">
     Descubre el amor incondicional y la alegría que solo un nuevo compañero puede traer a tu hogar.
    </p>

   </div>
   <!-- Animated paw prints floating up in multiple colors -->
   <div aria-hidden="true" class="absolute bottom-10 left-1/2 -translate-x-1/2 flex space-x-6 z-10">
    <svg aria-hidden="true" class="w-10 h-10 text-yellow-200 animate-float-up delay-0" fill="currentColor" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
     <path d="M7.5 6a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0zM14 4a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM19 7a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0zM9 14a3 3 0 0 1 6 0c0 1.5-3 6-3 6s-3-4.5-3-6z"/>
    </svg>
    <svg aria-hidden="true" class="w-8 h-8 text-yellow-100 animate-float-up delay-2000" fill="currentColor" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
     <path d="M7.5 6a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0zM14 4a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM19 7a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0zM9 14a3 3 0 0 1 6 0c0 1.5-3 6-3 6s-3-4.5-3-6z"/>
    </svg>
    <svg aria-hidden="true" class="w-12 h-12 text-yellow-300 animate-float-up delay-4000" fill="currentColor" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
     <path d="M7.5 6a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0zM14 4a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM19 7a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0zM9 14a3 3 0 0 1 6 0c0 1.5-3 6-3 6s-3-4.5-3-6z"/>
    </svg>
    <svg aria-hidden="true" class="w-9 h-9 text-yellow-200 animate-float-up delay-1000" fill="currentColor" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
     <path d="M7.5 6a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0zM14 4a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM19 7a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0zM9 14a3 3 0 0 1 6 0c0 1.5-3 6-3 6s-3-4.5-3-6z"/>
    </svg>
    <svg aria-hidden="true" class="w-7 h-7 text-yellow-50 animate-float-up delay-3000" fill="currentColor" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
     <path d="M7.5 6a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0zM14 4a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM19 7a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0zM9 14a3 3 0 0 1 6 0c0 1.5-3 6-3 6s-3-4.5-3-6z"/>
    </svg>
   </div>
   <!-- Floating hearts in multiple yellow shades -->
   <div aria-hidden="true" class="absolute top-20 left-10 w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 text-yellow-200 animate-heartbeat-slow z-20">
    <svg fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
     <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
    </svg>
   </div>
   <div aria-hidden="true" class="absolute top-32 right-16 w-5 h-5 sm:w-7 sm:h-7 md:w-9 md:h-9 text-yellow-100 animate-heartbeat delay-1000 z-20">
    <svg fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
     <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
    </svg>
   </div>
   <div aria-hidden="true" class="absolute bottom-32 left-20 w-7 h-7 sm:w-9 sm:h-9 md:w-12 md:h-12 text-yellow-300 animate-heartbeat-slower delay-2000 z-20">
    <svg fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
     <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
    </svg>
   </div>
   <!-- Additional colorful paw prints scattered -->
   <svg aria-hidden="true" class="absolute top-16 left-1/4 w-8 h-8 text-yellow-200 animate-float delay-1000 z-15" fill="currentColor" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
    <path d="M7.5 6a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0zM14 4a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM19 7a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0zM9 14a3 3 0 0 1 6 0c0 1.5-3 6-3 6s-3-4.5-3-6z"/>
   </svg>
   <svg aria-hidden="true" class="absolute bottom-24 right-1/3 w-10 h-10 text-yellow-100 animate-float-slow delay-3000 z-15" fill="currentColor" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
    <path d="M7.5 6a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0zM14 4a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM19 7a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0zM9 14a3 3 0 0 1 6 0c0 1.5-3 6-3 6s-3-4.5-3-6z"/>
   </svg>
   <svg aria-hidden="true" class="absolute top-1/3 right-1/4 w-6 h-6 text-yellow-50 animate-float delay-2000 z-15" fill="currentColor" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
    <path d="M7.5 6a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0zM14 4a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM19 7a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0zM9 14a3 3 0 0 1 6 0c0 1.5-3 6-3 6s-3-4.5-3-6z"/>
   </svg>
   <!-- Sparkles -->
   <div aria-hidden="true" class="absolute top-10 right-20 w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 text-yellow-200 animate-sparkle z-30">
    <svg fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
     <path d="M12 2l1.176 3.618L17 7l-3.5 2.5L14.352 13 12 10.5 9.648 13l1.852-3.5L8 7l3.824-.382L12 2z"/>
    </svg>
   </div>
   <div aria-hidden="true" class="absolute bottom-20 left-24 w-4 h-4 sm:w-6 sm:h-6 md:w-8 md:h-8 text-yellow-100 animate-sparkle delay-1500 z-30">
    <svg fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
     <path d="M12 2l1.176 3.618L17 7l-3.5 2.5L14.352 13 12 10.5 9.648 13l1.852-3.5L8 7l3.824-.382L12 2z"/>
    </svg>
   </div>
   <div aria-hidden="true" class="absolute top-1/2 right-1/3 w-5 h-5 sm:w-7 sm:h-7 md:w-9 md:h-9 text-yellow-50 animate-sparkle delay-3000 z-30">
    <svg fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
     <path d="M12 2l1.176 3.618L17 7l-3.5 2.5L14.352 13 12 10.5 9.648 13l1.852-3.5L8 7l3.824-.382L12 2z"/>
    </svg>
   </div>
   <!-- Scroll down indicator -->
   <div aria-label="Bajar a la sección de adopción" class="absolute bottom-8 left-1/2 -translate-x-1/2 z-30 text-yellow-700 text-5xl animate-bounce cursor-pointer" onclick="document.getElementById('adopt-section').scrollIntoView({ behavior: 'smooth' })" onkeypress="if(event.key==='Enter'){document.getElementById('adopt-section').scrollIntoView({ behavior: 'smooth' })}" role="button" tabindex="0">
    <i class="fas fa-chevron-down"></i>
   </div>
  </header>
  <section class="bg-white bg-opacity-90 py-16 px-6 sm:px-12 md:px-24 max-w-5xl mx-auto rounded-3xl shadow-lg mt-[-4rem] relative z-20" id="about">
   <h2 class="text-3xl font-bold text-yellow-700 mb-6 text-center">
    ¿Por qué adoptar?
   </h2>
   <p class="text-yellow-600 text-lg sm:text-xl leading-relaxed max-w-4xl mx-auto text-center">
    Adoptar no solo salva la vida de un animal, sino que también llena tu
    hogar de amor y alegría. Cada mascota adoptada es una historia de
    esperanza y un nuevo comienzo. Al adoptar, ayudas a reducir el abandono
    y das una segunda oportunidad a un ser que merece ser amado.
   </p>
  </section>
 

<!-- LISTADO DE PERROS -->
<section class="container mx-auto px-4 py-20" id="perros">
    <h2 class="text-3xl font-bold text-center mb-12">🐶 Perritos en adopción</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden h-full flex flex-col">
                <?php $foto = $row['foto'] ?: 'img/sin-foto.jpg'; ?>
                <img src="<?= $foto ?>" class="w-full h-64 object-cover">
                <div class="p-6 flex-grow">
                    <h5 class="text-xl font-bold mb-3"><?= htmlspecialchars($row['nombre']) ?></h5>
                    <p class="mb-2"><strong>Edad:</strong> <?= $row['edad'] ?> años</p>
                    <p class="mb-2"><strong>Raza:</strong> <?= htmlspecialchars($row['raza']) ?></p>
                    <p class="mb-2"><strong>Tamaño:</strong> <?= $row['tamano'] ?></p>
                    <p class="text-gray-600 mb-4"><?= nl2br(htmlspecialchars($row['descripcion'])) ?></p>
                    <a href="ver_perro.php?id=<?= $row['id'] ?>" class="bg-blue-600 text-white px-4 py-2 rounded font-medium hover:bg-blue-700 transition inline-block">
                        Conocer más
                    </a>
                </div>
            </div>
        <?php } ?>
        <?php if ($result->num_rows == 0) { ?>
            <div class="col-span-full text-center text-gray-600">
                <p>Por el momento no hay perritos disponibles 🐾</p>
            </div>
        <?php } ?>
    </div>
</section>

<!-- CONTACTO -->
<section class="bg-gradient-to-br from-green-50 to-blue-50 py-20 relative overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 left-10 w-20 h-20 bg-green-200 rounded-full"></div>
        <div class="absolute bottom-10 right-10 w-16 h-16 bg-blue-200 rounded-full"></div>
        <div class="absolute top-1/2 left-1/4 w-12 h-12 bg-yellow-200 rounded-full"></div>
    </div>

    <div class="container mx-auto px-4 text-center relative z-10">
        <div class="max-w-2xl mx-auto">
            <h2 class="text-4xl font-bold mb-6 text-gray-800 flex items-center justify-center gap-3">
                <span class="text-3xl">📩</span>
                Contacto
            </h2>
            <p class="text-xl text-gray-600 mb-10 leading-relaxed">
                ¿Querés adoptar o colaborar? Estamos aquí para ayudarte. ¡Escribinos y hagamos la diferencia juntos!
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                <a href="https://wa.me/3054390551" target="_blank"
                   class="group bg-gradient-to-r from-green-500 to-green-600 text-white px-10 py-4 rounded-xl text-lg font-semibold hover:from-green-600 hover:to-green-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl inline-flex items-center gap-3">
                    <span class="text-2xl group-hover:animate-bounce">💬</span>
                    WhatsApp
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </a>

                <div class="text-center sm:text-left">
                    <p class="text-sm text-gray-500 mb-1">También puedes contactarnos por:</p>
                    <div class="flex items-center justify-center sm:justify-start gap-4 text-gray-600">
                        <span class="flex items-center gap-1">
                            <span>📧</span>
                            <span class="text-sm">info@unitedogs.com</span>
                        </span>
                        <span class="flex items-center gap-1">
                            <span>📞</span>
                            <span class="text-sm">+54 0303 456</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
 <!-- Footer -->
    <footer>
        <div class="footer-content container">
            <div class="footer-section">
                <h3>Proyecto Huellitas</h3>
                <p>Somos una organización dedicada a rescatar, rehabilitar y encontrar hogares amorosos para perritos en situación de abandono.</p>
                <div class="social-icons">
                    <a href="#" class="social-icon">📘</a>
                    <a href="#" class="social-icon">📷</a>
                    <a href="#" class="social-icon">🐦</a>
                </div>
            </div>
            
            <div class="footer-section">
                <h3>Enlaces Rápidos</h3>
                <ul>
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="quienes.php">¿Quiénes somos?</a></li>
                    <li><a href="adopta.php">Adopta ya</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Contacto</h3>
                <ul>
                    <li>📍 Cra. 82 #47a-65, La América, Medellín, La América, Medellín, Antioquia</li>
                    <li>📞 3054390551</li>
                    <li>✉️ info@proyectohuellitas.com</li>
                    <li>🕐 Lun-Vie: 9:00 - 18:00</li>
                    <li>🕐 Sáb: 10:00 - 14:00</li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; 2025 Proyecto Huellitas. Todos los derechos reservados.</p>
        </div>
    </footer>


<script>
    function toggleMobileMenu() {
        const mobileMenu = document.getElementById('mobileMenu');
        mobileMenu.classList.toggle('hidden');
    }

    function openLoginModal() {
        document.getElementById('loginModal').classList.remove('hidden');
    }

    function closeLoginModal() {
        document.getElementById('loginModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('loginModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeLoginModal();
        }
    });

    <?php if (isset($login_error)) { ?>
        openLoginModal();
    <?php } ?>
</script>
 <style>
   @keyframes float {
    0%, 100% {
      transform: translateY(0);
    }
    50% {
      transform: translateY(-15px);
    }
   }
   @keyframes float-slow {
    0%, 100% {
      transform: translateY(0);
    }
    50% {
      transform: translateY(-8px);
    }
   }
   .animate-float {
    animation: float 4s ease-in-out infinite;
   }
   .animate-float-slow {
    animation: float-slow 6s ease-in-out infinite;
   }
   @keyframes float-up {
    0% {
      opacity: 0;
      transform: translateY(20px);
    }
    50% {
      opacity: 1;
      transform: translateY(-40px);
    }
    100% {
      opacity: 0;
      transform: translateY(-80px);
    }
   }
   .animate-float-up {
    animation: float-up 5s ease-in-out infinite;
   }
   .delay-0 {
    animation-delay: 0s;
   }
   .delay-2000 {
    animation-delay: 2s;
   }
   .delay-3000 {
    animation-delay: 3s;
   }
   .delay-4000 {
    animation-delay: 4s;
   }
   @keyframes heartbeat {
    0%, 100% {
      transform: scale(1);
      opacity: 1;
    }
    50% {
      transform: scale(1.2);
      opacity: 0.7;
    }
   }
   @keyframes heartbeat-slow {
    0%, 100% {
      transform: scale(1);
      opacity: 1;
    }
    50% {
      transform: scale(1.15);
      opacity: 0.75;
    }
   }
   @keyframes heartbeat-slower {
    0%, 100% {
      transform: scale(1);
      opacity: 1;
    }
    50% {
      transform: scale(1.1);
      opacity: 0.8;
    }
   }
   .animate-heartbeat {
    animation: heartbeat 1.5s ease-in-out infinite;
   }
   .animate-heartbeat-slow {
    animation: heartbeat-slow 2.5s ease-in-out infinite;
   }
   .animate-heartbeat-slower {
    animation: heartbeat-slower 3.5s ease-in-out infinite;
   }
   @keyframes sparkle {
    0%, 100% {
      opacity: 0.2;
      transform: scale(1) rotate(0deg);
    }
    50% {
      opacity: 1;
      transform: scale(1.3) rotate(20deg);
    }
   }
   .animate-sparkle {
    animation: sparkle 3s ease-in-out infinite;
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
  <script>
   function buttonPulse(active) {
    const paw = document.getElementById('pawIcon');
    if (active) {
      paw.classList.add('animate-ping');
    } else {
      paw.classList.remove('animate-ping');
    }
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
  </script>
</body>
</html>