<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniteDogs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

   
    <link rel="stylesheet" href="css/inicio.css">
</head>
<body>




    <section class="header">
        <!-- Navbar Mejorado -->
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
                       
                        
                        <!-- Admin/Login Section -->
                       
                    </div>
                </div>

                <!-- Mobile menu -->
                <div id="mobileMenu" class="lg:hidden hidden pb-4 border-t border-gray-200 mt-4">
                    <!-- Mobile Navigation Links -->
                    <div class="space-y-2 mb-4">
                        <a href="index.html" class="block text-gray-700 hover:text-blue-600 font-medium py-2 px-2 rounded hover:bg-gray-50 transition">Inicio</a>
                        <a href="./paginas internas/QuienesSomos.html" class="block text-gray-700 hover:text-blue-600 font-medium py-2 px-2 rounded hover:bg-gray-50 transition">¿Quiénes somos?</a>
                        <a href="./paginas internas/AdoptaYa.html" class="block text-gray-700 hover:text-blue-600 font-medium py-2 px-2 rounded hover:bg-gray-50 transition">Adopta ya</a>
                        <a href="#" class="block text-gray-700 hover:text-blue-600 font-medium py-2 px-2 rounded hover:bg-gray-50 transition">De regreso a casa</a>
                        <a href="http://localhost/PaginaMedia/conexion.php/" class="block text-gray-700 hover:text-blue-600 font-medium py-2 px-2 rounded hover:bg-gray-50 transition">Contacto</a>
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

        <!-- HUESITOS -->
        <div class="huesito" style="bottom: 15px; left: 1rem; "></div>
        <div class="huesito3" style="bottom: 800px; left: 120px;"></div>
        <div class="huesito2" style="bottom: 15px; left: 110rem;"></div>
        <div class="huesito" style="bottom: 50rem; left: 110rem;"></div>
        <div class="huesito3" style="bottom: 10rem; left: 80rem;"></div>
        <div class="huesito2" style="top: 5rem; left: 40rem;"></div>

        <div class="content" style="padding-top: 120px;">
            <div class="textBox">
                <h2>No se trata solo de encontrar un hogar, se trata de salvar vidas
                    <br>somos<span id="animated-text">UniteDogs</span></h2>
                <p>Descubre la alegría de adoptar un nuevo amigo peludo. En UnitedDogs, cada perro tiene una historia y cada adopción es un acto de amor. Explora perfiles de perros adorables y encuentra a tu compañero perfecto hoy mismo. ¡Haz la diferencia en una vida!</p>
            <a href="#">Leer más</a>
            </div>
            <div class="imgBox">
                <img src="imgfront/perroinicio.png" alt="">
            </div>
        </div>
        <ul class="sci">
            <li><a href=""><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
            <li><a href=""><i class="fa fa-youtube-play" aria-hidden="true"></i></a></li>
            <li><a href=""><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
        </ul>
    </section>      

    <!-- Floating Paws Background -->
    <div class="paw-container" id="pawContainer"></div>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <h2 class="section-title">¿Por Qué Adoptar con Nosotros?</h2>
            <p class="section-subtitle">Comprometidos con el bienestar animal y tu tranquilidad</p>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">❤️</div>
                    <h3>Salvas una Vida</h3>
                    <p>Cada adopción significa una segunda oportunidad para un perrito que merece amor y cuidados. Tu decisión cambia vidas.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🏥</div>
                    <h3>Salud Garantizada</h3>
                    <p>Todos nuestros perritos están vacunados, desparasitados y con chequeo veterinario completo antes de la entrega.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🤝</div>
                    <h3>Acompañamiento Total</h3>
                    <p>Te brindamos asesoría y seguimiento personalizado para asegurar una adaptación exitosa y feliz.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Cómo Ayudar -->
    <section class="process">
        <div class="container">
            <h2 class="section-title" style="color: white; filter: drop-shadow(0 2px 8px rgba(0,0,0,0.3));">¿Cómo Puedes Ayudar?</h2>
            <p class="section-subtitle" style="color: rgba(255,255,255,0.9);">Hay muchas formas de apoyar nuestra causa y cambiar vidas</p>
            <div class="process-grid">
                <div class="process-step" data-step="🏠">
                    <h3>Adopta</h3>
                    <p>Dale un hogar permanente a un perrito que lo necesita. Es el acto más directo de amor.</p>
                </div>
                <div class="process-step" data-step="🏡">
                    <h3>Hogar Temporal</h3>
                    <p>Si no puedes adoptar, sé un hogar temporal mientras encuentran familia definitiva.</p>
                </div>
                <div class="process-step" data-step="💰">
                    <h3>Dona</h3>
                    <p>Tu aporte ayuda a cubrir gastos veterinarios, alimentación y mantenimiento del refugio.</p>
                </div>
                <div class="process-step" data-step="🤲">
                    <h3>Voluntariado</h3>
                    <p>Dona tu tiempo: pasea perritos, ayuda en eventos o tareas administrativas.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Galería de Historias -->
    <section class="success-gallery">
        <div class="container">
            <h2 class="section-title">Historias que Nos Motivan</h2>
            <p class="section-subtitle">Cada adopción es una historia de amor y segunda oportunidad</p>
            <div class="gallery-grid">
                <div class="gallery-card">
                    <div class="gallery-image">
                        <img src="https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=400&h=300&fit=crop&crop=center" alt="Perro adoptado Luna" loading="lazy">
                    </div>
                    <div class="gallery-content">
                        <h4>Luna</h4>
                        <p>Luna fue rescatada de las calles y encontró su hogar después de 6 meses en el refugio. Ahora es la compañera inseparable de María en sus caminatas matutinas.</p>
                    </div>
                </div>
                <div class="gallery-card">
                    <div class="gallery-image">
                        <img src="https://images.unsplash.com/photo-1583337130417-3346a1be7dee?w=400&h=300&fit=crop&crop=center" alt="Perro adoptado Max" loading="lazy">
                    </div>
                    <div class="gallery-content">
                        <h4>Max</h4>
                        <p>Max llegó tímido al refugio pero hoy es el guardián y mejor amigo de los niños de la familia Rodríguez. Su transformación fue increíble.</p>
                    </div>
                </div>
                <div class="gallery-card">
                    <div class="gallery-image">
                        <img src="https://images.unsplash.com/photo-1544568100-847a948585b9?w=400&h=300&fit=crop&crop=center" alt="Perro adoptado Rocky" loading="lazy">
                    </div>
                    <div class="gallery-content">
                        <h4>Rocky</h4>
                        <p>Rocky fue rescatado de la calle y hoy disfruta de una vida llena de amor, juegos y aventuras junto a Carlos.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq">
        <div class="container">
            <h2 class="section-title">Preguntas Frecuentes</h2>
            <p class="section-subtitle">Resolvemos tus dudas sobre el proceso de adopción</p>
            
            <div class="faq-item">
                <div class="faq-question">
                    ¿Cuáles son los requisitos para adoptar?
                    <span>+</span>
                </div>
                <div class="faq-answer">
                    Para adoptar necesitas ser mayor de edad, contar con identificación oficial, comprobante de domicilio, y demostrar que tienes las condiciones adecuadas para cuidar a un perrito. También realizamos una entrevista para conocer tu estilo de vida y asegurarnos de que sea compatible con las necesidades del perrito.
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    ¿Tiene algún costo la adopción?
                    <span>+</span>
                </div>
                <div class="faq-answer">
                    La adopción tiene una cuota de recuperación simbólica que cubre los gastos veterinarios (vacunas, esterilización, desparasitación) y ayuda a sostener nuestro refugio. El monto varía según el tamaño y edad del perrito, pero es mucho menor que el costo real de los cuidados recibidos.
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    ¿Los perritos están esterilizados?
                    <span>+</span>
                </div>
                <div class="faq-answer">
                    Sí, todos nuestros perritos son esterilizados antes de ser entregados en adopción. En caso de cachorros muy pequeños, se firma un compromiso para realizar la esterilización cuando alcancen la edad adecuada, y nosotros hacemos seguimiento.
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    ¿Puedo devolver al perrito si no me adapto?
                    <span>+</span>
                </div>
                <div class="faq-answer">
                    Entendemos que pueden surgir situaciones imprevistas. Si por alguna razón no funciona la adopción, nos comprometemos a recibir de vuelta al perrito. Sin embargo, te ofrecemos un periodo de adaptación con asesoría gratuita para ayudarte a superar cualquier dificultad inicial.
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    ¿Hacen seguimiento después de la adopción?
                    <span>+</span>
                </div>
                <div class="faq-answer">
                    Sí, realizamos seguimiento durante los primeros meses para asegurarnos de que tanto tú como tu nuevo amigo están felices y adaptados. Además, estamos disponibles para resolver cualquier duda o preocupación que tengas durante toda la vida de tu perrito.
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    ¿Puedo adoptar si vivo en departamento?
                    <span>+</span>
                </div>
                <div class="faq-answer">
                    Sí, vivir en departamento no es impedimento para adoptar. Lo importante es que el espacio sea adecuado para el tamaño del perrito y que puedas brindarle paseos diarios y ejercicio suficiente. Te ayudamos a encontrar el perrito ideal para tu espacio.
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials">
        <div class="container">
            <h2 class="section-title" style="color: white; filter: drop-shadow(0 2px 8px rgba(0,0,0,0.3));">Historias de Éxito</h2>
            <p class="section-subtitle" style="color: rgba(255,255,255,0.9);">Lo que dicen nuestras familias adoptantes</p>
            <div class="testimonial-grid">
                <div class="testimonial-card">
                    <p class="testimonial-text">"Adoptar a Luna fue la mejor decisión de mi vida. Ella llegó a llenar mi hogar de alegría y amor incondicional. El proceso fue muy profesional y siempre me sentí acompañada."</p>
                    <p class="testimonial-author">- María González</p>
                </div>
                <div class="testimonial-card">
                    <p class="testimonial-text">"Max se adaptó perfectamente a nuestra familia. Los niños lo adoran y él es muy paciente con ellos. Gracias por ayudarnos a encontrar al compañero perfecto."</p>
                    <p class="testimonial-author">- Carlos Rodríguez</p>
                </div>
                <div class="testimonial-card">
                    <p class="testimonial-text">"El seguimiento post-adopción fue excelente. Siempre respondieron mis dudas y me dieron consejos útiles. Rocky es ahora parte fundamental de mi vida."</p>
                    <p class="testimonial-author">- Ana Martínez</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Final -->
    <section class="cta-final">
        <div class="container">
            <h2>¿Listo para Cambiar una Vida?</h2>
            <p>Tu nuevo mejor amigo te está esperando</p>
            <a href="#" class="btn-primary">Iniciar Proceso de Adopción</a>
        </div>
    </section>

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
                    <li>📞 (555) 123-4567</li>
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
        // Crear huellas flotantes y huesitos
        const pawContainer = document.getElementById('pawContainer');
        const symbols = ['🐾', '🐾', '🐾', '🦴', '🦴'];
        
        for (let i = 0; i < 25; i++) {
            const element = document.createElement('div');
            const symbol = symbols[Math.floor(Math.random() * symbols.length)];
            element.className = symbol === '🦴' ? 'bone' : 'paw';
            element.textContent = symbol;
            element.style.left = Math.random() * 100 + '%';
            element.style.animationDelay = Math.random() * 20 + 's';
            element.style.fontSize = (20 + Math.random() * 30) + 'px';
            pawContainer.appendChild(element);
        }
        
        // Toggle FAQ
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', function() {
                const faqItem = this.parentElement;
                const answer = this.nextElementSibling;
                const icon = this.querySelector('span');
                
                // Cerrar todas las otras respuestas
                document.querySelectorAll('.faq-item').forEach(item => {
                    if (item !== faqItem) {
                        item.classList.remove('active');
                        item.querySelector('.faq-answer').classList.remove('active');
                        item.querySelector('.faq-question span').textContent = '+';
                    }
                });
                
                // Toggle la respuesta actual
                faqItem.classList.toggle('active');
                answer.classList.toggle('active');
                icon.textContent = answer.classList.contains('active') ? '−' : '+';
            });
        });

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
</body>
</html>