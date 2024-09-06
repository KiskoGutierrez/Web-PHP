<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <!-- Header y sus componentes -->
    <header>
        <h1 class="text_header">Bienvenidos a Forza Motors</h1>
        <nav>
            <ul>
                <?php include './views/navbar.php'; ?>
            </ul>
        </nav>
    </header>

    <!-- Imagenes principales con botones prev y next -->
    <div class="slideshow-container">

        <div class="mySlides fade">
            <img src="./images/bmw_m3.jpg">
        </div>

        <div class="mySlides fade">
            <img src="./images/jaguar.jpg">
        </div>

        <div class="mySlides fade">
            <img src="./images/audi_tt.jpg">
        </div>

        <div class="mySlides fade">
            <img src="./images/mercedes.jpg">
        </div>

        <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
        <a class="next" onclick="plusSlides(1)">&#10095;</a>
    </div>

    <!-- Sección con el contenido de la web (Noticias, destacados, etc...) -->
    <section class="content">
        <h2 class="title-1">Últimas noticias</h2>
        <div class="content-1">
            <div class="news"><img src="./images/mini-img1.JPG" alt="">
                <br>
                <p>
                    <b>Bentley transforma el Continental GT Speed en un híbrido enchufable de 782 CV</b>
                </p>
                <p>
                    Puede circular hasta 81 kilómetros en modo eléctrico, tiene etiqueta Cero y alcanza una velocidad
                    máxima de 335 km/h. <a href="https://motor.elpais.com/actualidad/bentley-transforma-el-continental-gt-speed-en-un-hibrido-enchufable-de-782-cv/" target="_blank">Saber más...</a>
                </p>
            </div>
            <div class="news"><img src="./images/mini-img2.JPG" alt="">
                <br>
                <p>
                    <b>Para los amantes de los coches históricos: el BOE subasta un Mini de hace más de 50 años</b>
                </p>
                <p>
                    Además de este Morris Mini, entre las ventas oficiales hay un Porsche tasado en más de 80.000 euros
                    y en buen estado. <a href="https://motor.elpais.com/actualidad/subasta-boe-morris-mini-de-hace-mas-de-50-anos/" target="_blank">Saber más...</a>
                </p>
            </div>
            <div class="news"><img src="./images/mini-img3.JPG" alt="">
                <br>
                <p>
                    <b>Un empresario indio y una estafa millonaria: la historia de este Rolls-Royce Phantom
                        abandonado</b>
                </p>
                <p>
                    Esta berlina de lujo se encuentra aparcada en un hotel de Calcuta, también fuera de servicio. Ambos
                    bienes pertenecen al mismo empresario indio.<a href="https://motor.elpais.com/actualidad/empresario-indio-estafa-millonaria-rolls-royce-abandonado/" target="_blank">Saber más...</a>
                </p>
            </div>
            <div class="news"><img src="./images/mini-img4.JPG" alt="">
                <br>
                <p>
                    <b>Bad Bunny se arrepiente de haber comprado un Bugatti: el mantenimiento cuesta miles de euros al
                        año</b>
                </p>
                <p>
                    Un simple cambio de aceite cuesta alrededor de 23.000 euros. Cambiar las llaves es tan caro como
                    comprar un utilitario.<a href="https://motor.elpais.com/supercoches/bad-bunny-se-arrepiente-comprado-bugatti-elevado-precio-mantenimiento/" target="_blank">Saber más...</a>
                </p>
            </div>
        </div>
        <h2 class="title-2">Destacados</h2>
        <div class="content-2">
            <div class="news"><img src="./images/mini-img5.JPG" alt="">
                <br>
                <p>
                    <b>El Rolls-Royce Cullinan mantiene su gran lujo, pero cambia de cara</b>
                </p>
                <p>
                    El modelo más vendido de la marca de lujo estrena diseño y mantiene sus dos versiones con 70 y 600
                    CV de potencia.<a href="https://motor.elpais.com/actualidad/el-rolls-royce-cullinan-mantiene-su-gran-lujo-pero-cambia-de-cara/" target="_blank">Saber más...</a>
                </p>
            </div>
            <div class="news"><img src="./images/mini-img6.JPG" alt="">
                <br>
                <p>
                    <b>Cómo perder 227.000 euros ‘tirando’ un Ferrari Roma por el hueco de un ascensor</b>
                </p>
                <p>
                    Problemas en el funcionamiento del ascensor de un concesionario de Florida han acabado provocando
                    este aparatoso accidente.<a href="https://motor.elpais.com/supercoches/como-perder-227000-euros-tirando-ferrari-roma-hueco-ascensor/" target="_blank">Saber más...</a>
                </p>
            </div>
            <div class="news"><img src="./images/mini-img7.JPG" alt="">
                <br>
                <p>
                    <b>Midsummer, la última colaboración entre Morgan y Pininfarina que evoca los años treinta</b>
                </p>
                <p>
                    La producción de esta ‘barchetta’ va a estar marcada por la exclusividad, ya que solo se fabricarán
                    50 unidades que ya tienen dueño. <a href="https://motor.elpais.com/supercoches/midsummer-colaboracion-morgan-pininfarina/" target="_blank">Saber más...</a>
                </p>
            </div>
            <div class="news"><img src="./images/mini-img8.JPG" alt="">
                <br>
                <p>
                    <b>Un Porsche a 2.800 euros: la curiosa subasta de coches inundados </b>
                </p>
                <p>
                    Después de las tormentas, algunas páginas de subastas de Dubái se están llenando de coches dañados
                    por el agua a muy bajos precios. <a href="https://motor.elpais.com/supercoches/porsche-2800-euros-coches-inundados-subastados-dubai/" target="_blank">Saber más...</a>
                </p>
            </div>
        </div>
    </section>

    <!-- Botón flotante para subir hacia el principio de la página -->

    <button id="scrollToTopBtn">↑</button>

    <script src="./js/script.js"></script>

    <!-- Sección de footer y su contenido -->
    <footer>
        <p>Siguenos en nuestras redes sociales: </p>
        <div class="social-ft">
            <div class="social">
                <a href="https://twitter.com" target="_blank">
                    <img src="./images/Twitter.svg" alt="logo-twitter" />
                </a>
            </div>
            <div class="social">
                <a href="https://youtube.com" target="_blank">
                    <img src="./images/youtube.svg" alt="logo-youtube" />
                </a>
            </div>
            <div class="social">
                <a href="https://facebook.com" target="_blank">
                    <img src="./images/FAcebook.svg" alt="logo-facebook" />
                </a>
            </div>
            <div class="social">
                <a href="https://instagram.com" target="_blank">
                    <img src="./images/Instagram.svg" alt="logo-instagram" />
                </a>
            </div>
        </div>
        <p class="ft-1">&copy; 2024 Todos los derechos reservados</p>
    </footer>
</body>

</html>
