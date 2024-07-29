# Modelado Base de Datos Servicios PONAL

## Listado Entidades

### funcionarios **(ED)**

- id **(PK)**
- nombre
- apellido
- email **(UQ)**
- password
- turno
- disponible
- usuario
- id_grado **(FK)**
- id_grupo **(FK)**
- estado

### grados **(EC)**

- id **(PK)**
- grado
- abreviacion

### grupos **(EC)**

- id **(PK)**
- grupo

### asignaciones_servicios **(EP)**

- id **(PK)**
- id_funcionario **(FK)**
- id_servicio **(FK)**
- fecha_servicio
- lugar
- semana
- observacion


### servicios **(EC)**

- id **(PK)**
- servicio

### novedades **(EC)**

- id **(PK)**
- novedad

### novedades_funcionarios **(EP)**
- id_funcionario **(PK)**
- id_novedad    **(FK)**
- observacion

## Relaciones

1. un **grado** tiene **funcionario** (_1 - M_)
1. un **grupo** contiene **funcionarios** (_1 - M_)
1. un **funcionario** realiza **asignacion** (_0 - M_)
1. un **servicio** asigna **asignacion** (_1 - M_)
1. un **funcionario** tiene **novedad_funcionario** (_1 - 1_)
1. las **novedades** vinculan **novedad_funcionario** (_1 - M_)


## Modelo Relacional

![alt text](ServiciosPonal.drawio.svg)


## Reglas de negocio

### funcionarios

1. Crear funcionario [Adm local - global]
1. Consultar funcionario (nombre) [Adm local - global]
1. Consultar todos funcionarios [Adm local - global]
1. Modificar funcionario (Solo admin ) [Adm global]
1. Asignar o modificar contraseña [Adm local - global]
1. Modificar estado - grupo [Adm global]
1. Si un usuario su estado es inactivo, no puede usarse a nivel global del sistema
1. Si la disponibilidad de un usuario es inactiva, no puede ser visible en los servicios
1. La categoria usuario permite el grado de accesibilidad al sistema

### Asignaciones_Servicios

1. Asignar servicios [usuario (respecto al grupo)- adm local - global]
1. Listar todos los servicios limite 50 registros [Todos *]
1. Listar servicios mas antiguos al mas reciente respecto por funcionario [Todos *]
1. Listar servicios por funcionario limite 50 registros [Todos *]
1. Listar servicios respecto a una fecha [adm global]
1. Modificar servicios menores a 72 horas [adm local - global]
1. Eliminar servicio [adm global]

### Novedades_funcionarios

1. Listar novedades [usuario - adm local - global]
1. Cambiar novedad respecto a un funcionario [usuario - adm local - global]
1. Cambiar turnos descanso o laboral por todo el grupo [usuario - adm local - global]
1. Calcular novedades
