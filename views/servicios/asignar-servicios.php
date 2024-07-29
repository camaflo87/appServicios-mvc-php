<main class="servicios">

    <div class="servicios__bloquetitulo">
        <p class="servicios__titulo"><?php echo $titulo; ?></p>
    </div>

    <?php
    require_once __DIR__ . '/../templates/enlaces/navegacion_asignacion.php';
    ?>

    <?php
    require_once __DIR__ . '/../templates/alertas.php';
    date_default_timezone_set('America/Bogota');
    ?>

    <div class="servicios__seleccion">
        <?php
        if ($_SESSION['usuario'] === '3') { ?>
            <select name="idgrupoGlobal" id="idgrupo">
                <option value="" disabled selected> <-Seleccione un grupo-> </option>
                <?php
                foreach ($grupos as $grupo) { ?>
                    <option value="<?php echo $grupo->id; ?>"><?php echo $grupo->grupo; ?></option>
                <?php    }
                ?>
            </select>
        <?php }else{ ?>
            <input type="hidden" id="userGrupo" value="<?php echo $_SESSION['id_grupo'] ?>">
        <?php }
        ?>
    </div>

    <div class="servicios__asignar">
        <section class="servicios servicios__asignacion">
            <p class="servicios__subtitulo">Formulario de Asignación</p>
            <form action="/asignar_servicio" method="post" class="servicios__form">
                <?php include_once __DIR__ . '/formulario.php' ?>
                <input type="submit" class="form__submit form__submit-modificar" value="Asignar">
            </form>
        </section>
    </div>
</main>