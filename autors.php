<!DOCTYPE html>
 <html>
 <head>
 <meta charset='UTF-8'>
 <title>AUTORS</title>
 <style>
    * {
        text-align:center;
    }
    img{
        margin: auto;
    }
 </style>
 </head>
 <body>
 <header>
 <img class='imagen' src='IesPau.png'><br/>
 </header>
 <?php

$mysqli = new mysqli("localhost", "root", "", "biblioteca");
// Comprobar conexion
if ($mysqli->connect_error) {
    die("Conexion fallida: " . $mysqli->connect_error);
}
echo "Conexion realizada";
echo "<br>";
$mysqli->set_charset("utf8");

$orden = "NOM_AUT ASC";



echo "<br/>";
echo "<form name='input' action='autors.php' method='post' style='text-align:center;  margin-left:auto;  margin-right:auto;'>";
echo "<input type='submit' name='nomASC' value='A--Z'>";
echo "<input type='submit' name='nomDESC' value='Z--A'>";
echo " ";
echo "<input type='submit' name='codiASC' value='0--9'>";
echo "<input type='submit' name='codiDESC' value='9--0'>";
echo "</form>";

if(isset($_POST['nomASC'])){
    $orden = "NOM_AUT ASC";
}
if(isset($_POST['nomDESC'])){
    $orden = "NOM_AUT DESC";
}
if(isset($_POST['codiASC'])){
    $orden = "ID_AUT ASC";
}
if(isset($_POST['codiDESC'])){
    $orden = "ID_AUT DESC";
}
$query = "SELECT ID_AUT, NOM_AUT FROM AUTORs ORDER BY $orden LIMIT 0,20";
echo "<table border='1' style='text-align:center;  margin-left:auto;  margin-right:auto;'>";
if ($cursor = $mysqli->query($query)) {
    while ($row = $cursor->fetch_assoc()) {
        echo "<tr><td>" . $row["ID_AUT"] . "</td><td>" . $row["NOM_AUT"] . "</td></tr>";
    }
    $cursor->free();
}
$mysqli->close();
?>
 </body>
 </html>

