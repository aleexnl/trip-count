<!DOCTYPE html>
<html>

<head>
	<?php session_start(); ?>
	<?php if (!isset($_SESSION['user'])) header("location: login.php") ?>
	<?php require_once('connection.php'); ?>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="home.css">
	<title>Home</title>
	<script src="https://kit.fontawesome.com/b17b075250.js" crossorigin="anonymous"></script>
	<style>
		body {
			font-family: 'Roboto', sans-serif;
			padding: 0;
			margin: 0;
		}

		th a {
			color: #fff;
		}

		tr {
			cursor: pointer;
		}

		p.title {
			text-align: center;
			font-size: 4em;
			font-weight: bold;
			margin: 2% 0 0 0;
		}

		div.container-messages {
			width: 100%;
			display: flex;
			flex-flow: column wrap;
			justify-content: center;
			white-space: pre-line;
		}

		div.container-messages div {
			width: 65%;
			margin: 5px auto;
			border-radius: 5px;
			text-align: center;
			padding: 10px 0;
			transition: 2s;
		}

		form.new-trip {
			display: flex;
			flex-direction: row;
			flex-wrap: wrap;
			justify-content: center;
			width: 35%;
			padding: 10px;
			margin: 0 auto 0 auto;
		}

		form.new-trip>div {
			width: 100%;
			display: flex;
			margin-bottom: 25px;
		}

		form.new-trip>div.coin {
			width: 100%;
			display: flex;
			margin-bottom: 0;
			place-content: center;
		}

		form.new-trip>div>label {
			font-size: 23px;
		}

		form.new-trip>label {
			font-size: 20px;
		}

		form.new-trip>div>label[for="nameTrip"] {
			width: 42%;
		}

		form.new-trip>div>label[for="descriptionTrip"] {
			width: 27%;
		}

		form.new-trip>div>input[type="text"] {
			width: -webkit-fill-available;
			width: -moz-available;
			background-color: #fff;
			border: 0;
			border-bottom: 2px solid #000;
			font-size: 19px;
			padding: 1px 5px 2px 5px;
		}

		select[name="coinTrip"] {
			margin-left: 15px;
			border: 0;
			font-size: 17px;
			background-color: #fff;
			border-bottom: 2px solid #000;
		}

		form.new-trip>div>input[type="text"]:focus,
		form.new-trip>div>input[type="text"]:hover,
		select[name="coinTrip"]:focus,
		select[name="coinTrip"]:hover {
			background-color: #0000001f;
			border-top-left-radius: 5px;
			border-top-right-radius: 5px;
			outline: none;
		}

		form.new-trip>p.author {
			width: 100%;
			font-size: 17px;
			text-align: center;
			margin-top: 25px;
		}

		form.new-trip>div.box-btn {
			width: 80%;
			margin-top: 20px;
			display: flex;
			justify-content: space-evenly;
		}

		button {
			font-size: 1.2em;
			background-color: #18c3f8;
			box-shadow: 0 0 4px #18c3f8;
			border: 2px solid #18c3f8;
			padding: 10px;
			border-radius: 5px;
			color: #f3f3f3;
			cursor: pointer;
		}

		button:focus {
			outline: none;
			border: 2px solid #549ab3;
		}

		.fa-v-align {
			vertical-align: text-bottom;
		}

		.msg-info {
			color: #fff;
			background-color: #2196F3;
			border: 1px solid #58748a;
			box-shadow: 0 0 5px #2196F3;
		}

		.msg-success {
			color: #fff;
			background-color: #4CAF50;
			border: 1px solid #39883c;
			box-shadow: 0 0 5px #4CAF50;
		}

		.msg-warning {
			color: #fff;
			background-color: #ff9800;
			border: 1px solid #ad7c33;
			box-shadow: 0 0 5px #ff9800;
		}

		.msg-error {
			color: #fff;
			background-color: #f44336;
			border: 1px solid #a0342c;
			box-shadow: 0 0 5px #f44336;
		}

		.text-center {
			text-align: center;
		}

		.hidden {
			display: none;
		}

		.bg-cornflowerblue {
			background-color: #2a76ff !important;
			color: white !important;
		}

		.travel-details {
			background-color: #e4f2ff;
			cursor: default;
			box-shadow: 0 0 2px 1px inset black;
		}

		.travel-details>td {
			padding: 0 10px;
			overflow: hidden;
			transition: 2s;
		}

		.box-details>p {
			font-size: 2.5em;
			text-align: center;
			margin: 0.5em 0 0.3em 0;
		}

		.box-details>div {
			display: flex;
			flex-direction: row;
			justify-content: space-evenly;
			margin-bottom: 10px;
		}

		.spend-details-list {
			margin: 20px auto;
		}
	</style>
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
			</tr>\n
			<tr class='travel-details'>\n
			<td colspan='5' class='hidden'></td>\n
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

<body>
	<?php require_once('header.php'); ?>
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
				</tr>
			</thead>
			<tbody name="main-travels">
				<?= $table_main_rows ?>
			</tbody>
		</table>
		<button class='add_travel button-primary'>Añadir Viaje <i class="fas fa-atlas fa-v-align"></i></button>
	</main>

	<?php
	require_once('footer.php');
	unset($queryTravels);
	unset($bd);
	?>
</body>
<script>
	<?= $json_with_travel_details ?>
	let newTrip = document.getElementsByClassName("add_travel")[0];
	let foreignExchange = ['AED', 'AFN', 'ALL', 'AMD', 'AOA', 'ARS', 'AUD', 'AZN', 'BAM', 'BBD', 'BDT', 'BGN', 'BHD', 'BIF', 'BND', 'BOB', 'BRL', 'BSD', 'BTN', 'BWP', 'BYN', 'BZD', 'CAD', 'CDF', 'CHF', 'CLP', 'CNY', 'COP', 'CRC', 'CUP', 'CVE', 'CZK', 'DJF', 'DKK', 'DOP', 'DZD', 'EGP', 'ERN', 'ETB', 'EUR', 'FJD', 'GBP', 'GEL', 'GHS', 'GMD', 'GNF', 'GTQ', 'GYD', 'HNL', 'HRK', 'HTG', 'HUF', 'IDR', 'ILS', 'INR', 'IQD', 'IRR', 'ISK', 'JMD', 'JOD', 'JPY', 'KES', 'KGS', 'KHR', 'KMF', 'KPW', 'KRW', 'KWD', 'KZT', 'LAK', 'LBP', 'LKR', 'LRD', 'LSL', 'LYD', 'MAD', 'MDL', 'MGA', 'MKD', 'MMK', 'MNT', 'MRO', 'MUR', 'MVR', 'MWK', 'MXN', 'MYR', 'MZN', 'NAD', 'NGN', 'NIO', 'NOK', 'NPR', 'NZD', 'OMR', 'PAB', 'PEN', 'PGK', 'PHP', 'PKR', 'PLN', 'PYG', 'QAR', 'RON', 'RSD', 'RUB', 'RWF', 'SAR', 'SBD', 'SCR', 'SDG', 'SEK', 'SGD', 'SLL', 'SOS', 'SRD', 'SSP', 'STD', 'SYP', 'SZL', 'THB', 'TJS', 'TMT', 'TND', 'TOP', 'TRY', 'TTD', 'TWD', 'TZS', 'UAH', 'UGX', 'USD', 'UYU', 'UZS', 'VEF', 'VND', 'VUV', 'WST', 'XAF', 'XCD', 'XOF', 'YER', 'ZAR', 'ZMW'];
	let lastElementCreated = null;


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
				var btnBalance = createElement("button", "Balance", divBtnDetails, null, {
					class: "button-primary"
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
	foreach ($_SESSION['msg'] as $msg)
		echo "generateMessages('$msg[0]', '$msg[1]', '$msg[2]', $msg[3]);";
	$_SESSION['msg'] = [];

	echo ("
	var tableRows = document.getElementsByName('main-travels')[0].children\n
	for (const row of tableRows)\n
		row.onclick = function() {\n
			showTravelDetails(travelDetails[row.id])\n
		}");
	?>
</script>

</html>