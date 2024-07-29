<div class="servicios__campo">
    <label for="id_funcionario" class="servicios__label">Funcionario:</label>
    <select name="id_funcionario" id="idFuncionario" class="servicios__opciones">
        <option value="" disabled selected><--Selecione Funcionario-> </option>
        <?php
            foreach($funcionarios as $funcionario){ ?>
                <option value="<?php echo $funcionario->id; ?>"><?php echo $funcionario->id_grado->abreviacion ." ". $funcionario->nombre ." ". $funcionario->apellido; ?></option>
           <?php }
        ?>
    </select>
</div>

<div class="servicios__campo">
    <label for="id_servicio" class="servicios__label">Servicio:</label>
    <select name="id_servicio" id="id_servicio" class="servicios__opciones">
        <option value="" disabled selected><--Selecione Servicio->></option>
        <?php foreach ($servicios as $servicio) { ?>
            <option value="<?php echo $servicios->id_servicio->id; ?>"><?php echo $servicios->id_servicio->servicio; ?></option>
        <?php } ?>
    </select>
</div>

<div class="servicios__campo">
    <label for="fecha" class="servicios__label">Fecha:</label>
    <input class="servicios__opciones" id="fecha" type="date" name="fecha_servicio" min="<?php echo date('Y-m-d'); ?>" value="<?php echo $asignacion->fecha_servicio; ?>">
</div>

<div class="servicios__campo">
    <label for="lugar" class="servicios__label">Lugar:</label>
    <input class="servicios__opciones" id="lugar" type="text" name="lugar" value="<?php echo $asignacion->lugar; ?>">
</div>

<div class="servicios__campo servicios__campo--textarea">
    <label for="observaciones" class="servicios__label">Observaciones:</label>
    <textarea class="servicios__opciones" name="observaciones" id="observaciones" cols="30" rows="10"><?php echo $asignacion->observaciones; ?></textarea>
</div>