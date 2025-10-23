<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiénes Somos - UniteDogs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/footer.css">
    <style>
        :root {
            --primary-color: #d97706;
            --secondary-color: #df7d48;
            --accent-color: #f59e0b;
            --amber-bright: #fbbf24;
            --warm-sand: #EAB85C;
            --success-color: #059669;
            --danger-color: #dc2626;
            --text-dark: #333333;
            --dark-slate: #414c5f;
            --bg-warm: #fef9f0;
            --bg-cream: #fff7ed;
            --bg-pale: #fefae6;
            --sage-soft: #a7c4a0;
            --mint-gentle: #b8dbd1;
            --moss-light: #c9d5a1;
            --leaf-tender: #d4e7c5;
            --sky-calm: #a8c5dd;
            --bg-mint: #f0f7f4;
            --bg-sage: #f5f8f3;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--bg-cream) 0%, var(--bg-pale) 50%, var(--bg-mint) 100%);
            min-height: 100vh;
            position: relative;
        }

        /* Floating paws background */
        body::before,
        body::after {
            content: '🐾';
            position: fixed;
            font-size: 8rem;
            opacity: 0.03;
            z-index: 0;
            pointer-events: none;
        }

        body::before {
            top: 15%;
            left: 5%;
            transform: rotate(-25deg);
        }

        body::after {
            bottom: 15%;
            right: 5%;
            transform: rotate(25deg);
        }

        .about-section {
            padding: 140px 20px 80px;
        }

        /* Hero Message */
        .hero-message {
            max-width: 900px;
            margin: 0 auto 80px;
            text-align: center;
        }

        .hero-message h1 {
            font-size: 3.5rem;
            color: var(--text-dark);
            margin-bottom: 20px;
            font-weight: 700;
        }

        .hero-message .paw-icon {
            display: inline-block;
            color: var(--primary-color);
        }

        .hero-message p {
            font-size: 1.2rem;
            color: var(--dark-slate);
            line-height: 1.8;
            margin-bottom: 30px;
        }

        .slogan {
            font-size: 1.8rem;
            color: var(--primary-color);
            font-weight: 600;
            font-style: italic;
            margin-top: 30px;
            padding: 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        /* Mission & Vision */
        .mv-container {
            max-width: 1200px;
            margin: 0 auto 80px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }

        .mv-card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .mv-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--sage-soft), var(--moss-light));
        }

        .mv-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .mv-card h2 {
            font-size: 2rem;
            color: var(--text-dark);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .mv-card .icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--sage-soft), var(--moss-light));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
        }

        .mv-card p {
            color: var(--dark-slate);
            line-height: 1.8;
            font-size: 1.05rem;
        }

        /* Team Section */
        .team-section {
            max-width: 1200px;
            margin: 0 auto;
            padding-bottom: 60px;
        }

        .team-title {
            text-align: center;
            font-size: 2.5rem;
            color: var(--text-dark);
            margin-bottom: 20px;
            font-weight: 700;
        }

        .team-subtitle {
            text-align: center;
            font-size: 1.15rem;
            color: var(--dark-slate);
            margin-bottom: 50px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 35px;
        }

        .team-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
        }

        .team-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.15);
        }

        .team-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--warm-sand));
            border-radius: 0 0 20px 20px;
        }

        .team-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin: 0 auto 20px;
            border: 5px solid var(--primary-color);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            overflow: hidden;
        }

        .team-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .team-name {
            font-size: 1.4rem;
            color: var(--text-dark);
            font-weight: 600;
            margin-bottom: 8px;
        }

        .team-role {
            color: var(--primary-color);
            font-weight: 500;
            margin-bottom: 15px;
            font-size: 1.05rem;
        }

        .team-bio {
            color: var(--dark-slate);
            line-height: 1.6;
            font-size: 0.95rem;
        }

        @media (max-width: 968px) {
            .hero-message h1 {
                font-size: 2.5rem;
            }

            .mv-container {
                grid-template-columns: 1fr;
            }

            .team-grid {
                grid-template-columns: 1fr;
                max-width: 500px;
                margin: 0 auto;
            }
        }

        @media (max-width: 640px) {
            .hero-message h1 {
                font-size: 2rem;
            }

            .slogan {
                font-size: 1.3rem;
            }

            .about-section {
                padding: 120px 20px 60px;
            }
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
    <!-- Navbar -->
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
                    <a href="index.php" class="text-gray-700 hover:text-blue-600 font-medium transition">Inicio</a>
                    <a href="quienes.php" class="text-gray-700 hover:text-blue-600 font-medium transition">¿Quiénes somos?</a>
                    <a href="adopta.php" class="text-gray-700 hover:text-blue-600 font-medium transition">Adopta ya</a>

                </div>
            </div>

            <!-- Mobile menu -->
            <div id="mobileMenu" class="lg:hidden hidden pb-4 border-t border-gray-200 mt-4">
                <div class="space-y-2 mb-4">
                    <a href="index.php" class="block text-gray-700 hover:text-blue-600 font-medium py-2 px-2 rounded hover:bg-gray-50 transition">Inicio</a>
                    <a href="quienes.php" class="block text-gray-700 hover:text-blue-600 font-medium py-2 px-2 rounded hover:bg-gray-50 transition">¿Quiénes somos?</a>
                    <a href="adopta.php" class="block text-gray-700 hover:text-blue-600 font-medium py-2 px-2 rounded hover:bg-gray-50 transition">Adopta ya</a>

                    
                </div>
            </div>
        </div>
    </nav>

    <!-- About Section -->
    <section id="about" class="about-section">
        <!-- Hero Message -->
        <div class="hero-message">
            <h1>¿Quiénes Somos? <span class="paw-icon">🐾</span></h1>
            <p>
              Somos una plataforma de adopción en línea que conecta a perros callejeros con familias adoptivas dispuestas a brindarles un hogar amoroso y cuidados adecuados. UniteDogs es un aplicativo web que realiza la conexión entre perros en situación de calle y familias dispuestas a brindar un hogar cariñoso.


Nuestro aplicativo aborda la conservación y el manejo sostenible de los ecosistemas terrestres, promoviendo la adopción de perritos en situación de calle para controlar la población canina, así como la concientización sobre el respeto, cuidado y búsqueda de la dignidad de los animales como parte integral de los ecosistemas urbanos.
            </p>
            <div class="slogan">
               "De la calle a tu hogar, UniteDogs es el mejor lugar"
            </div>
        </div>

        <!-- Mission & Vision -->
        <div class="mv-container">
            <div class="mv-card">
                <h2>
                    <span class="icon">🎯</span>
                    Nuestra Misión
                </h2>
                <p>
Controlar la sobrepoblación de animales domésticos en situación de calle a través de la adopción, un proceso de creación de conciencia sobre el respeto, cuidado y búsqueda de la dignificación de estos seres vivos como parte integral de los ecosistemas urbanos promoviendo simultáneamente una coexistencia sana y sostenible.
                </p>
            </div>

            <div class="mv-card">
                <h2>
                    <span class="icon">🌟</span>
                    Nuestra Visión
                </h2>
                <p>
                    Ser la plataforma líder en adopción de mascotas, donde ningún perro quede sin hogar y cada adopción sea una historia de éxito. Aspiramos a crear una comunidad comprometida con el bienestar animal y fomentar una cultura de adopción en lugar de compra.
                </p>
            </div>
        </div>

        <!-- Team Section -->
        <div class="team-section">
            <h2 class="team-title">Nuestro Equipo 👥</h2>
            <p class="team-subtitle">Conoce a las personas apasionadas que hacen posible que miles de perritos encuentren su hogar perfecto cada día.</p>
            <div class="team-grid">
                <div class="team-card">
                    <div class="team-avatar">
                        <img src="integrantes imagen/Salomearboleda.jpeg" alt="Salomé Arboleda">
                    </div>
                    <h3 class="team-name">Salomé Arboleda</h3>
                    <p class="team-role">Líder de Proyecto y Desarrolladora</p>
                </div>

                <div class="team-card">
                    <div class="team-avatar">
                        <img src="integrantes imagen/ricardoarango.jpeg" alt="Ricardo Arango Londoño">
                    </div>
                    <h3 class="team-name">Ricardo Arango Londoño</h3>
                    <p class="team-role">Diseñador UI</p>
                </div>

                <div class="team-card">
                    <div class="team-avatar">
                        <img src="integrantes imagen/alanleal.jpeg" alt="Alan leal">
                    </div>
                    <h3 class="team-name">Alan leal</h3>
                    <p class="team-role">Tester</p>
                </div>

                <div class="team-card">
                    <div class="team-avatar">
                        <img src="integrantes imagen/samuelzapata.jpeg" alt="Samuel Zapata">
                    </div>
                    <h3 class="team-name">Samuel Zapata</h3>
                    <p class="team-role">Tester</p>
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
        const menu = document.getElementById('mobileMenu');
        menu.classList.toggle('hidden');
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
