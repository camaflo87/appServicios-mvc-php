<main class="servicios">
    <p class="servicios__titulo"><?php echo $titulo; ?></p>

    <?php
    require_once __DIR__ . '/../templates/enlaces/navegacion_asignacion.php';
    ?>

    <?php
    if ($_GET) {
        $alertas['exito'][] = $_GET['msg'];
        require_once __DIR__ . '/../templates/alertas.php';
    }
    ?>

    <div class="servicios__consulta">
        <?php
        if ($_SESSION['usuario'] === '3') { ?>
            <div class="servicios__grupo">
                <select name="id_grupo" id="id_grupoSeguimiento">
                    <option value="" disabled selected> <-Seleccione un Grupo-> </option>
                    <?php
                    foreach ($grupos as $grupo) { ?>
                        <option value="<?php echo $grupo->id; ?>"><?php echo $grupo->grupo; ?></option>
                    <?php }
                    ?>
                </select>
            </div>
        <?php } else { ?>

            <input type="hidden" id="userGrupo" value="<?php echo $_SESSION['id_grupo']; ?>">

        <?php  } ?>

        <div class="servicios__opc">
            <div class="servicios__bloque">
                <label for="todos">Todos</label>
                <input type="radio" name="nivel" id="todos" checked value="Todos">
            </div>
            <div class="servicios__bloque">
                <label for="directivo">N. Directivo</label>
                <input type="radio" name="nivel" id="directivo" value="Directivo">
            </div>
            <div class="servicios__bloque">
                <label for="ejecutivo">N. Ejecutivo</label>
                <input type="radio" name="nivel" id="ejecutivo" value="Ejecutivo">
            </div>
            <div class="servicios__bloque">
                <label for="patrullero">Patrulleros y otros</label>
                <input type="radio" name="nivel" id="patrullero" value="Base">
            </div>
        </div>

        <div>
            <input type="button" id="seguimiento" class="servicios__link" value="Consultar">
        </div>
    </div>

    <table class="servicios__table">
        <caption class="servicios__caption">CONTROL SERVICIOS</caption>
        <thead class="servicios__head">
            <tr>
                <th class="servicios__encabezado funcionario">Funcionario</th>
                <th class="servicios__encabezado servicio">Servicio</th>
                <th class="servicios__encabezado fecha">Fecha</th>
                <th class="servicios__encabezado dias">Dias sin Servicio</th>
                <th class="servicios__encabezado lugar">Lugar</th>
                <th class="servicios__encabezado observaciones">Observaciones</th>
            </tr>
        </thead>
        <tbody class="servicios__body">
            <tr class="servicios__listado">
                <td colspan="6" data-label="---------------">--------------------------</td>
            </tr>
        </tbody>
    </table>

</main>