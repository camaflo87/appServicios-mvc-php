
<main class="main">
    <h3 class="main__titulo"><?php echo $titulo; ?></h3>
    <?php require_once __DIR__ . '/../templates/alertas.php'; ?>
    <form action="/crear" method="post" class="form">
        <?php include_once __DIR__ . '/formulario.php'; ?>
        <div class="form__acciones">
            <input class="form__submit" type="submit" value="Crear Usuario">
            <a class="form__volver" href="/usuarios">Volver</a>
        </div>
    </form>
</main>