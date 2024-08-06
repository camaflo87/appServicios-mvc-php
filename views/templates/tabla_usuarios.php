<thead class="usuarios__head">
    <tr>
        <th class="usuarios__encabezado nombre">Funcionario</th>
        <th class="usuarios__encabezado email">Email</th>
        <th class="usuarios__encabezado grupo">Grupo</th>
        <th class="usuarios__encabezado disponible">Disponible</th>
        <th class="usuarios__encabezado perfil">Perfil</th>
        <th class="usuarios__encabezado turno">Turno Descanso</th>
        <th class="usuarios__encabezado acciones">Acciones</th>
    </tr>
</thead>

<tbody class="usuarios__registrados">
    <?php foreach ($usuarios as $usuario) { ?>
        <tr class="usuarios__listado">
            <td data-label="Nombre"><?php echo $usuario->id_grado->abreviacion . " " . $usuario->nombre . " " . $usuario->apellido; ?></td>
            <td data-label="Email"><?php echo $usuario->email; ?></td>
            <td data-label="Grupo"><?php echo $usuario->id_grupo->grupo; ?></td>
            <td data-label="Disponible"><?php

                                        if ($usuario->disponible === '0') {
                                            echo 'NO';
                                        }

                                        if ($usuario->disponible === '1') {
                                            echo 'SI';
                                        }

                                        ?>
            </td>
            <td data-label="Perfil"><?php

                                    if ($usuario->usuario === '3') {
                                        echo "Adm. Global";
                                    }

                                    if ($usuario->usuario === '2') {
                                        echo "Adm. Local";
                                    }

                                    if ($usuario->usuario === '1') {
                                        echo "Usuario";
                                    }

                                    if ($usuario->usuario === '0') {
                                        echo "Funcionario";
                                    }
                                    ?></td>
            <td data-label="Turno"><?php echo $usuario->turno; ?></td>

            <?php
            if ($_SESSION['usuario'] === '3') { ?>
                <td data-label="Acciones">
                    <a class="usuarios__modificar" href="/modificar?id=<?php echo $usuario->id; ?>">Modificar</a>
                </td>
            <?php } elseif ($usuario->usuario !== '3') { ?>
                <td data-label="Acciones">
                    <a class="usuarios__modificar" href="/modificar?id=<?php echo $usuario->id; ?>">Modificar</a>
                </td>

            <?php } else { ?>
                <td data-label="Acciones">
                    <p>---------</p>
                </td>

            <?php } ?>
        </tr>
        <!-- <tr class="espacio"></tr> -->
    <?php } ?> <!-- Cierre ciclo -->
</tbody>