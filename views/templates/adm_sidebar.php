<aside class="menu__sidebar">
    <nav class="menu__navegacion">

        <?php
            
            switch ($_SESSION['usuario'])
            {
                case 0:
                ?>
                    <a href="/admin-funcionario" class="menu__enlace">Funcionario</a>
                <?php 
                    break;
                case 1:
                    ?>
                        <a href="/admin-usuario" class="menu__enlace">Usuario</a>
                    <?php 
                    break;

                case 2:
                        ?>
                            <a href="/admin-secundario" class="menu__enlace">Usuario Adm.</a>
                        <?php 
                    break;
                case 3:
                        ?>
                            <a href="/admin-principal" class="menu__enlace">Administrador</a>
                        <?php 
                    break;
            }
        ?>       
            <a href="/admin-personal" class="menu__enlace">Parte Personal</a>
    </nav>
</aside>