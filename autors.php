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
    if(isset($_POST['borrar'])){
        $borrar = $_POST['borrar'];
        $queryElimina = "DELETE from AUTORS where 'ID_AUT'=$borrar";
        $cursor=$mysqli->$query($queryElimina) OR die($queryElimina);
    }
    if(isset($_POST['añadir'])){
        $añadir = $_POST['añadir'];
        $queryCrear = "UPDATE AUTORS SET ID_AUT = auto, NOM_AUT = ";
        $cursor=$mysqli->$query($queryCrear) OR die($queryCrear);
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

//avanzar y retroceder;
if(isset($_POST['mantener'])){
    $numero = $_POST['mantener'];
} else {
    $numero = 0;
}
if(isset($_POST['mantener2'])){
    $numeroPagina = $_POST['mantener2'];
} else {
    $numeroPagina = 1;
}

$limite = 20;
$guardarNumero = 0;
$sql1 = "SELECT COUNT(*) as cuenta FROM autors $cerca";
$cursor2 = $mysqli->query($sql1) or die($sql1);
if($row2 = $cursor2->fetch_assoc()){
    $guardar2 = $row2['cuenta'];
    $guardarNumero = ceil($guardar2 / $limite);
}
if(isset($_POST['avanzar'])){
    if(!($numeroPagina == $guardarNumero)){
    $numero = $numero + $limite;
    $numeroPagina = $numeroPagina +  1;
}
}
if(isset($_POST['retroceder'])){
    if($numeroPagina != 1){
    $numero = $numero - $limite;
    $numeroPagina = $numeroPagina - 1;
    }
}
if(isset($_POST['retrocederLimite'])){
    $numeroPagina = 1;
    $numero = 0;
}
if(isset($_POST['avanzarLimite'])){
    $cuentaSql = "SELECT COUNT(*) as cuenta FROM autors $cerca";
    $cursor2 = $mysqli->query($cuentaSql) or die($cuentaSql);
    if($row1 = $cursor2->fetch_assoc()){
        $guardar = $row1['cuenta'];
    }
    $numero = $guardar - $limite;
    $numeroPagina = ceil($guardar / $limite);
}


echo "Conexion realizada";
echo "<br>";
$mysqli->set_charset("utf8");
echo "<br/>";
echo "<form name='formulari' action='autors.php' method='post' style='text-align:center;  margin-left:auto;  margin-right:auto;'>";
echo "<h3>Introdueix un nom o codi per cercar</h3>";
echo "Nom o Codi: <input type='text' name='nom' value='$nom'/>";
echo "<input type='submit' name='buscador' value='CERCAR'/>";
echo "<br/>";
echo "<br/>";
echo "<input type='submit' name='nomASC' value='A--Z'/>";
echo "<input type='submit' name='nomDESC' value='Z--A'/>";
echo " ";
echo "<input type='submit' name='codiASC' value='0--9'/>";
echo "<input type='submit' name='codiDESC' value='9--0'/>";
echo "<br/>";
echo "<input type='submit' name='retrocederLimite' value='<<' />";
echo "<input type='submit' name='retroceder' value='<' />";
echo " ";
echo "<input type='submit' name='avanzar' value='>' />";
echo "<input type='submit' name='avanzarLimite' value='>>' />";
echo "<input type='hidden' name='mantener' value='$numero'/>";
echo "<input type='hidden' name='mantener2' value='$numeroPagina'/>";
echo "<input type='hidden' name='oculto' value='$orden'/>";
echo "</form>";

echo "$numeroPagina"."/ "."$guardarNumero <br/>";

//Query per ordenacio y cerca
$query = "SELECT ID_AUT, NOM_AUT FROM AUTORs $cerca";
$query .= "ORDER BY $orden LIMIT $numero,$limite";

//taula
echo "<table border='1' style='text-align:center;  margin-left:auto;  margin-right:auto;'>";
if ($cursor = $mysqli->query($query) or die($query)) {
    while ($row = $cursor->fetch_assoc()) {
        echo "<tr><td>" . $row["ID_AUT"] . "</td><td>" . $row["NOM_AUT"] . "</td>";
        echo "<td><button type='submit' form='formulari' name='crear' value='{$row["ID_AUT"]}'>
                Crear</button></td>";
        echo "<td><button type='submit' form='formulari' name='borrar' value='{$row["ID_AUT"]}'>
                Borrar</button></td></tr>";

    }
   
   
    echo "Afegir: <input type='text' form='formulari' name='nom' value=''/>";
    echo "<button type='submit' form='formulari' name='añadir' value='{$row["ID_AUT"]}'>
    Añadir</button>";
    echo "<br/>";
    $cursor->free();
}
$mysqli->close();

?>
 </body>
 </html>

