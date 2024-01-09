<?php
require_once('conf.php');
session_start();
if (isset($_REQUEST["punktid0"])) {
    global $yhendus;
    $kask = $yhendus->prepare("UPDATE tantsud set punktid=0 WHERE id=?");
    $kask->bind_param("i", $_REQUEST["punktid0"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
}
if (isset($_REQUEST["peitmine"])) {
    global $yhendus;
    $kask = $yhendus->prepare("UPDATE tantsud set avalik=0 WHERE id=?");
    $kask->bind_param("i", $_REQUEST["peitmine"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
}
if (isset($_REQUEST["naitmine"])) {
    global $yhendus;
    $kask = $yhendus->prepare("UPDATE tantsud set avalik=1 WHERE id=?");
    $kask->bind_param("i", $_REQUEST["naitmine"]);
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
?>
<!doctype html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Tantsud tähtedega_admin</title>
</head>
<body>
<p>
    <?php
    if(isset($_SESSION['kasutaja'])){
        ?>
        <h1 id="tere">Tere, <?="$_SESSION[kasutaja]"?></h1>
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
<nav>
    <ul>
        <li>
            <a href="adminLeht.php">Admin leht</a>
        </li>
        <li>
            <a href="haldusLeht.php">Haldus leht</a>
        </li>
    </ul>
</nav>
<table>
    <tr>
        <th>Tantsupaari nimi</th>
        <th>Punktid</th>
        <th>Päev</th>
        <th>Komentaarid</th>
        <th>Avalik</th>
        <th>Nuliks</th>
        <th>Peida</th>
        <th>Kustuta paar</th>
    </tr>
    <?php
    global $yhendus;
    $kask = $yhendus->prepare("SELECT id, tantsupaar, punktid, ava_paev, kommentaarid, avalik FROM tantsud");
    $kask->bind_result($id, $tantsupaar, $punktid, $ava_paev, $komment, $avalik);
    $kask->execute();
    while ($kask->fetch()) {
        $tekst = "Näita";
        $seisund = "naitmine";
        if ($avalik == 1) {
            $tekst = "Peida";
            $seisund = "peitmine";
        } else if ($avalik == 0) {
            $tekst = "Näita";
            $seisund = "naitmine";
        }
        echo "<tr>";
        $tantsupaar = htmlspecialchars($tantsupaar);
        echo "<td>" . $tantsupaar . "</td>";
        echo "<td>" . $punktid . "</td>";
        echo "<td>" . $ava_paev . "</td>";
        echo "<td>" . $komment . "</td>";
        echo "<td>" . $avalik . "</td>";
        echo "<td><a href='?punktid0=$id'>Punktid nulliks</a></td>";
        echo "<td><a href='?$seisund=$id'>$tekst</a></td>";
        echo "<td><a href='?kustutapaar=$id'>Kustuta paar</a></td>";
        echo "</tr>";
    }
    ?>
</table>
</body>
</html>


