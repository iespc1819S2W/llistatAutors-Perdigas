<!DOCTYPE html>
 <html>
 <head>
 <meta charset='UTF-8'>
 <title>AUTORS</title>
 <link rel="stylesheet" type="text/css" href="autors.css" media="screen" />
 </head>
 <body>
 <header>
 <img class='imagen' src='IesPau.png'><br/>
 </header>
 <?php
   $orden = "NOM_AUT ASC";
   if(isset($_POST['oculto'])){
       $orden = $_POST['oculto'];
   }
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
$mysqli = new mysqli("localhost", "root", "", "biblioteca");
// Comprobar conexion
if ($mysqli->connect_error) {
    die("Conexion fallida: " . $mysqli->connect_error);
}

//Cerca
$cerca = "";
$nom = (isset($_POST['nom'])? $_POST['nom'] : '');
if($nom != ''){
 $cerca= "where ID_AUT = '$nom' OR NOM_AUT like '%$nom%' ";
}

echo "Conexion realizada";
echo "<br>";
$mysqli->set_charset("utf8");

echo "<br/>";
echo "<form name='input' action='autors.php' method='post' style='text-align:center;  margin-left:auto;  margin-right:auto;'>";
echo "<h1>Introdueix un nom o codi per cercar</h1>";
echo "Nom o Codi: <input type='text' name='nom' value=''/>";
echo "<input type='submit' name='buscador' value='CERCAR'/>";
echo "<br/>";
echo "<br/>";
echo "<input type='submit' name='nomASC' value='A--Z'/>";
echo "<input type='submit' name='nomDESC' value='Z--A'/>";
echo " ";
echo "<input type='submit' name='codiASC' value='0--9'/>";
echo "<input type='submit' name='codiDESC' value='9--0'/>";
echo "<input type='hidden' name='oculto' value='$orden'/>";
echo "</form>";

//Query per ordenacio y cerca
$query = "SELECT ID_AUT, NOM_AUT FROM AUTORs $cerca";
$query .= "ORDER BY $orden LIMIT 0,20";

//taula
echo "<table border='1' style='text-align:center;  margin-left:auto;  margin-right:auto;'>";
if ($cursor = $mysqli->query($query) or die($query)) {
    while ($row = $cursor->fetch_assoc()) {
        echo "<tr><td>" . $row["ID_AUT"] . "</td><td>" . $row["NOM_AUT"] . "</td></tr>";
    }
    $cursor->free();
}
$mysqli->close();

?>
 </body>
 </html>

