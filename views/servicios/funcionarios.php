<main class="servicios">
    <p class="servicios__titulo"><?php echo $titulo; ?></p>

    <?php
    require_once __DIR__ . '/../templates/enlaces/navegacion_asignacion.php';
    ?>

    <div class="servicios__consulta servicios__consulta-agrupar servicios__consulta-<?php echo ($_SESSION['usuario']!=='3')?'solo':''; ?>">
        <?php
        if ($_SESSION['usuario'] === '3') { ?>
            <div class="servicios__grupo">
                <select name="id_grupo" id="id_grupoFuncionario">
                    <option value="" disabled selected> <-Seleccione un grupo-> </option>
                    <?php
                    foreach ($grupos as $grupo) { ?>
                        <option value="<?php echo $grupo->id; ?>"><?php echo $grupo->grupo; ?></option>
                    <?php }
                    ?>
                </select>
            </div>
        <?php } else { ?>
            
            <input type="hidden" id="userGrupo" value="<?php echo $_SESSION['id_grupo']; ?>">
            
           <?php }?>
        <div class="servicios__funcionario">
            <select name="id_funcionario" id="id_funcionario">
                <option value="" disabled selected> <-Seleccione un funcionario-> </option>
            </select>
        </div>
    </div>

    <table class="servicios__table">
        <caption class="servicios__caption">SERVICIOS FUNCIONARIOS</caption>
        <thead class="servicios__head">
            <tr>
                <th class="servicios__encabezado servicio">Servicio</th>
                <th class="servicios__encabezado fecha">Fecha</th>
                <th class="servicios__encabezado lugar">Lugar</th>
                <th class="servicios__encabezado observaciones">Observaciones</th>
                <th class="servicios__encabezado acciones">Acciones</th>
            </tr>
        </thead>
        <tbody class="servicios__body">
            <tr class="servicios__listado">
                <td colspan="5" data-label="------------------------">------------------------</td>
            </tr>
        </tbody>
    </table>

</main>