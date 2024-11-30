<?php
require 'configuracion.php';
require 'conexiondb.php';
require 'clientefunciones.php';

$db = new Database();
$con = $db->conectar();

$erros = [];
$mensaje = '';
$tipoMensaje = '';

if (!empty($_POST)) {
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);

    if (esNulo([$nombres, $apellidos, $email, $usuario, $password, $repassword])) {
        $erros[] = "Debe llenar todos los campos";
    }
    if (!esEmail($email)) {
        $erros[] = "La dirección de correo no es válida";
    }
    if (!validapassword($password, $repassword)) {
        $erros[] = "Las contraseñas no coinciden";
    }
    if (usuarioexiste($usuario, $con)) {
        $erros[] = "El nombre de usuario $usuario ya existe";
    }
    if (emailexiste($email, $con)) {
        $erros[] = "El correo $email ya existe";
    }

    // Función para validar la contraseña segura
    function validarPasswordSegura($password) {
        $errores = [];

        // Verificar longitud mínima de la contraseña
        if (strlen($password) < 12) {
            $errores[] = "La contraseña debe tener al menos 12 caracteres.";
        }

        // Verificar que la contraseña contenga al menos una letra mayúscula
        if (!preg_match('/[A-Z]/', $password)) {
            $errores[] = "La contraseña debe contener al menos una letra mayúscula.";
        }

        // Verificar que la contraseña contenga al menos un número
        if (!preg_match('/\d/', $password)) {
            $errores[] = "La contraseña debe contener al menos un número.";
        }

        // Verificar que la contraseña contenga al menos un carácter especial
        if (!preg_match('/[\W_]/', $password)) {
            $errores[] = "La contraseña debe contener al menos un carácter especial (por ejemplo: !, @, #, $, etc.).";
        }

        // Retorna true si no hay errores, de lo contrario retorna los errores
        return empty($errores) ? true : $errores;
    }

    // Validar la contraseña de acuerdo a los requisitos de seguridad
    $resultadoValidacionPassword = validarPasswordSegura($password);
    if ($resultadoValidacionPassword !== true) {
        // Si la validación de la contraseña falla, agrega los errores
        $erros = array_merge($erros, $resultadoValidacionPassword);
    }

    if (count($erros) == 0) {
        $id = registraCliente([$nombres, $apellidos, $email], $con);
        if ($id > 0) {
            require 'correo.php';
            $mailer = new Mailer();
            $token = generarToken();
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            $idUsuario = registraUsuario([$usuario, $pass_hash, $token, $id], $con);

            if ($idUsuario > 0) {
                $url = SITE_URL . '/Activar_Cliente.php?id=' . $idUsuario . '&token=' . $token;
                $asunto = "Bienvenido, activa tu nueva cuenta";
                $cuerpo = "Estimado $nombres:<br>Para continuar con el proceso de registro, es indispensable dar clic en la siguiente liga: <a href='$url'>Activar cuenta</a>";

                if ($mailer->enviarEmail($email, $asunto, $cuerpo)) {
                    $mensaje = "Para terminar el proceso de registro, sigue las instrucciones enviadas a tu correo $email.";
                    $tipoMensaje = 'success';
                } else {
                    $erros[] = "Error al enviar el correo de activación";
                }
            } else {
                $erros[] = "Error al registrar usuario";
            }
        } else {
            $erros[] = "Error al registrar cliente";
        }
    }

    if (count($erros) > 0) {
        $mensaje = implode('<br>', $erros);
        $tipoMensaje = 'error';
    }
}


?>
 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://kit.fontawesome.com/ea754b03b4.js" crossorigin="anonymous"></script>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet"/>
    <link
    rel="stylesheet"
    href="https://unpkg.com/swiper@8/swiper-bundle.min.css"
    />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="verificarregistro.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS Bundle (incluye Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
 
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+ua7Kw1TIq0FfDOMi7kMxWGbqu5T6zHoZ6nOPlYw5RlOqaO8Bz7VZGVF77" crossorigin="anonymous">
    <style>
        /* Estilos para los logos */
        body {
            background-color: #f8f9fa; /* Fondo blanco para la página */
            font-family: 'Arial', sans-serif;
        }

        .logo-container {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            display: flex;
            justify-content: space-between;
            width: calc(100% - 40px); /* Ajusta el ancho para dar espacio entre ambos logos */
            z-index: 10; /* Asegura que los logos estén sobre otros elementos */
        }

        .logo-container img {
            max-width: 150px; /* Ajusta el tamaño máximo de los logos */
            height: auto;
        }
    </style>
</head>
<body>
    
<!-- ----------------- HEADER ------------------>
<header>
<div class="logo-container">
        <img src="logo1.png" alt="Logo Izquierda"> <!-- Reemplaza con el logo izquierdo -->
        <img src="logo2.png" alt="Logo Derecha"> <!-- Reemplaza con el logo derecho -->
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zyFWt7R1p73e/78b5wSTY2eMk/2nV8in0zvC9FI4" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-J1Fz60VfbdaOM4dTfPftIu4IQAXySZChpIK+6Kp+dqO1a9aybD8L+M1y2e8vBd+8" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0FfDOMi7kMxWGbqu5T6zHoZ6nOPlYw5RlOqaO8Bz7VZGVF77" crossorigin="anonymous"></script>

    
<!-- ----------------- MAIN ------------------->
        <main>
        <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 50px;
        }
    </style>

            
            <div class="single-login">
           
                <div class="contenedors">
                    <div class="formulario">
                    
                        <form id="form-registro" action="registro.php" method="post" >
                            <h2>Registrar</h2> 
                           

                            <div class="inputcon">
                                <i class="fa-solid fa-user"></i>
                                <input type="text" id="nombres" name="nombres" required  >
                                <label for="nombres">Nombres</label>
                            </div>

                            <div class="inputcon">
                                <i class="fa-solid fa-user"></i>
                                <input type="text" id="apellidos" name="apellidos" required >
                                <label for="apellidos">Primer Apellido</label>   
                            </div>

                            <div class="inputcon">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                <input type="email" id="email" name="email" required>
                                <label for="email">correo</label>
                                <span id="mensaje-correo" class="text-danger"></span><br>

                            </div>

                            <div class="inputcon">
                                <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                                <input type="text" id="usuario" name="usuario"  required>
                                <label for="usuario">Nombre de usuario</label>
                                <span id="mensaje-usuario" class="text-danger"></span><br>
                            </div>                            
                
                            <div class="inputcon">
                                <i class="fa-solid fa-lock"></i>
                                <input type="password" id="password" name="password" >
                                <label for="password">contraseña</label>
                                
                            </div>

                            <div class="inputcon">
                                <i class="fa-solid fa-lock"></i>
                                <input type="password" id="repassword" name="repassword" >
                                <label for="repassword">Repetir contraseña</label>
                                <span id="mensaje-contraseña" style="color: red;"></span>
                               
                            </div>

                            <span id="mensaje-general" style="color: red;"></span>

                            <button class="button" type="submit" >Registrar</button>

                        </form>
                        
                      
                        <div class="registrar"> 
                            <p>Ya tengo cuenta <a href="Login.php">Iniciar sesión</a></p>
                        </div>
                        
                    </div>
                   
                    
                </div>
            </div>
            
        </main>
        <?php if (!empty($mensaje)): ?>
    <script>
        Swal.fire({
            icon: '<?php echo $tipoMensaje; ?>',
            title: '<?php echo $tipoMensaje === "success" ? "Éxito" : "Error"; ?>',
            html: '<?php echo $mensaje; ?>',
        });
    </script>
    <?php endif; ?>

<!-- ----------------- FOOTER ------------------->
        <footer>
            <div class="newsletter">
                <div class="container">
                    <div class="wrapper">
                        <div class="box">
                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-info">
                <div class="container">
                    <div class="wrapper">
                        <div class="flexcol">
                            <div class="logo">
                                <a href=""><span class="circle"></span>.Dungeons</a>
                            </div>
                            <div class="socials">
                                <ul class="flexitem">
                                    <li><a href="#"><i class="ri-twitter-line"></i></a></li>
                                    <li><a href="#"><i class="ri-facebook-line"></i></a></li>
                                    <li><a href="#"><i class="ri-instagram-line"></i></a></li>
                                    <li><a href="#"><i class="ri-linkedin-line"></i></a></li>
                                    <li><a href="#"><i class="ri-youtube-line"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <p class="mini-text">Copyrigth © 2024 .Dungeons. Derechos Reservados </p>
                    </div>
                </div>
            </div>
            <!--           ^  FOOTER-INFO ^           -->
        </footer>


        <div class="menu-bottom desktop-hide">
            <div class="container">
                <div class="wrapper">
                    <nav>
                        <ul class="flexitem">
                            <!--<li>
                                <a href="#">
                                    <i class="ri-bar-chart-line"></i>
                                    <span>Tendencias</span>
                                </a>
                            </li>-->
                            <li>
                                <a href="#">
                                    <i class="ri-user-6-line"></i>
                                    <span>Cuenta</span>
                                </a>
                            </li>
                            <!--
                            <li>
                                <a href="#0" class="t-search">
                                    <i class="ri-search-line"></i>
                                    <span>Buscar</span>
                                </a>
                            </li>
                            
                            <li>
                                <a href="#0" class="cart-trigger">
                                    <i class="ri-shopping-cart-line"></i>
                                    <span>Carrito</span>
                                    <div class="fly-item">
                                        <span class="item-number">0</span>
                                    </div>
                                </a>
                            </li>
-->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!--                ^ MENU-BOTTOM ^             -->

        <div class="search-bottom desktop-hide">
            <div class="container">
                <div class="wrapper">
                    <a href="#" class="t-close search-close flexcenter"><i class="ri-close-line"></i></a>
                    <form action="" class="search">
                        <span class="icon-large"><i class="ri-search-line"></i></span>
                        <input type="search" placeholder="Buscar Productos" required>
                        <button type="submit">Buscar</button>
                    </form>
                </div>
            </div>
        </div>
        <!--                ^ SEARCH BOTTOM ^           -->
    

        <div id="modal" class="modal">
            <div class="content flexcol">
                <div class="image object-cover">
                    <img src="/Imagenes/P5.jpg" alt="">
                </div>
                <h2>Obtenga las ultimas ofertas y cupones</h2>
                <p class="mobile-hide">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptatum aspernatur autem animi fugit ipsa nesciunt!</p>
                
                <a href="#" class="t-close modalclose flexcenter">
                    <i class="ri-close-line"></i>
                </a>
            </div>
        </div>
        <!--        MODAL        -->

        <div class="backtotop">
            <a href="#" class="flexcol">
                <i class="ri-arrow-up-line"></i>
                <span>Top</span>
            </a>
        </div>

        <div class="overlay"></div>
    </div>
    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fslightbox/3.3.1/index.js"></script>
    <script src="/index.js"defer></script>
    <script>
        const FtoShow = '.filter';
        const Fpopup = document.querySelector(FtoShow);
        const Ftrigger = document.querySelector('.filter-trigger');

        Ftrigger.addEventListener('click', () => {
            setTimeout(() => {
                if(!Fpopup.classList.contains('show')) {
                    Fpopup.classList.add('show')
                }
            }, 250)
        })

        //auto close by click outside .filter
        document.addEventListener('click', (e) => {
            const isClosest = e.target.closest(FtoShow);
            if(!isClosest && Fpopup.classList.contains('show')) {
                Fpopup.classList.remove('show')
            }
        })
    </script>
    <script>
        let txtUsuario = document.getElementById('usuario')
        txtUsuario.addEventListener("blur",function(){
            existeUsuario(txtUsuario.value)
        },false)
        let txtEmail = document.getElementById('email')
        txtEmail.addEventListener("blur",function(){
            existeEmail(txtEmail.value)
        },false)
       
        function existeEmail(email){ 
            let url ="ClienteAjax.php"
            let formData = new FormData()
            formData.append("action","existeEmail")
            formData.append("email",email)

            fetch(url,{
                method:'POST',
                body:formData
            }).then(response =>response.json())
            .then(data => {
                if(data.ok){
                    document.getElementById('email').value=''
                    document.getElementById('mensaje-correo').innerHTML = 'correo no disponible'
                }else{
                    document.getElementById('mensaje-correo').innerHTML = ''

                }
            })

        }
        function existeUsuario(usuario){
            
            let url ="ClienteAjax.php"
            let formData = new FormData()
            formData.append("action","existeUsuario")
            formData.append("usuario",usuario)

            fetch(url,{
                method:'POST',
                body:formData
            }).then(response =>response.json())
            .then(data => {
                if(data.ok){
                    document.getElementById('usuario').value=''
                    document.getElementById('mensaje-usuario').innerHTML = 'Usuario no disponible'
                }else{
                    document.getElementById('mensaje-usuario').innerHTML = ''

                }
            })

        }
    </script>

</body>
</html>
