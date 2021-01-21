<!DOCTYPE html>
<html>

<head>
	<?php session_start(); ?>
	<?php if (!isset($_SESSION['user'])) header("location: login.php") ?>
	<?php require_once('connection.php'); ?>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>Home</title>
	<link rel="shortcut icon" href="images/logo.ico">
	<script src="https://kit.fontawesome.com/b17b075250.js" crossorigin="anonymous"></script>
	<?php
	$userId = $_SESSION['user'][0];
	$sql = "";
	$creation_date_text = "Fecha de Creación ▲";
	$modify_date_text = "Fecha de Modificación ▲";
	$creation_href = "?col=creation_date&order=asc";
	$modify_href = "?col=modify_date&order=asc";

	if (
		isset($_GET['col'], $_GET['order']) &&
		($_GET['col'] == "creation_date" || $_GET['col'] == "modify_date") &&
		($_GET['order'] == "asc" || $_GET['order'] == "desc")
	) {
		if ($_GET['col'] == "creation_date" && $_GET['order'] == "asc") {
			$creation_href = "?col=creation_date&order=desc";
			$creation_date_text = "Fecha de Creación ▼";
		} else if ($_GET['col'] == "modify_date" && $_GET['order'] == "asc") {
			$modify_href = "?col=modify_date&order=desc";
			$modify_date_text = "Fecha de Modificación ▼";
		}
		$sql = "SELECT * FROM Travels t where t.trip_id in (select g.trip_id from `Groups` g where g.user_id = $userId) ORDER BY $_GET[col] $_GET[order];";
	} else {
		$sql = "SELECT * FROM Travels t where t.trip_id in (select g.trip_id from `Groups` g where g.user_id = $userId);";
	}

	$queryTravels = $bd->prepare($sql);
	$queryTravels->execute();

	$table_main_rows = "";
	$json_with_travel_details = "const travelDetails = {";
	$cont = 1;
	$array_index = [];
	foreach ($queryTravels as $travel) {
		$travel_details = $bd->prepare("SELECT u.name, ge.description, ge.price FROM Group_Expenses ge, Users u WHERE ge.group_id = (SELECT DISTINCT group_id FROM `Groups` WHERE trip_id = $travel[0]) AND ge.paid_by=u.user_id ORDER BY ge.date");
		$travel_details->execute();

		$json_with_travel_details .= "$travel[0]: { id: $travel[0], coin: '$travel[coin]',  creation_date: '$travel[creation_date]', expenses: [";
		$sum_total = '0.00';

		foreach ($travel_details as $details) {
			$sum_total = number_format(floatval($sum_total) + floatval($details[2]), 2);
			$json_with_travel_details .= "{ paid_by: '$details[0]', content: '$details[1]', amount: '$details[2]' }, ";
		}
		$json_with_travel_details .= "], total_amount: '$sum_total' }, ";

		$table_main_rows .= ("
			<tr id='$travel[0]'>\n
			<td>$travel[name]</td>\n
			<td>$travel[description]</td>\n
			<td>$travel[coin]</td>\n
			<td>$travel[creation_date]</td>\n
			<td>$travel[modify_date]</td>\n
			<td><button class='btn-edit-travel' onclick='editTravel($travel[0], event)'><i class='fas fa-pencil-alt'></i></button></td>\n
			</tr>\n
			<tr class='travel-details'>\n
			<td colspan='6' class='hidden'></td>\n
			</tr>
		");

		if ($cont % 2 == 1) array_push($array_index, $cont);
		$cont += 2;
	}
	$json_with_travel_details .= "};";

	$background_color_table = "";
	for ($i = 0; $i < sizeOf($array_index); $i++)
		if ($i % 2 == 1) $background_color_table .= "table tbody tr:nth-child($array_index[$i]) { background-color: #c9e3f9; }\n";
	?>
	<style>
		<?= $background_color_table ?>
	</style>
</head>

<body id="home">
	<?php include_once(__DIR__ . '/templates/header.html'); ?>
	<ul class="breadcrumb">
		<li><a href="#">Home</a></li>
	</ul>
	<main>
		<div class="container-messages">
		</div>
		<p class="title"><i class="far fa-calendar-alt"></i> Tus viajes</p>
		<table>
			<thead>
				<tr>
					<th>Nombre</th>
					<th>Descripción</th>
					<th>Moneda</th>
					<th><a class='order_creation' href="<?= $creation_href ?>"><?= $creation_date_text ?></a></th>
					<th><a class='order_update' href="<?= $modify_href ?>"><?= $modify_date_text ?></a></th>
					<th>Editar</th>
				</tr>
			</thead>
			<tbody name="main-travels">
				<?= $table_main_rows ?>
			</tbody>
		</table>
		<button class='add_travel button-primary'>Añadir Viaje <i class="fas fa-atlas fa-v-align"></i></button>
	</main>

	<?php
	include_once(__DIR__ . '/templates/footer.html');
	unset($queryTravels);
	unset($bd);
	?>
</body>
<script>
	<?= "$json_with_travel_details\n" ?>
	let newTrip = document.getElementsByClassName("add_travel")[0];
	let foreignExchange = ['AED', 'AFN', 'ALL', 'AMD', 'AOA', 'ARS', 'AUD', 'AZN', 'BAM', 'BBD', 'BDT', 'BGN', 'BHD', 'BIF', 'BND', 'BOB', 'BRL', 'BSD', 'BTN', 'BWP', 'BYN', 'BZD', 'CAD', 'CDF', 'CHF', 'CLP', 'CNY', 'COP', 'CRC', 'CUP', 'CVE', 'CZK', 'DJF', 'DKK', 'DOP', 'DZD', 'EGP', 'ERN', 'ETB', 'EUR', 'FJD', 'GBP', 'GEL', 'GHS', 'GMD', 'GNF', 'GTQ', 'GYD', 'HNL', 'HRK', 'HTG', 'HUF', 'IDR', 'ILS', 'INR', 'IQD', 'IRR', 'ISK', 'JMD', 'JOD', 'JPY', 'KES', 'KGS', 'KHR', 'KMF', 'KPW', 'KRW', 'KWD', 'KZT', 'LAK', 'LBP', 'LKR', 'LRD', 'LSL', 'LYD', 'MAD', 'MDL', 'MGA', 'MKD', 'MMK', 'MNT', 'MRO', 'MUR', 'MVR', 'MWK', 'MXN', 'MYR', 'MZN', 'NAD', 'NGN', 'NIO', 'NOK', 'NPR', 'NZD', 'OMR', 'PAB', 'PEN', 'PGK', 'PHP', 'PKR', 'PLN', 'PYG', 'QAR', 'RON', 'RSD', 'RUB', 'RWF', 'SAR', 'SBD', 'SCR', 'SDG', 'SEK', 'SGD', 'SLL', 'SOS', 'SRD', 'SSP', 'STD', 'SYP', 'SZL', 'THB', 'TJS', 'TMT', 'TND', 'TOP', 'TRY', 'TTD', 'TWD', 'TZS', 'UAH', 'UGX', 'USD', 'UYU', 'UZS', 'VEF', 'VND', 'VUV', 'WST', 'XAF', 'XCD', 'XOF', 'YER', 'ZAR', 'ZMW'];
	let lastElementCreated = null;

	function editTravel(id, event) {
		event.preventDefault();
		event.stopPropagation();
		window.location.href = `functions.php?action=edit-travel&group=${id}`;
	}

	function generateMessages(type, text, parentName, seconds) {
		let parent = document.getElementsByClassName(parentName)[0];
		let msg = document.createElement("div");
		if (type == "info") msg.className = "msg-info";
		else if (type == "success") msg.className = "msg-success";
		else if (type == "error") msg.className = "msg-error";
		else if (type == "warning") msg.className = "msg-warning";
		msg.appendChild(document.createTextNode(text));
		parent.prepend(msg);
		setTimeout(() => {
			parent.firstElementChild.style.opacity = "1";
		}, 1);
		countdown(parent, seconds);
	}

	function countdown(parent, seconds) {
		setTimeout(() => {
			parent.lastElementChild.style.opacity = "0";
			setTimeout(() => {
				parent.removeChild(parent.lastElementChild);
			}, 400);
		}, seconds * 1000);
	}

	function validateInputTextLength(name, size) {
		let input = document.getElementsByName(name)[0];
		if (input.value.length < size) return true;
		else return false;
	}

	function isNull(name) {
		return document.getElementsByName(name)[0].value.replace(/ /g, "");
	}

	function setAttributes(ele, attr) {
		Object.keys(attr).forEach(function(key) {
			ele.setAttribute(key, attr[key]);
		});
	}

	function createElement(tag, text, element, direction, attr) {
		let newElement = document.createElement(tag);
		if (text) {
			let textNode = document.createTextNode(text);
			newElement.appendChild(textNode);
		}
		if (attr) setAttributes(newElement, attr);
		if (direction == "after")
			insertAfter(newElement, element);
		else if (direction == "before")
			element.parentNode.insertBefore(newElement, element.nextElementSibling);
		else if (direction == "prepend")
			element.prepend(newElement);
		else
			element.appendChild(newElement);
		return newElement;
	}

	function insertAfter(newElement, element) {
		element.parentNode.insertBefore(newElement, element.nextElementSibling);
	}

	function validationCreateTrip() {
		let textError = "";

		// CHECK INPUT NAME
		if (!isNull("nameTrip")) textError += "ERROR: El nombre está vacio.\n\r";
		else {
			if (!validateInputTextLength("nameTrip", 50)) textError += "ERROR: El nombre tiene una logitud superior a 50 caracteres.\n";
		}

		// CHECK INPUT DESCRIPTION
		if (!isNull("descriptionTrip")) textError += "ERROR: La descripción está vacia.\n";
		else {
			if (!validateInputTextLength("descriptionTrip", 255)) textError += "ERROR: La descripción tiene una logitud superior a 255 caracteres.\n";
		}

		return textError;
	}

	newTrip.onclick = (e) => {
		e.preventDefault();
		if (document.getElementsByClassName("new-trip").length == 0) {
			let form = createElement("form", null, newTrip, "after", {
				class: "new-trip",
				action: "functions.php",
				method: "post"
			})
			let divName = createElement("div", null, form, null, {
				class: "name"
			})
			createElement("label", "Nombre del viaje: ", divName, null, {
				for: "nameTrip"
			})
			createElement("input", null, divName, null, {
				type: "text",
				name: "nameTrip"
			})
			let divDescription = createElement("div", null, form, null, {
				class: "description"
			})
			createElement("label", "Descripción: ", divDescription, null, {
				for: "descriptionTrip"
			})
			createElement("input", null, divDescription, null, {
				type: "text",
				name: "descriptionTrip"
			})
			let divCoin = createElement("div", null, form, null, {
				class: "coin"
			})
			createElement("label", "Moneda que se utilizará: ", divCoin, null, {
				for: "coinTrip"
			})
			let sectionsCoin = createElement("select", null, divCoin, null, {
				name: "coinTrip"
			})
			for (const coin of foreignExchange) {
				if (coin == "EUR")
					createElement("option", coin, sectionsCoin, null, {
						value: coin,
						selected: ""
					})
				else
					createElement("option", coin, sectionsCoin, null, {
						value: coin
					})
			}
			let divBoxBtn = createElement("div", null, form, null, {
				class: "box-btn"
			})
			let btnCreateTrip = createElement("button", "Crear Viaje ", divBoxBtn, null, {
				class: "redo"
			})
			createElement("i", null, btnCreateTrip, null, {
				class: "fas fa-plane fa-v-align"
			})
			let pTitle = createElement("p", " Nuevo Viaje ", newTrip, "after", {
				class: "title"
			})
			createElement("i", null, pTitle, "prepend", {
				class: "fas fa-globe-americas"
			})
			createElement("i", null, pTitle, null, {
				class: "fas fa-globe-europe"
			})
			createElement("div", null, newTrip, "after", {
				class: "container-messages"
			})
			form.onsubmit = (e) => {
				e.preventDefault();
				let textError = validationCreateTrip();
				if (textError != "")
					generateMessages("error", textError, "container-messages", 7);
				else e.currentTarget.submit();
			}
		}
	}

	let previousID = null;

	function showTravelDetails(details) {
		if (details != undefined) {
			console.log(details);
			const tdContainer = document.getElementById(details.id).nextElementSibling.children[0];
			if (tdContainer.children[0] == undefined) {
				if (previousID != null) {
					let previousElementSelected = document.getElementById(previousID).nextElementSibling.children[0];
					previousElementSelected.removeChild(previousElementSelected.children[0]);
					previousElementSelected.className = "hidden";
					document.getElementById(previousID).className = "";
				}

				document.getElementById(details.id).nextElementSibling.children[0].className = "";
				document.getElementById(details.id).className = "bg-cornflowerblue";

				var divBox = createElement("div", null, tdContainer, null, {
					class: "box-details"
				})
				createElement("p", "Detalles del viaje", divBox, null, {})
				var divGeneralDetails = createElement("div", null, divBox, null, {
					class: "general-details"
				})
				createElement("p", `Fecha de salida: ${details.creation_date}`, divGeneralDetails, null, {})
				createElement("p", `Total gastado: ${details.total_amount} ${details.coin}`, divGeneralDetails, null, {})

				var divBtnDetails = createElement("div", null, divBox, null, {
					class: "button-details"
				})
				var btnNewSpend = createElement("button", "Agregar gasto ", divBtnDetails, null, {
					class: "button-primary",
					onclick: `window.location.href = 'functions.php?action=new-spend&id=${details.id}'`
				})
				createElement("i", null, btnNewSpend, null, {
					class: "fas fa-comment-dollar fa-v-align"
				})
				var btnBalance = createElement("button", "Balance ", divBtnDetails, null, {
					class: "button-primary",
					onclick: `window.location.href = 'functions.php?action=balance&id=${details.id}'`
				})
				createElement("i", null, btnBalance, null, {
					class: "fas fa-balance-scale-right fa-v-align"
				})
				createElement("button", "Gestionar usuarios", divBtnDetails, null, {
					class: "button-primary"
				})
				var tableList = createElement("table", null, divBox, null, {
					class: "spend-details-list"
				})
				var tableHead = createElement("thead", null, tableList, null, {})
				createElement("th", "Pagado por", tableHead, null, {})
				createElement("th", "Concepto", tableHead, null, {})
				createElement("th", "Gasto", tableHead, null, {})
				var tableBody = createElement("tbody", null, tableList, null, {})
				if (details.expenses.length > 0) {
					for (const spend of details.expenses) {
						var tr = createElement("tr", null, tableBody, null, {})
						createElement("td", spend.paid_by, tr, null, {})
						createElement("td", spend.content, tr, null, {})
						createElement("td", `${spend.amount} ${details.coin}`, tr, null, {})
					}
				} else
					createElement("td", "No hay ningún gasto registrado", tableBody, null, {
						colspan: 3,
						class: "text-center"
					})

				previousID = details.id;
			} else {
				if (previousID == details.id) {
					let previousElementSelected = document.getElementById(previousID).nextElementSibling.children[0];
					previousElementSelected.removeChild(previousElementSelected.children[0]);
					previousElementSelected.className = "hidden";
					document.getElementById(previousID).className = "";
					previousID = null;
				}
			}
		}
	}
</script>

<script>
	<?php
	if (isset($_SESSION['msg'])) {
		foreach ($_SESSION['msg'] as $msg)
			echo "generateMessages('$msg[0]', '$msg[1]', '$msg[2]', $msg[3]);\n";
		$_SESSION['msg'] = [];
	}

	echo ("var tableRows = document.getElementsByName('main-travels')[0].children;
	for (const row of tableRows) {
		row.onclick = function() {
			showTravelDetails(travelDetails[row.id])
		}
	}\n");
	?>
</script>

</html>