<!DOCTYPE html>
<html>

<head>
	<?php session_start(); ?>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="home.css">
	<title>Home</title>
	<script src="https://kit.fontawesome.com/b17b075250.js" crossorigin="anonymous"></script>
	<style>
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
		}

		form.new-trip {
			display: flex;
			flex-direction: row;
			flex-wrap: wrap;
			justify-content: center;
			width: 75%;
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

		form.new-trip>div.box-btn>button {
			font-size: 1.2em;
			background-color: #18c3f8;
			box-shadow: 0 0 4px #18c3f8;
			border: 2px solid #18c3f8;
			padding: 10px;
			border-radius: 5px;
			color: #f3f3f3;
			cursor: pointer;
		}

		form.new-trip>div.box-btn>button:focus {
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
	</style>
</head>

<body>

	<?php

	include_once 'header.php';


	if (!isset($_SESSION['order_creation']) && !isset($_SESSION['order_update'])) {
		$_SESSION['order_creation'] = "asc";
		$_SESSION['order_update'] = "asc";
		$buttoncreation = "▲";
		$buttonupdate = "▲";
	}

	if (isset($_POST['order_update'])) {
		if ($_POST['order_update'] == "asc") {
			$_SESSION['order_update'] = "desc";
			$buttonupdate = "▼";
		} elseif ($_POST['order_update'] == "desc") {
			$_SESSION['order_update'] = "asc";
			$buttonupdate = "▲";
		}
	}

	if (isset($_POST['order_creation'])) {
		if ($_POST['order_creation'] == "asc") {
			$_SESSION['order_creation'] = "desc";
			$buttoncreation = "▼";
		} elseif ($_POST['order_creation'] == "desc") {
			$_SESSION['order_creaton'] = "asc";
			$buttoncreation = "▲";
		}
	}


	include 'connection.php';



	$querytravels = $bd->prepare("SELECT * FROM Travels t where t.trip_id in (select g.trip_id from `Groups` g where g.user_id= :user_id ) order by 6 " . $_SESSION['order_creation'] . ", 7 " . $_SESSION['order_update'] . " ;");

	$querytravels->bindParam(':user_id', $_SESSION['$user_id']);

	$querytravels->execute();

	echo "<main>";

	echo "<table>";
	echo "<tr>";
	echo "<form action='home.php' method='post'><th colspan='4'>Ordenar por:</th><th><button type='submit' name='order_creation' class='order_creation'>Fecha de Creacion " . $buttoncreation . " </button></th><th><button type='submit' name='order_update' class='order_update'>Fecha de Modificacion " . $buttonupdate . "</button></th><form>";
	echo "</tr>";


	foreach ($querytravels as $rowtravel) {
		echo "<tr>";
		echo "<td>" . $rowtravel['destiny'] . "</td>";
		echo "<td>" . $rowtravel['coin'] . "</td>";
		echo "<td>" . $rowtravel['departure_date'] . "</td>";
		echo "<td>" . $rowtravel['return_date'] . "</td>";
		echo "<td>" . $rowtravel['creation_date'] . "</td>";
		echo "<td>" . $rowtravel['modify_date'] . "</td>";
		echo "</tr>";
	}

	echo "</table>";

	echo "<button class='add_travel'>Añadir Viaje</button>";

	echo "</main>";

	include_once 'footer.php';

	unset($querytravels);
	unset($bd);

	?>
</body>
<script>
	let newTrip = document.getElementsByClassName("add_travel")[0];
	let createTrip = "";
	let foreignExchange = ['AED', 'AFN', 'ALL', 'AMD', 'AOA', 'ARS', 'AUD', 'AZN', 'BAM', 'BBD', 'BDT', 'BGN', 'BHD', 'BIF', 'BND', 'BOB', 'BRL', 'BSD', 'BTN', 'BWP', 'BYN', 'BZD', 'CAD', 'CDF', 'CHF', 'CLP', 'CNY', 'COP', 'CRC', 'CUP', 'CVE', 'CZK', 'DJF', 'DKK', 'DOP', 'DZD', 'EGP', 'ERN', 'ETB', 'EUR', 'FJD', 'GBP', 'GEL', 'GHS', 'GMD', 'GNF', 'GTQ', 'GYD', 'HNL', 'HRK', 'HTG', 'HUF', 'IDR', 'ILS', 'INR', 'IQD', 'IRR', 'ISK', 'JMD', 'JOD', 'JPY', 'KES', 'KGS', 'KHR', 'KMF', 'KPW', 'KRW', 'KWD', 'KZT', 'LAK', 'LBP', 'LKR', 'LRD', 'LSL', 'LYD', 'MAD', 'MDL', 'MGA', 'MKD', 'MMK', 'MNT', 'MRO', 'MUR', 'MVR', 'MWK', 'MXN', 'MYR', 'MZN', 'NAD', 'NGN', 'NIO', 'NOK', 'NPR', 'NZD', 'OMR', 'PAB', 'PEN', 'PGK', 'PHP', 'PKR', 'PLN', 'PYG', 'QAR', 'RON', 'RSD', 'RUB', 'RWF', 'SAR', 'SBD', 'SCR', 'SDG', 'SEK', 'SGD', 'SLL', 'SOS', 'SRD', 'SSP', 'STD', 'SYP', 'SZL', 'THB', 'TJS', 'TMT', 'TND', 'TOP', 'TRY', 'TTD', 'TWD', 'TZS', 'UAH', 'UGX', 'USD', 'UYU', 'UZS', 'VEF', 'VND', 'VUV', 'WST', 'XAF', 'XCD', 'XOF', 'YER', 'ZAR', 'ZMW'];


	function generateMessages(type, text, parentName, seconds) {
		let parent = document.getElementsByClassName(parentName)[0];
		let msg = document.createElement("div");
		if (type == "info") msg.className = "msg-info";
		else if (type == "success") msg.className = "msg-success";
		else if (type == "error") msg.className = "msg-error";
		else if (type == "warning") msg.className = "msg-warning";
		msg.appendChild(document.createTextNode(text));
		parent.prepend(msg);
		countdown(parent, seconds);
	}

	function countdown(parent, seconds) {
		setTimeout(() => {
			parent.removeChild(parent.lastElementChild);
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

	function validateInputDate(input) {
		return Date.parse(input.value);
	}

	// IF date1 IS GREATER THAN date2, RETURN TRUE
	function compareDates(date1, date2) {
		return date1 > date2;
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

		// CHECK INPUT DEPARTURE DATE
		if (!validateInputDate(departureDate)) textError += "ERROR: Fecha de salida no valida.\n";
		else {
			if (!compareDates(new Date(departureDate.value).getTime(), new Date().getTime())) textError += "ERROR: La fecha de salida es mas pequeña que la fecha actual.\n";
		}

		// CHECK INPUT RETURN DATE
		if (!validateInputDate(returnDate)) textError += "ERROR: Fecha de regreso no valida.\n";
		else {
			if (!compareDates(new Date(returnDate.value).getTime(), new Date(departureDate.value).getTime())) textError += "ERROR: La fecha de regreso es mas pequeña que la fecha de salida.\n";
		}

		return textError;
	}

	newTrip.onclick = () => {
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
			let btnRedo = createElement("button", "Restablecer ", divBoxBtn, null, {
				class: "redo",
				type: "reset"
			})
			createElement("i", null, btnRedo, null, {
				class: "fas fa-redo-alt fa-v-align"
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
			createTrip.onclick = (e) => {
				e.preventDefault();
				let textError = validationCreateTrip();
				if (textError != "")
					generateMessages("error", textError, "container-messages", 7);
				else e.currentTarget.submit();
			}
		}
	}
</script>

</html>