<!DOCTYPE html>
<html lang="en">

<head>
    <?php session_start(); ?>
    <?php if (!isset($_SESSION['user']) || !isset($_SESSION['travelSelected'])) header("location: login.php") ?>
    <?php include_once('connection.php'); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Nuevo Gasto</title>
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="footer.css">
    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
        }

        header,
        footer {
            width: 100vw;
            padding: 0 !important;
        }

        .container {
            display: flex;
            flex-direction: row;
            justify-content: space-evenly;
            width: 80vw;
        }

        .container-spend,
        .container-advanced {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        form.form-new-spend,
        div.advanced-options {
            width: 20vw;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin: 1rem 0 1rem 0;
        }

        p.destiny {
            text-align: center;
            font-size: 1.5rem;
            margin: 0;
        }

        .form-group>input,
        .form-group>select {
            margin: 5px 0 0 0;
            padding: 0.7rem 1.5rem;
            font-size: 1.25rem;
            border-radius: 7px;
            color: #495057;
            border: 1px solid #ced4da;
            box-shadow: 0 0 5px #18c3d859;
        }

        .form-group>label {
            font-size: 1.2rem;
        }

        button {
            padding: 1rem 0.5rem;
            border: 0;
            background-color: #18c3f8;
            color: #ffffff;
            font-size: 1.25rem;
            border-radius: 7px;
            box-shadow: 0px 18px 40px -12px rgba(24, 195, 216, 0.35);
            cursor: pointer;
        }

        .individual-spend {
            transition: 1s;
            height: 0;
            max-height: 600px;
            /* background-color: cyan; */
            margin-top: 10px;
            overflow: auto;
        }

        .individual-spend>.header {
            background-color: #000;
            color: #fff;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .user {
            display: flex;
            flex-direction: row;
            justify-content: space-evenly;
            align-items: center;
            text-align: center;
            border-bottom: 1px solid black;
            background-color: aliceblue;
        }

        .user>:nth-child(1) {
            width: 55%;
        }

        .user>:nth-child(2) {
            width: 25%;
        }

        .user>:nth-child(3) {
            width: 20%;
            margin: 0;
        }

        .user input[type="text"] {
            width: 60px;
            text-align: center;
            padding: 5px;
            font-size: 1rem;
            border-radius: 7px;
            color: #495057;
            border: 1px solid #ced4da;
            box-shadow: 0 0 5px #18c3d859;
        }
    </style>
    <?php
    $travel = $bd->prepare("SELECT g.group_id, g.user_id, g.trip_id, u.name, t.name AS 'trip_name' FROM `Groups` g, Users u, Travels t WHERE g.trip_id=t.trip_id AND g.user_id=u.user_id AND g.trip_id=$_SESSION[travelSelected]");
    $travel->execute();

    $_SESSION['newSpend'] = [];
    $_SESSION['newSpend']['users'] = [];
    foreach ($travel as $user) {
        $_SESSION['newSpend']['groupId'] = $user['group_id'];
        $_SESSION['newSpend']['tripName'] = $user['trip_name'];
        array_push($_SESSION['newSpend']['users'], "$user[user_id] - $user[name]");
    }

    ?>
</head>

<body>
    <?php include_once("./header.php") ?>
    <div class="container">
        <div class="container-spend">
            <h1>AGREGAR UN NUEVO GASTO</h1>
            <p class="destiny">Destino: <b><?php echo $_SESSION['newSpend']['tripName'] ?></b></p>
            <form class="form-new-spend" action="#function.php" method="POST">
                <div class="form-group">
                    <label for="paid-by">Pagado por</label>
                    <select name="paid-by">
                        <?php
                        foreach ($_SESSION['newSpend']['users'] as $user_data) {
                            list($id, $user) = explode(" - ", $user_data);
                            echo "<option name='$id'>$user</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="total-expend">Importe</label>
                    <input type="text" name="total-expend" value="10" required>
                </div>
                <div class="form-group">
                    <button class="button-primary" type="submit">Guardar gasto</button>
                </div>
            </form>
        </div>
        <div class="container-advanced">
            <h1>OPCIONES AVANZADAS</h1>
            <div class="advanced-options">
                <button id="btn-advanced" style="width: 100%;" class="button-primary">Habilitar</button>
                <div class="individual-spend">
                </div>
            </div>
        </div>
    </div>
    <?php include_once("./footer.php") ?>
    <script>
        let numUsers = <?= sizeOf($_SESSION['newSpend']['users']) ?>;
        let btnAdvanced = document.getElementById("btn-advanced");
        let individualSpend = document.getElementsByClassName("individual-spend")[0];
        const userNames = document.getElementsByTagName("option");
        let totalExpend = document.getElementsByName("total-expend")[0];
        let boolAdvancedOption = false;
        let minValue = (numUsers < 10) ? "0.0" + numUsers : "0." + numUsers;
        let previousTotalExpend = totalExpend.value;

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

        function setAttributes(ele, attr) {
            Object.keys(attr).forEach(function(key) {
                ele.setAttribute(key, attr[key]);
            });
        }

        function insertAfter(newElement, element) {
            element.parentNode.insertBefore(newElement, element.nextElementSibling);
        }

        function isNumberKey(e) {
            var charCode = (e.which) ? e.which : e.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 46)
                return false;
            return true;
        }

        function checkTotalPrice(arrayPrices, inputPrice) {
            var array = [];
            /* function reduce: sum all the positions of the array */
            var totalPrice = arrayPrices.reduce(function(a, b) {
                return a + b;
            }, 0);
            var decimal = 0;
            if (totalPrice < inputPrice)
                decimal = (inputPrice - totalPrice).toFixed(2);
            for (let i = 0; i < numUsers; i++) {
                if (decimal > 0) {
                    array.push((parseFloat(arrayPrices[i]) + parseFloat("0.01")).toFixed(2));
                    decimal -= 0.01;
                } else array.push(arrayPrices[i]);
            }
            return array;
        }

        function changeInputUsers(newText) {
            if (boolAdvancedOption && totalExpend.value >= minValue) {
                let usersChecked = [];
                let arrayPercentages = [];
                let arrayNewPrices = [];
                let usersBox = document.getElementsByClassName("individual-spend")[0];
                let numOfUsersActivated = 0;

                for (const user of usersBox.children) {
                    if (user.className == "user" && user.lastElementChild.checked) {
                        usersChecked.push(user);
                        arrayPercentages.push(getPriceToPercentage(user.children[1].firstElementChild.value));
                    }
                }
                previousTotalExpend = totalExpend.value;
                for (var i = 0; i < usersChecked.length; i++)
                    arrayNewPrices.push(getPercentageToPrice(arrayPercentages[i], totalExpend.value));
                    
                let arrayOfCents = getCents(arrayNewPrices, arrayPercentages);

                for (var i = 0; i < usersChecked.length; i++)
                    arrayNewPrices[i] = roundToTwoDecimals(arrayNewPrices[i] + arrayOfCents[i], "up");

                arrayNewPrices = checkTotalPrice(arrayNewPrices, parseFloat(totalExpend.value));

                for (var i = 0; i < usersChecked.length; i++)
                    usersChecked[i].children[1].firstElementChild.value = arrayNewPrices[i];
            }
        }

        function roundToTwoDecimals(num, direction) {
            return (direction === "up") ? Math.ceil(num * 100) / 100 : Math.floor(num * 100) / 100;
        }


        function getCents(array, percentages) {
            /* function reduce: sum all the positions of the array */
            var actualTotalPriceInputs = array.reduce(function(a, b) {
                return a + b;
            }, 0);
            var arrayCents = [];
            for (const percentage of percentages)
                arrayCents.push(getPercentageToPrice(percentage, parseFloat(totalExpend.value) - actualTotalPriceInputs));
            return arrayCents;
        }

        function validateWithRegex(rgx, text) {
            var regex = new RegExp(rgx);
            return text.match(regex);
        }

        function getPriceToPercentage(price) {
            return roundToTwoDecimals(((price * 100) / previousTotalExpend) / 100);
        }

        function getPercentageToPrice(percentage, totalPrice) {
            return roundToTwoDecimals(roundToTwoDecimals(parseFloat(totalPrice)) * percentage);
        }

        totalExpend.oninput = () => {
            var text = totalExpend.value;
            if (text != "" || parseFloat(text) < minValue) {
                if (validateWithRegex(/^[0-9]*\.$/, text) || validateWithRegex(/^\s*-?\d+(\.\d{1,2})?\s*$/, text)) {
                    changeInputUsers(text);
                    return;
                }
                totalExpend.value = roundToTwoDecimals(parseFloat(text.replace(/^[1-9]{1,6}(\\.\\d{1,2})?$/)));
            } else totalExpend.value = minValue;
        };

        btnAdvanced.onclick = () => {
            var text = totalExpend.value;
            if (btnAdvanced.innerText === "Habilitar" && parseFloat(text) >= minValue) {
                boolAdvancedOption = true;
                var totalPrice = parseFloat(totalExpend.value);
                var arrayPrices = [];
                for (let i = 0; i < numUsers; i++) {
                    arrayPrices.push(roundToTwoDecimals(totalPrice / numUsers));
                }
                var arrayNewPrices = checkTotalPrice(arrayPrices, totalPrice);

                btnAdvanced.innerText = "Deshabilitar";

                var divHeader = createElement("div", null, individualSpend, null, {
                    class: "user header"
                });
                createElement("p", "Nombre", divHeader, null, {})
                createElement("p", "Precio", divHeader, null, {})
                createElement("p", "Aplicar", divHeader, null, {})

                for (let i = 0; i < userNames.length; i++) {
                    var divUser = createElement("div", null, individualSpend, null, {
                        class: "user"
                    });
                    createElement("p", userNames[i].innerText, divUser, null, {})
                    var divInput = createElement("div", null, divUser, null, {})
                    createElement("input", null, divInput, null, {
                        name: "price",
                        type: "text",
                        value: arrayNewPrices[i],
                        onkeypress: "return isNumberKey(event)"
                    })
                    createElement("input", null, divUser, null, {
                        type: "checkbox",
                        name: "apply",
                        checked: ""
                    })
                }
                individualSpend.style.height = (individualSpend.childElementCount * 52) + "px";
            } else {
                boolAdvancedOption = false;
                individualSpend.style.height = "0";
                btnAdvanced.innerText = "Habilitar";
                while (individualSpend.firstChild)
                    individualSpend.removeChild(individualSpend.firstChild);
            }
        }
    </script>
</body>

</html>