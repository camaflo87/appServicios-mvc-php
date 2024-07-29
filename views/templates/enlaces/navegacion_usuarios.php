<nav class="usuarios__navegacion">
    <a href="/crear" class="usuarios__crear">Crear Usuario</a>
    <?php
    if ($_SESSION['usuario'] === '3') { ?>
        <a href="/usuarios" class="usuarios__crear <?php echo "usuarios__" . ($boton === 'funcionarios' ? 'funcionarios' : '') ?>">Usuarios Registrados</a>
        <a href="/consulta-adm" class="usuarios__crear <?php echo "usuarios__" . ($boton === 'admin' ? 'admin' : '') ?>">Consultar Administradores</a>
        <a href="/consulta-inactivos" class="usuarios__crear <?php echo "usuarios__" . ($boton === 'inactivos' ? 'inactivos' : '') ?>">Usuarios Inactivos</a>
    <?php }
    ?>
    <a href="<?php echo $link; ?>" class="usuarios__volver">Volver</a>
</nav>