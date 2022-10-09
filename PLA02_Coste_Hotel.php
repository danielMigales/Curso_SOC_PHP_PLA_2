<?php

//print_r($_POST);

//variables
$numeroNoches = 0;
$ciudad = null;
$diasAlquilerCoche = 0;

$error = null;
const PRECIOXNOCHE = 60;
const PRECIOXDIA_COCHE = 40;
const DTO_ALQUILER_COCHE = 20;
const DTO_ALQUILER_COCHE_2 = 50;

$costeTotalHotel = 0;
$costeTotalAvion = 0;
$costeTotalCoche = 0;
$costeTotalViaje = 0;

//detecccion del submit
if (isset($_POST['enviar'])) {

	try {

		//recuperar numero de noches
		$numeroNoches = filter_input(INPUT_POST, 'noches', FILTER_VALIDATE_INT);

		//validar que sea mayor de 0
		if ($numeroNoches <= 0) {
			throw new Exception("Seleccione el numero de noches. Introduzca un numero mayor que 0.", 1);
		}

		//recuperar ciudad de destino
		if (!$ciudad = filter_input(INPUT_POST, 'ciudad')) {
			throw new Exception("Seleccione la ciudad de destino.", 2);
		}

		//recuperar dias alquiler coche
		$diasAlquilerCoche = filter_input(INPUT_POST, 'coche', FILTER_VALIDATE_INT);

		//validar que sea mayor de 0 y que no este vacio
		if (!$diasAlquilerCoche == null && $diasAlquilerCoche <= 0) {
			throw new Exception("Seleccione los dias de alquiler de vehiculo. Introduzca un numero mayor que 0.", 3);
		}

		//impedir que se ejecute el metodo calculo del coste de alquiler si los dias son 0
		$costeTotalCoche = $diasAlquilerCoche == null ? 0 : costeCoche($diasAlquilerCoche);

		//validar que no sea mayor que el numero de estancia
		if ($diasAlquilerCoche > $numeroNoches) {
			throw new Exception("Seleccione los dias de alquiler de vehiculo. No debe ser superior al numero de dias de estancia.", 4);
		}
	} catch (Exception $e) {
		$error = 'Error nº: ' . $e->getCode() . '. ' . $e->getMessage();
	}
}

//funcion coste hotel
function costeHotel($numeroNoches)
{
	return $numeroNoches * PRECIOXNOCHE;
}

//funcion coste avion
function costeAvion($ciudad)
{

	switch ($ciudad) {
		case ($ciudad == 'Madrid'):
			return $costeTotalAvion = 150;
			break;
		case ($ciudad == 'Paris'):
			return $costeTotalAvion = 250;
			break;
		case ($ciudad == 'Los Angeles'):
			return $costeTotalAvion = 450;
			break;
		case ($ciudad == 'Roma'):
			return $costeTotalAvion = 200;
			break;
		default:
			echo 'ciudad no valida';
			return $costeTotalAvion = 0;
	}
}

function costeCoche($diasAlquilerCoche)
{
	if ($diasAlquilerCoche >= 7) {
		$costeTotalCoche = ($diasAlquilerCoche * PRECIOXDIA_COCHE) - 50;
	} else if ($diasAlquilerCoche >= 3) {
		$costeTotalCoche = ($diasAlquilerCoche * PRECIOXDIA_COCHE) - 20;
	} else {
		$costeTotalCoche = ($diasAlquilerCoche * PRECIOXDIA_COCHE);
	}
	return $costeTotalCoche;
}

//funcion calculo total precio
function calculoTotalPrecioViaje($costeTotalHotel, $costeTotalAvion, $costeTotalCoche)
{
	$costeTotalViaje = $costeTotalHotel + $costeTotalAvion + $costeTotalCoche;
	return $costeTotalViaje;
}

//llamada al metodo de calculo de coste noches hotel
$costeTotalHotel = costeHotel($numeroNoches);

//llamada al metodo coste avion
$costeTotalAvion = costeAvion($ciudad);

//llamada al metodo de calculo de coste coche
$costeTotalCoche = costeCoche($diasAlquilerCoche);

//lamada al metodo de calculo total del viaje
$costeTotalViaje = calculoTotalPrecioViaje($costeTotalHotel, $costeTotalAvion, $costeTotalCoche);

?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<title>PLA02</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/estilos.css">
</head>

<body>
	<main>
		<h1 class='centrar'>PLA02: COSTE HOTEL</h1>
		<br>
		<form method="post" action="#">
			<div class="row mb-3">
				<label for="noches" class="col-sm-3 col-form-label">Número de noches:</label>
				<div class="col-sm-9">
					<input type="number" class="form-control" name="noches" id="noches" value='<?= $numeroNoches ?>'>
				</div>
			</div>
			<div class="row mb-3">
				<label for="ciudad" class="col-sm-3 col-form-label">Destino:</label>
				<div class="col-sm-9">
					<select class="form-select" name='ciudad'>
						<option selected value=''>Selecciona un destino</option>
						<option <?php if ($ciudad == 'Madrid') {
									echo 'selected';
								} ?>>Madrid</option>
						<option <?php if ($ciudad == 'Paris') {
									echo 'selected';
								} ?>>Paris</option>
						<option <?php if ($ciudad == 'Los Angeles') {
									echo 'selected';
								} ?>>Los Angeles</option>
						<option <?php if ($ciudad == 'Roma') {
									echo 'selected';
								} ?>>Roma</option>
					</select>
				</div>
			</div>
			<div class="row mb-3">
				<label for="coche" class="col-sm-3 col-form-label">Días alquiler coche:</label>
				<div class="col-sm-9">
					<input type="number" class="form-control" name="coche" id="coche" value='<?= $diasAlquilerCoche ?>'>
				</div>
			</div>
			<label class="col-sm-3 col-form-label"></label>
			<button type="submit" class="btn btn-primary" name='enviar'>Enviar datos</button>
			<br><br>
			<div class="row mb-3">
				<label class="col-sm-3 col-form-label">Coste total: </label>
				<div class="col-sm-9">
					<input type="text" class="form-control" name="total" id="total" disabled value='<?= $costeTotalViaje ?>'>
				</div>
			</div><br>
			<span class='errores'><?= $error ?></span>
		</form>
	</main>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>