<main class="servicios">
    <p class="servicios__titulo"><?php echo $titulo; ?></p>

    <?php
    require_once __DIR__ . '/../templates/enlaces/navegacion_asignacion.php';
    ?>

    <div class="servicios__consulta">
        <?php
        if ($_SESSION['usuario'] === '3') { ?>
            <select name="grupo" id="selectgrupo" class="servicios__grupo">
                <option value="" disabled selected> <-Seleccione un grupo-> </option>
                <?php
                foreach ($grupos as $grupo) { ?>
                    <option value="<?php echo $grupo->id; ?>"><?php echo $grupo->grupo; ?></option>
                <?php }
                ?>
            </select>
        <?php } else { ?>
            <input type="hidden" id="userGrupo" value="<?php echo $_SESSION['id_grupo']; ?>">
       <?php } ?>

        <select name="servicio" id="selectservicio">
            <option value="" disabled selected> <-Selecione un servicio-> </option>
            <?php
            foreach ($servicios as $servicio) { ?>
                <option value="<?php echo $servicio->id; ?>"><?php echo $servicio->servicio; ?></option>
            <?php }
            ?>
        </select>
        <div class="servicios__fecha">
            <div class="servicios__bloque">
                <label for="ini">Fecha Inicial:</label>
                <input type="date" id="ini" name="ini">
            </div>

            <div class="servicios__bloque">
                <label for="fin">Fecha Final:</label>
                <input type="date" id="fin" name="fin">
            </div>

            <div class="servicios__bloque">
                <input type="button" value="Consultar" class="servicios__link" id="btnHistorico">
            </div>
        </div>
    </div>


    <table class="servicios__table">
        <caption class="servicios__caption">CONSULTA HISTORICA POR TIPO DE SERVICIO</caption>
        <thead class="servicios__head">
            <tr>
                <th class="servicios__encabezado funcionario">Funcionario</th>
                <th class="servicios__encabezado fecha">Fecha</th>
                <th class="servicios__encabezado servicio">Semana</th>
                <th class="servicios__encabezado lugar">Lugar</th>
                <th class="servicios__encabezado observaciones">Observaciones</th>
            </tr>
        </thead>
        <tbody class="servicios__body">
            <tr class="servicios__listado">
                <td colspan="5" data-label="------------------------"> ------------------------ </td>
            </tr>
        </tbody>
    </table>

</main>