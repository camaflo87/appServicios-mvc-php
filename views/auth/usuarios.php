<main class="usuarios">
    <p class="usuarios__titulo"><?php echo $titulo; ?></p>

    <?php
    require_once __DIR__ . '/../templates/enlaces/navegacion_usuarios.php';
    ?>

    <?php if (empty($usuarios)) { ?>
        <p class="usuarios__sinregistros">No hay registros para mostrar</p>
    <?php } else { ?>

        <div>
            <label for="filtro">Buscar funcionario:</label>
            <input type="text" id="filtro" placeholder="Escribe nombre funcionario">
        </div>

        <table class="usuarios__table">
            <caption class="usuarios__caption">Usuarios Registrados (Cant: <?php echo count($usuarios); ?>)</caption>
            <?php require_once __DIR__ . '/../templates/tabla_usuarios.php'; ?>
        </table>
    <?php } ?> <!--Cierra condicional sinregistros-->
</main>