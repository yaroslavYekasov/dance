<?php
require_once('conf.php');
session_start();
//punktide lisamine
if (isset($_REQUEST["heatants"])) {
    global $yhendus;
    $kask = $yhendus->prepare("UPDATE tantsud set punktid=punktid+1 WHERE id=?");
    $kask->bind_param("i", $_REQUEST["heatants"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
}
if (isset($_REQUEST["halbtants"])) {
    global $yhendus;
    $kask = $yhendus->prepare("UPDATE tantsud set punktid=punktid-1 WHERE id=?");
    $kask->bind_param("i", $_REQUEST["halbtants"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
}
if (isset($_REQUEST["paarinimi"]) && !empty($_REQUEST["paarinimi"])) {
    global $yhendus;
    $kask = $yhendus->prepare("INSERT INTO tantsud (tantsupaar, ava_paev) VALUES(?, NOW())");
    $kask->bind_param("s", $_REQUEST["paarinimi"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
}

if (isset($_REQUEST["kustutapaar"])) {
    global $yhendus;
    $kask = $yhendus->prepare("DELETE FROM tantsud WHERE id=?");
    $kask->bind_param("i", $_REQUEST["kustutapaar"]);
    $kask->execute();
}
function isAdmin()
{
    return isset($_SESSION['onAdmin']) && $_SESSION['onAdmin'];
}

?>
<!doctype html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Tantsud tähtedega</title>
</head>
<body>
<p>
    <?php
    if (isset($_SESSION['kasutaja'])) {
        ?>
        <h1 id="tere">Tere, <?= "$_SESSION[kasutaja]" ?></h1>
        <a href="logout.php">Logi välja</a>
        <?php
    } else {
        ?>
        <a href="login.php">Logi sisse</a>
        <?php
    }
    ?>
</p>
<h1>Tantsud tähtedega</h1>
<?php if (isAdmin()) { ?>
<nav>
    <ul>
        <li>
            <a href="adminLeht.php">Admin leht</a>
        </li>
        <li>
            <a href="haldusLeht.php">Kasutaja leht</a>
        </li>
    </ul>
</nav>
<?php } ?>
<?php if(isset($_SESSION["kasutaja"])) { ?>
<table>
    <tr>
        <th>Tantsupaari nimi</th>
        <th>Punktid</th>
        <th>Paev</th>
        <?php if (!isAdmin()) { ?>
            <th>Lisa 1</th>
            <th>Emalda 1</th>
        <?php } ?>
        <th>Kustuta</th>
    </tr>
    <?php
    global $yhendus;
    $kask = $yhendus->prepare("SELECT id, tantsupaar, punktid, ava_paev FROM tantsud WHERE avalik=1");
    $kask->bind_result($id, $tantsupaar, $punktid, $ava_paev);
    $kask->execute();
    while ($kask->fetch()) {
        echo "<tr>";
        $tantsupaar = htmlspecialchars($tantsupaar);
        echo "<td>" . $tantsupaar . "</td>";
        echo "<td>" . $punktid . "</td>";
        echo "<td>" . $ava_paev . "</td>";
        if (isAdmin()) {

        } else if (!isAdmin()) {
            echo "<td><a href='?heatants=$id'>Lisa 1 punkt</a></td>";
            echo "<td><a href='?halbtants=$id'>Emalda 1 punkt</a></td>";
        }
        echo "<td><a href='?kustutapaar=$id'>Kustuta paar</a></td>";
        echo "</tr>";
    }
    ?>
    <?php if (!isAdmin()) { ?>
        <form action="?">
            <label for="paarinimi">Lisa uus paar </label>
            <input type="text" name="paarinimi" id="paarinimi">
            </br>
            <input type="submit" value="Lisa">
        </form>
    <?php } ?>
</table>
<?php } ?>
</body>
</html>


