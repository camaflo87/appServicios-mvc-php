<fieldset class="form__fieldset">
    <legend class="form__legend">Datos Usuario</legend>

    <div class="form__campo">
        <label class="form__label" for="nombre">Nombre</label>
        <input class="form__input" type="text" id="nombre" name="nombre" placeholder="Digita tu nombre" value="<?php echo $usuarios->nombre; ?>">
    </div>

    <div class="form__campo">
        <label class="form__label" for="apellido">Apellido</label>
        <input class="form__input" type="text" id="apellido" name="apellido" placeholder="Digita tu apellido" value="<?php echo $usuarios->apellido; ?>">
    </div>

    <div class="form__campo">
        <label class="form__label" for="grado">Grado</label>
        <select class="form__select" name="id_grado" id="grado">
            <option value="" disabled selected>>>Seleccione el grado<< </option>
                    <?php foreach ($grados as $grado) { ?>
            <option <?php echo ($usuarios->id_grado === $grado->id) ? 'selected' : '' ?> value="<?php echo $grado->id; ?>"><?php echo $grado->grado; ?></option>
        <?php } ?>
        </select>
    </div>

    <div class="form__campo">
        <label class="form__label" for="email">Email</label>
        <input class="form__input" type="email" id="email" name="email" placeholder="Digita tu Email" value="<?php echo $usuarios->email; ?>">
    </div>

    <div class="form__campo">
        <label class="form__label" for="turno">Turno de Trabajo</label>
        <select class="form__select" name="turno" id="turno">
            <option value="" disabled selected>>>Selecciona un turno<< </option>
            <option value="A" <?php echo ($usuarios->turno === 'A') ? 'selected' : ''; ?>>Turno A</option>
            <option value="B" <?php echo ($usuarios->turno === 'B') ? 'selected' : ''; ?>>Turno B</option>
        </select>
    </div>

    <div class="form__campo">
        <label class="form__label" for="grupo">Grupo</label>
        <select class="form__select" name="id_grupo" id="grupo">
            <option value="" disabled selected>>>Seleccione el grupo<<</option>
                    <?php
                    if ($_SESSION['usuario'] === '3') {
                        foreach ($grupos as $grupo) { ?>
            <option <?php echo ($usuarios->id_grupo === $grupo->id) ? 'selected' : '' ?> value="<?php echo $grupo->id; ?>"><?php echo $grupo->grupo; ?></option>
            <?php }
                    } else {
                        foreach ($grupos as $grupo) {
                            if ($_SESSION['id_grupo'] === $grupo->id) { ?>
                <option <?php echo ($_SESSION['id_grupo'] === $grupo->id) ? 'selected' : '' ?> value="<?php echo $grupo->id; ?>"><?php echo $grupo->grupo; ?></option>
    <?php }
                        }
                    }
    ?>

        </select>
    </div>

</fieldset>

<fieldset class="form__fieldset">
    <legend class="form__legend">Tipo Usuario</legend>

    <div class="form__campo">
        <label class="form__label" for="usuario">Tipo de Usuario</label>
        <select class="form__select" name="usuario" id="usuario">
            <option value="" disabled selected>>>Seleccione un perfil<<</option>
                    <?php
                    if ($_SESSION['usuario'] === '3') { ?>
            <option value="0" <?php echo ($usuarios->usuario === '0') ? 'selected' : ''; ?>>Funcionario</option>
            <option value="1" <?php echo ($usuarios->usuario === '1') ? 'selected' : ''; ?>>Usuario</option>
            <option value="2" <?php echo ($usuarios->usuario === '2') ? 'selected' : ''; ?>>Administrador Local</option>
            <option value="3" <?php echo ($usuarios->usuario === '3') ? 'selected' : ''; ?>>Administrador Global</option>
        <?php } else { ?>
            <option value="0" <?php echo ($usuarios->usuario === '0') ? 'selected' : ''; ?>>Funcionario</option>
            <option value="1" <?php echo ($usuarios->usuario === '1') ? 'selected' : ''; ?>>Usuario</option>
        <?php }
        ?>

        </select>
    </div>

    <div class="form__campo">
        <label class="form__label" for="password">Password</label>
        <input class="form__input" type="password" name="password" placeholder="Digitá tu password" id="password">
    </div>

    <div class="form__campo">
        <label class="form__label" for="passwordTwo">Repetir Password</label>
        <input class="form__input" type="password" name="passwordTwo" placeholder="Digitá tu password" id="passwordTwo">
    </div>

</fieldset>