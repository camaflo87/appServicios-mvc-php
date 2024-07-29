<main class="servicios">

    <div class="servicios__bloquetitulo">
        <p class="servicios__titulo"><?php echo $titulo; ?></p>
    </div>

    <?php
    require_once __DIR__ . '/../templates/alertas.php';
    date_default_timezone_set('America/Bogota');
    ?>

    <div class="servicios__retorno">
        <a href="/servicios-funcionario" class="servicios__link servicios__link-volver">Volver</a>
    </div>

    <div class="servicios__asignar">
        <section class="servicios servicios__asignacion">
            <p class="servicios__subtitulo">Formulario de Asignación</p>
            <form action="/asignar_servicio" method="post" class="servicios__form">
                <?php include_once __DIR__ . '/formulario_modificar.php' ?>
                <input type="submit" class="form__submit form__submit-modificar" value="Modificar Servicio">
            </form>
        </section>
    </div>
</main>