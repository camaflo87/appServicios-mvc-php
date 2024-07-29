<main class="formaciones">
    <div class="formaciones__encabezado">
        <button class="hamburger formaciones__hamburger hamburger--slider" type="button">
            <span class="hamburger-box">
                <span class="hamburger-inner"></span>
            </span>
        </button>
        <div class="formaciones__listas">
            <?php
            if ($_SESSION['usuario'] === '3') { ?>
                <div class="formaciones__grupo">
                    <label for="id_grupoSeguimiento" class="formaciones__grupo--label">Grupo:</label>
                    <select name="id_grupo" id="id_grupoSeguimiento" class="formaciones__grupo--lista">
                        <option value="" class="formaciones__grupo--lista" disabled selected><-Seleccione un Grupo-></option>
                        <?php
                        foreach ($grupos as $grupo) { ?>
                            <option class="formaciones__grupo--lista" value="<?php echo $grupo->id; ?>"><?php echo $grupo->grupo; ?></option>
                        <?php }
                        ?>
                    </select>
                </div>
            <?php } else { ?>

                <input type="hidden" id="userGrupo" value="<?php echo $_SESSION['id_grupo']; ?>">

            <?php  } ?>

            <div class="formaciones__opc">
                <label for="estado" class="formaciones__opc--label">Estado:</label>
                <select name="estado" id="estado" class="formaciones__opc--lista">
                    <option value="0" disabled selected><-Seleccione un Estado-></option>
                    <option value="1" class="formaciones__opc--lista">Laborando</option>
                    <option value="2" class="formaciones__opc--lista">Franquicia Turno A</option>
                    <option value="3" class="formaciones__opc--lista">Franquicia Turno B</option>
                </select>
            </div>
        </div>

        <nav class="formaciones__navegacion">
            <button class="formaciones__btn formaciones__btn--general" id="btnGeneral">Formación General</button>
            <button class="formaciones__btn formaciones__btn--turnoA" id="btnTurnoA">Turno A</button>
            <button class="formaciones__btn formaciones__btn--turnoB" id="btnTurnoB">Turno B</button>
            <a href="<?php echo $link; ?>" class="usuarios__volver">Volver</a>
        </nav>
    </div>

    <div class="formaciones__sidebar">
        <div class="formaciones__funcionario">
            <?php
            if (empty($funcionarios)) { ?>
                <p class="formaciones__mensaje">Escoja un grupo por favor.... Gracias!</p>
            <?php } else { ?>
                <h2 class="formaciones__subtitulo">Personal</h2>
                <?php foreach ($funcionarios as $funcionario) { ?>
                    <div class="formaciones__registro">
                        <p class="formaciones__nombre"><span><?php echo $funcionario['grado']; ?></span><?php echo $funcionario['nombre']; ?></p>
                        <p class="formaciones__novedad" value="<?php echo $funcionario['id_funcionario']; ?>"><?php echo $funcionario['novedad']; ?></p>
                    </div>
            <?php }
            }
            ?>
        </div>
        <div class="formaciones__novedades">
            <?php
            if (empty($funcionarios)) { ?>
                <p class="formaciones__mensaje">Esperando selección de grupo.....</p>
            <?php } else { ?>
                <h2 class="formaciones__subtitulo">Novedades</h2>
                <p>Total Personal: <span><?php echo $total; ?></span></p>
                <p>Parte Numerico: <span><?php echo $numerico['directivo'] . "-" . $numerico['ejecutivo'] . "-" . $numerico['base']; ?></span></p>
                <?php foreach ($novedades as $novedad) { ?>
                    <div class="formaciones__bloque">
                        <label class="formaciones__label"><?php echo $novedad->novedad; ?></label>
                        <input type="text" value="<?php echo $novedad->cant; ?>" class="formaciones__cant">
                        <input type="text" value="<?php echo $novedad->numerico; ?>" class="formaciones__numerico">
                    </div>
            <?php }
            }
            ?>
        </div>
    </div>
</main>

<!-- Bloque modal emergente -->
<section class="modal">
    <div class="modal__container">
        <h2 class="modal__title">Actualizar Información</h2>
        <form class="modal__formulario">
            <div class="modal__bloque">
                <label for="funcionario">Funcionario:</label>
                <input type="text" id="funcionario" value="" name="funcionario" class="modal__input" disabled>
            </div>

            <div class="modal__bloque">
                <label for="situacion">Situación actual:</label>
                <select name="situacion" id="situacion">
                </select>
            </div>

            <div class="modal__bloque">
                <label for="observacion">Observación:</label>
                <textarea name="observacion" id="observacion" maxlength="250" class="modal__textArea"></textarea>
            </div>

            <fieldset class="modal__cambios">
                <input type="button" value="Actualizar" class="modal__actualizar">
                <button class="modal__cancelar">Cancelar</button>
            </fieldset>
        </form>
    </div>
</section>