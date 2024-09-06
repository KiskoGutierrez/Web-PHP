<nav>
    <ul>
        <!-- Enlace al inicio, marca como activo si es la página actual -->
        <li><a href="../index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">Inicio</a></li>

        <!-- Enlace a noticias, marca como activo si es la página actual -->
        <li><a href="./views/noticias.php" class="<?= basename($_SERVER['PHP_SELF']) == 'noticias.php' ? 'active' : '' ?>">Noticias</a></li>

        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] == 'user'): ?>
            <!-- Menú para usuarios normales -->
            <li><a href="./views/citaciones.php" class="<?= basename($_SERVER['PHP_SELF']) == 'citaciones.php' ? 'active' : '' ?>">Citaciones</a></li>
            <li><a href="./views/perfil.php" class="<?= basename($_SERVER['PHP_SELF']) == 'perfil.php' ? 'active' : '' ?>">Perfil</a></li>
            <li><a href="../add/logout.php">Cerrar Sesión</a></li>

        <?php elseif (isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin'): ?>
            <!-- Menú para administradores -->
            <li><a href="./views/usuarios-administracion.php" class="<?= basename($_SERVER['PHP_SELF']) == 'usuarios-administracion.php' ? 'active' : '' ?>">Administrar Usuarios</a></li>
            <li><a href="./views/citas-administracion.php" class="<?= basename($_SERVER['PHP_SELF']) == 'citas-administracion.php' ? 'active' : '' ?>">Administrar Citas</a></li>
            <li><a href="./views/noticias-administracion.php" class="<?= basename($_SERVER['PHP_SELF']) == 'noticias-administracion.php' ? 'active' : '' ?>">Administrar Noticias</a></li>
            <li><a href="./views/perfil.php" class="<?= basename($_SERVER['PHP_SELF']) == 'perfil.php' ? 'active' : '' ?>">Perfil</a></li>
            <li><a href="./add/logout.php">Cerrar Sesión</a></li>

        <?php else: ?>
            <!-- Menú para visitantes no autenticados -->
            <li><a href="./views/registro.php" class="<?= basename($_SERVER['PHP_SELF']) == 'registro.php' ? 'active' : '' ?>">Registrarse</a></li>
            <li><a href="./views/login.php" class="<?= basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : '' ?>">Login</a></li>
        <?php endif; ?>
    </ul>
</nav>