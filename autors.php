<!DOCTYPE html>
 <html>
 <head>
 <meta charset='UTF-8'>
 <title>AUTORS</title>
 </head>
 <body>
 <?php
$mysqli = new mysqli("localhost", "root", "", "biblioteca");
// Comprobar conexion
if ($mysqli->connect_error) {
    die("Conexion fallida: " . $mysqli->connect_error);
}
echo "Conexion realizada";
echo "<br>";
$mysqli->set_charset("utf8");
echo "<table border = 1 px>";
$query = "SELECT ID_AUT, NOM_AUT FROM AUTORs ORDER BY NOM_AUT ASC LIMIT 0 , 20";
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

