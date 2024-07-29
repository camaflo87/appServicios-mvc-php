<main class="menu__administracion servicios">
    <div>
        <p class="menu__titulo"> <?php echo $titulo; ?></p>
    </div>

    <table class="servicios__table">
        <caption class="servicios__caption">SERVICIOS FUNCIONARIO</caption>
        <thead class="servicios__head">
            <tr>
                <th class="servicios__encabezado funcionario">Funcionario</th>
                <th class="servicios__encabezado servicio">Servicio</th>
                <th class="servicios__encabezado fecha">Fecha</th>
                <th class="servicios__encabezado lugar">Lugar</th>
                <th class="servicios__encabezado observaciones">Observaciones</th>
            </tr>
        </thead>
        <tbody class="servicios__body">
            <?php
            foreach ($servicios as $servicio) { ?>
                <tr class="servicios__listado">
                    <td data-label="funcionario"><?php echo $servicio['funcionario']; ?></td>
                    <td data-label="servicio"><?php echo $servicio['servicio']; ?></td>
                    <td data-label="fecha"><?php echo $servicio['fecha']; ?></td>
                    <td data-label="lugar"><?php echo $servicio['lugar']; ?></td>
                    <td data-label="observaciones"><?php echo $servicio['observaciones']; ?></td>
                </tr>
            <?php }
            ?>

        </tbody>
    </table>

</main>