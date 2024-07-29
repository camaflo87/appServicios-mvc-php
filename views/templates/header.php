<header class="header">
    <?php
    if (!empty($_SESSION)) { ?>

        <div>
            <?php require_once __DIR__ . '/enlaces/session.php'; ?>
        </div>
        <nav>
            <form method="POST" action="/logout">
                <input type="submit" value="Cerrar Sesión" class="header__logout">
            </form>
        </nav>

    <?php }
    ?>

</header>