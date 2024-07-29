<nav class="servicios__navegacion">
    <a href="/seguimiento" class="servicios__link <?php echo "servicios__link-" . ($boton === 'seguimiento' ? 'seguimiento' : '') ?>">Seguimiento Servicios</a>
    <a href="/asignar_servicio" class="servicios__link <?php echo "servicios__link-" . ($boton === 'asignar' ? 'asignar' : '') ?>">Asignar Servicios</a>
    <a href="/servicios-funcionario" class="servicios__link <?php echo "servicios__link-" . ($boton === 'funcionario' ? 'funcionario' : '') ?>">Servicios Funcionarios</a>
    <a href="/servicios-historico" class="servicios__link <?php echo "servicios__link-" . ($boton === 'historico' ? 'historico' : '') ?>">Historico Servicios</a>
    <a href="<?php echo $link; ?>" class="servicios__link servicios__link-volver">Volver</a>
</nav>