<?php
if ($_SESSION['usuario'] === '3') { ?>
    <a href="/usuarios" class="administracion__enlaces">Adm. Usuarios</a>
    <a href="/seguimiento" class="administracion__enlaces">Asignar Servicios</a>
    <a href="/enlaces-formacion" class="administracion__enlaces">Formación Personal</a>
<?php }
if ($_SESSION['usuario'] === '1' || $_SESSION['usuario'] === '2') { ?>
    <a href="/seguimiento" class="administracion__enlaces">Asignar Servicios</a>
    <a href="/parte-grupo" class="administracion__enlaces">Formación</a>
<?php } else {
    // No se registran enlaces.
}
?>