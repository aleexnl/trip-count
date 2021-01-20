<!DOCTYPE html>
<html lang="en">

<head>
    <?php session_start(); ?>
    <?php if (!isset($_SESSION['user']) || !isset($_SESSION['travelSelected'])) header("location: pages/login.php") ?>
    <?php include_once('connection.php'); ?>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="images/logo.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Nuevo Gasto</title>
    <!-- <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="footer.css"> -->
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
            flex-direction: column;
            justify-content: space-evenly;
            width: 80vw;
        }

        .container-forms {
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

        div.container-messages {
            width: 100%;
            display: flex;
            flex-flow: column wrap;
            justify-content: center;
        }

        div.container-messages div {
            width: 65%;
            margin: 5px auto;
            border-radius: 5px;
            text-align: center;
            padding: 10px 0;
            opacity: 0;
            transition: 0.5s;
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

        /*** CUSTOM CHECKBOX ***/

        .checkbox {
            display: grid;
            grid-gap: 0.5em;
            font-size: 1.3rem;
            color: #000;
        }

        .checkbox-control {
            display: inline-grid;
            width: 1em;
            height: 1em;
            border-radius: 0.25em;
            border: 0.1em solid currentColor;
            box-sizing: border-box;
            cursor: pointer;
        }

        .checkbox-control svg {
            transition: transform 0.1s ease-in 25ms;
            transform: scale(0);
            transform-origin: bottom left;
            width: 17px;
            height: 17px;
        }

        .checkbox-control svg image {
            width: 17px;
            height: 17px;
        }


        .checkbox-input {
            display: grid;
            grid-template-areas: "checkbox";

        }

        .checkbox-input input {
            opacity: 0;
            width: 1em;
            height: 1em;
            display: none;
        }


        .checkbox-input input:focus+.checkbox-control {
            box-shadow: 0 0 0 0.05em #fff, 0 0 0.15em 0.1em currentColor;
        }

        .checkbox-input input:checked+.checkbox-control svg {
            transform: scale(1);
        }

        div.checkbox-container {
            display: grid;
            place-content: center;
            grid-gap: 2rem;
            line-height: 1;
        }


        #img_url {
            background: #ddd;
            width: 100px;
            height: 90px;
            display: block;
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
        <div class="container-messages">
        </div>
        <div class="container-forms">
            <div class="container-spend">
                <h1>AGREGAR UN NUEVO GASTO</h1>
                <p class="destiny">Destino: <b><?php echo $_SESSION['newSpend']['tripName'] ?></b></p>
                <form class="form-new-spend" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="paid-by">Pagado por</label>
                        <select name="paid-by">
                            <?php
                            foreach ($_SESSION['newSpend']['users'] as $user_data) {
                                list($id, $user) = explode(" - ", $user_data);
                                echo "<option value='$id'>$user</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="total-expend">Importe</label>
                        <input type="text" name="total-expend" value="10" required>
                    </div>
                    <div class="form-group">
                        <label for="file">Subir fotos</label>
                        <!-- CAMBIAR LOS ESTILOS (PREVIEW DE LA IMAGEN) -->
                        <!-- <img src="" id="img_url" alt="your image"> -->
                        <!-- <br> -->
                        <input type="file" name="files[]" id="img_file" multiple onChange="img_pathUrl(this);">
                        <!-- <input type="file" name="files[]" id="hidden">
                        <input type="file" name="files[]" id="hidden"-->
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
    </div>
    <?php include_once("./footer.php") ?>
    <script>
        let numUsers = <?= sizeOf($_SESSION['newSpend']['users']) ?>;
        let btnAdvanced = document.getElementById("btn-advanced");
        let formAddNewSpend = document.getElementsByClassName("form-new-spend")[0];
        let individualSpend = document.getElementsByClassName("individual-spend")[0];
        const userNames = document.getElementsByTagName("option");
        let totalExpend = document.getElementsByName("total-expend")[0];
        let boolAdvancedOption = false;
        let previousTotalExpend = totalExpend.value;

        function img_pathUrl(input) {
            let fileBuffer = Array.from(input.files);
            const validExt = ["jpg", "png", "jpeg"];

            // document.getElementById('img_url').src = (window.URL ? URL : webkitURL).createObjectURL(input.files[0]);
            for (let i = fileBuffer.length - 1; i >= 0; i--) {
                const file = input.files[i].name.split(".");
                let ext = file[file.length - 1];
                if (validExt.indexOf(ext) == -1)
                    fileBuffer.splice(i, 1);
            }

            const dT = new ClipboardEvent('').clipboardData || new DataTransfer();
            for (let file of fileBuffer)
                dT.items.add(file);
            input.files = dT.files;

            // console.log(input.files);
            // const image = (window.URL ? URL : webkitURL).createObjectURL(input.files[0]);
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

        function createSvgImage(parent) {
            const linkSvg = "http://www.w3.org/2000/svg";
            const xLink = "http://www.w3.org/1999/xlink";

            var svg = document.createElementNS(linkSvg, 'svg');
            svg.setAttributeNS(linkSvg, 'xlink', xLink);

            var image = document.createElementNS(linkSvg, 'image');
            image.setAttributeNS(xLink, 'href', 'images/checkbox.svg');

            svg.appendChild(image);
            parent.appendChild(svg);
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

        function setAttributes(ele, attr) {
            Object.keys(attr).forEach(function(key) {
                ele.setAttribute(key, attr[key]);
            });
        }

        function insertAfter(newElement, element) {
            element.parentNode.insertBefore(newElement, element.nextElementSibling);
        }

        function checkTotalPrice(arrayPrices, inputPrice) {
            var decimal = 0;
            /* function reduce: sum all the positions of the array */
            var totalPrice = arrayPrices.reduce(function(a, b) {
                return parseFloat(a) + parseFloat(b);
            }, 0);
            if (totalPrice < inputPrice)
                decimal = (inputPrice - totalPrice).toFixed(2);

            // console.log(totalPrice)
            let subtractDecimal = "";
            let num = totalPrice;
            let firstTime = true;
            while (true) {
                if (num % 1 == 0) {
                    subtractDecimal += "1";
                    break;
                } else {
                    num = (num * 10).toFixed(2);
                    if (firstTime) {
                        firstTime = false;
                        subtractDecimal = "0.";
                    } else subtractDecimal += "0";
                }
            }
            // console.log("checkTotalPrice", inputPrice, arrayPrices, subtractDecimal, decimal)
            let cont = 0;
            while (decimal > 0) {
                if (cont >= arrayPrices.length) cont = 0;
                arrayPrices[cont] = (parseFloat(arrayPrices[cont]) + parseFloat(subtractDecimal)).toFixed(2);
                decimal -= parseFloat(subtractDecimal);
                cont++;
                totalPrice = arrayPrices.reduce(function(a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0);
                // console.log("LOG TOTAL PRICE: ", totalPrice)
                if (totalPrice == inputPrice) break;
            }
            for (let i = 0; i < arrayPrices.length; i++) {
                arrayPrices[i] = parseFloat(arrayPrices[i]).toFixed(2);
            }
            return arrayPrices;
        }

        function changeInputUsers(newText) {
            if (boolAdvancedOption) {
                let usersChecked = [];
                let arrayPercentages = [];
                let arrayNewPrices = [];
                let numOfUsersActivated = 0;

                for (const user of individualSpend.children) {
                    var inputCheckbox = user.lastElementChild.getElementsByTagName("input")[0];
                    if (user.className == "user" && inputCheckbox.checked) {
                        usersChecked.push(user);
                        arrayPercentages.push(getPriceToPercentage(user.children[1].firstElementChild.value));
                    }
                }
                // console.log("percentage: ", arrayPercentages)
                for (var i = 0; i < usersChecked.length; i++)
                    arrayNewPrices.push(getPercentageToPrice(arrayPercentages[i], newText));

                // console.log("new prices", arrayNewPrices);
                let arrayOfCents = getCents(arrayNewPrices, arrayPercentages);
                // console.log("arrayOfCents", arrayOfCents)

                for (var i = 0; i < usersChecked.length; i++)
                    arrayNewPrices[i] = roundToTwoDecimals(arrayNewPrices[i] + arrayOfCents[i], "up");

                arrayNewPrices = checkTotalPrice(arrayNewPrices, parseFloat(newText));

                for (var i = 0; i < usersChecked.length; i++)
                    usersChecked[i].children[1].firstElementChild.value = arrayNewPrices[i];

                // var aaa = parseFloat(usersChecked[0].children[1].firstElementChild.value);
                // aaa += parseFloat(usersChecked[1].children[1].firstElementChild.value);
                // aaa += parseFloat(usersChecked[2].children[1].firstElementChild.value);
                // console.log("TOTAL FINAL: ", aaa);*/
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
            // console.log("actualTotalPriceInputs 2: ", array, percentages, actualTotalPriceInputs, previousTotalExpend, totalExpend.value, parseFloat(totalExpend.value) - actualTotalPriceInputs)
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
            // console.log("getPriceToPercentage", price, previousTotalExpend, ((price * 100) / previousTotalExpend) / 100)
            return roundToTwoDecimals(((price * 100) / previousTotalExpend) / 100);
        }

        function getPercentageToPrice(percentage, totalPrice) {
            // console.log("getPercentageToPrice", percentage, roundToTwoDecimals(parseFloat(totalPrice)))
            return roundToTwoDecimals(roundToTwoDecimals(parseFloat(totalPrice)) * percentage);
        }

        function redistributeUserPrice(input) {
            const id = input.id;
            const inputPrices = document.getElementsByName("price");
            const inputCheckbox = document.getElementsByName("apply");
            let numUsersActive = 0;
            let arrayPrices = [];
            let usersChecked = [];
            for (const cb of inputCheckbox)
                if (cb.checked) numUsersActive++;

            if (numUsersActive != 0) {
                var addPrice = roundToTwoDecimals(parseFloat(input.value) / numUsersActive);
                // console.log(addPrice, parseFloat(input.value) / numUsersActive)
                for (var i = 0; i < inputPrices.length; i++) {
                    if (inputPrices[i].id != id && inputCheckbox[i].checked) {
                        inputPrices[i].value = (parseFloat(inputPrices[i].value) + parseFloat(addPrice)).toFixed(2);
                        arrayPrices.push(inputPrices[i].value);
                        usersChecked.push(inputPrices[i]);
                    }
                }

                var arrayNewPrices = checkTotalPrice(arrayPrices, parseFloat(totalExpend.value));
                for (let i = 0; i < arrayNewPrices.length; i++)
                    usersChecked[i].value = arrayNewPrices[i];
            }
            input.value = 0;
        }

        function checkTotalInputPrice(id) {
            const inputUsers = document.getElementsByName("price");
            var sumTotal = 0;
            for (const user of inputUsers) {
                let inputCheckbox = user.parentElement.nextElementSibling.getElementsByTagName("input")[0];
                // console.log(inputCheckbox)
                if (user.id != id && inputCheckbox.checked)
                    sumTotal += (isNaN(parseFloat(user.value))) ? 0 : parseFloat(user.value);
            }
            sumTotal = sumTotal.toFixed(2);
            // console.log("sumTotal", sumTotal)
            return (parseFloat(totalExpend.value) - sumTotal).toFixed(2);
        }

        function checkInputListener(text) {
            // console.log(text, totalExpend.value, (text - parseFloat(totalExpend.value)))
            var numToSubtract = (text - parseFloat(totalExpend.value)).toFixed(2);
            return (text - numToSubtract).toFixed(2);
        }

        function singleUserInputListener(e) {
            var text = e.target.value;

            if (text != "") {
                if (validateWithRegex(/^[0-9]*\.$/, text) || validateWithRegex(/^\s*-?\d+(\.\d{1,2})?\s*$/, text)) {
                    if (parseFloat(text) > parseFloat(totalExpend.value)) {
                        text = checkInputListener(parseFloat(text));
                        text = checkTotalInputPrice(e.target.id);
                        // console.log(text)
                    } else {
                        const totalInputs = checkTotalInputPrice(e.target.id);
                        if (totalInputs < parseFloat(text)) text = totalInputs;
                    }
                } else {
                    text = roundToTwoDecimals(parseFloat(text.replace(/^[1-9]{1,6}(\\.\\d{1,2})?$/)));
                    if (isNaN(text)) text = "";
                }
            }
            e.target.value = text;
        }

        function userCheckboxListener(e) {
            var input = e.target;
            var parent = input.parentElement.parentElement.parentElement.parentElement;
            var inputPrice = parent.children[1].firstElementChild;
            if (input.checked) {
                parent.style.opacity = "1";
                parent.style.cursor = "default";
                inputPrice.style.cursor = "text";
                inputPrice.removeAttribute("disabled");
            } else {
                parent.style.opacity = "0.4";
                parent.style.cursor = "not-allowed";
                inputPrice.style.cursor = "not-allowed";
                inputPrice.setAttribute("disabled", "");

                if (inputPrice.value != "0")
                    redistributeUserPrice(inputPrice);
            }
        }

        totalExpend.oninput = () => {
            if (totalExpend.value == "") totalExpend.value = 1;
            var text = totalExpend.value;

            if (validateWithRegex(/^[0-9]*\.$/, text) || validateWithRegex(/^\s*-?\d+(\.\d{1,2})?\s*$/, text)) {
                if (parseFloat(text) < 1) {
                    totalExpend.value = 1;
                    text = 1;
                } else if (parseFloat(text) > 10000) {
                    totalExpend.value = 10000;
                    text = 10000;
                }
                changeInputUsers(text);
            } else {
                text = roundToTwoDecimals(parseFloat(text.replace(/^[1-9]{1,6}(\\.\\d{1,2})?$/)));
                totalExpend.value = (isNaN(text)) ? 1 : text;
            }

            previousTotalExpend = totalExpend.value;
        };

        btnAdvanced.onclick = () => {
            var text = totalExpend.value;
            if (parseFloat(text) >= 1 && parseFloat(text) <= 10000) {
                if (btnAdvanced.innerText === "Habilitar") {
                    generateMessages("info", "Opciones avanzadas habilitadas.", "container-messages", 2);
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
                            id: i,
                            name: "price",
                            type: "text",
                            value: arrayNewPrices[i],
                            oninput: "singleUserInputListener(event);"
                        })

                        var divCbContainer = createElement("div", null, divUser, null, {
                            class: "checkbox-container"
                        })
                        var labelCb = createElement("label", null, divCbContainer, null, {
                            class: "checkbox"
                        })
                        var spanInput = createElement("span", null, labelCb, null, {
                            class: "checkbox-input"
                        })
                        createElement("input", null, spanInput, null, {
                            type: "checkbox",
                            name: "apply",
                            checked: "",
                            onchange: "userCheckboxListener(event);"
                        })
                        var spanControl = createElement("span", null, spanInput, null, {
                            class: "checkbox-control"
                        })
                        createSvgImage(spanControl);
                    }

                    individualSpend.style.height = (individualSpend.childElementCount * 52) + "px";
                } else {
                    generateMessages("info", "Opciones avanzadas deshabilitadas.", "container-messages", 2);
                    boolAdvancedOption = false;
                    individualSpend.style.height = "0";
                    btnAdvanced.innerText = "Habilitar";
                    while (individualSpend.firstChild)
                        individualSpend.removeChild(individualSpend.firstChild);
                }
            } else generateMessages("error", "Valor invalido en el precio total del gasto.", "container-messages", 3);
        }

        formAddNewSpend.onsubmit = (e) => {
            e.preventDefault();
            var textMsg = ""

            var text = totalExpend.value;
            if (parseFloat(text) >= 1 && parseFloat(text) <= 10000) {
                if (boolAdvancedOption) {
                    var total = checkTotalInputPrice(null);
                    if (total == 0) {
                        var ids = [];
                        var prices = [];
                        let users = document.getElementsByName("price");
                        let checkbox = document.getElementsByName("apply");
                        if (checkbox.length > 0) {
                            for (let i = 0; i < users.length; i++) {
                                if (checkbox[i].checked && users[i].value > 0) {
                                    ids.push(users[i].id);
                                    prices.push(users[i].value);
                                }
                            }
                            generateMessages("success", "Los valores introducidos son correctos.", "container-messages", 1.5);
                            setTimeout(() => {
                                formAddNewSpend.setAttribute("action", `functions.php?action=new-spend-advanced&ids=${ids.join("-")}&prices=${prices.join("-")}`);
                                formAddNewSpend.submit();
                            }, 1600);
                        } else generateMessages("error", "No hay ningún usuario activo, desactiva las opciones avanzadas o habilita algún usuario.", "container-messages", 3);

                    } else if (total < 0) {
                        generateMessages("error", `El dinero total de los usuarios supera el total por: ${Math.abs(total)}`, "container-messages", 3);
                    } else {
                        generateMessages("error", `El dinero total de los usuarios no alcanza el total por: ${total}`, "container-messages", 3);
                    }
                } else {
                    generateMessages("success", "Los valores introducidos son correctos.", "container-messages", 1.5);
                    setTimeout(() => {
                        formAddNewSpend.setAttribute("action", "functions.php?action=new-spend");
                        formAddNewSpend.submit();
                    }, 1600);
                }
            } else generateMessages("error", "Valor invalido en el precio total del gasto.", "container-messages", 3);
        }
    </script>
</body>

</html>