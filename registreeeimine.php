<?php
require_once("conf2.php");
global $yhendus;
session_start();
if (isset($_POST["register"])) {
    $login = htmlspecialchars(trim($_POST['login']));
    $pass = htmlspecialchars(trim($_POST['pass']));

    $cool = "grave";
    $krypt = crypt($pass, $cool);

    global $yhendus;
    $kask = $yhendus->prepare("INSERT INTO kasutaja (kasutaja, parool) VALUES (?,?)");
    $kask->bind_param("ss", $login, $krypt);
    $success = $kask->execute();

    if ($success) {
        echo '<script>window.location.href ="haldusLeht.php";</script>';

        exit();
    } else {
        echo "Registreerimine ebaõnnestus. Palun proovige uuesti.";
    }

    $kask->close();

}

?>
<head>
    <link rel="stylesheet" type="text/css" href="formStyle.css">
</head>
<h1>Registreerimine</h1>
<form action="" method="post">
    <div id="registerModal" class="modal">
        <div class="modal-content">
            <form method="post" action="haldusLeht.php" onsubmit="return validateRegistration()">
                <table>
                    <tr>
                        <td>
                            <input type="text" id="login" name="login" required placeholder="login">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="password" id="pass" name="pass" required placeholder="password">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="password" id="confirmPass" name="confirmPass" required placeholder="re pass">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="submit" name="register" value="Register">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <br>
</form>
<script>
    function openRegisterModal() {

        document.getElementById('registerModal').style.display = 'block';
    }

    function closeRegisterModal() {
        document.getElementById('registerModal').style.display = 'none';
    }

    window.onclick = function (event) {
        var modal = document.getElementById('registerModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
    function validateRegistration() {
        var password = document.getElementById('pass').value;
        var confirmPass = document.getElementById('confirmPass').value;

// Check if passwords match
        if (password !== confirmPass) {
            alert('Paroolid ei vasta.');
            return false;
        }
        return true;
    }
</script>
