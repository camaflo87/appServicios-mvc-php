<main class="auth">
    <h2 class="auth__heading"><?php echo $titulo; ?></h2>
    <p class="auth__texto">Inicia sesión Sistema Control de Servicios</p>

    <?php
        require_once __DIR__ . '/../templates/alertas.php';
    ?>

    <form class="formulario" method="POST">

        <div class="formulario__image">
            <picture>
                <source srcset="/build/img/logoPolicia.webp" type="image/webp">
                <source srcset="/build/img/logoPolicia.avif" type="image/avif">
                <img src="/build/img/logoPolicia.jpg" type="image/jpg" alt="Imagen Ponente">
            </picture>
        </div>
        
        <div class="formulario__campos">

            <div class="formulario__campo">
                <label for="email" class="formulario__label">Email</label>
                <input type="email" class="formulario__input" placeholder="Tu Email" id="email" name="email">
            </div>
    
            <div class="formulario__campo">
                <label for="password" class="formulario__label">Password</label>
                <input type="password" class="formulario__input" placeholder="Tu Password" id="password" name="password">
            </div>
    
            <input type="submit" class="formulario__submit" value="Iniciar Sesión">
        </div>
    </form>
</main>