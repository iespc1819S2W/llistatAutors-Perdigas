<!DOCTYPE html>
<html>
<head>
   <meta charset='UTF-8'>
   <title>AUTORS</title>
   <link rel="stylesheet" type="text/css" href="autors.css" />
</head>
<body>
   <header>
    <img style='width: 450px;' class='imagen' src='IesPau.png'/>
</header>
<?php
$mysqli = new mysqli("localhost", "root", "", "biblioteca");
// Comprobar conexion
if ($mysqli->connect_error) {
die("Conexion fallida: " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8");

//ordenacion
$orden = "ID_AUT ASC";
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
//borrar
$borrar = "";
if(isset($_POST['borrar'])){
$borrar = $_POST['borrar'];
$queryElimina = "DELETE from AUTORS where ID_AUT = $borrar";
$cursor=$mysqli->query($queryElimina) OR die($queryElimina);
}
//editar
$editar  = "";
if(isset($_POST['editar'])){
$editar = $_POST['editar'];
}
if(isset($_POST['guardar'])){
$editarAutor = $mysqli->real_escape_string($_POST['editarAutor']);
$paisos = ($_POST['paisos']);
$guardar = $mysqli->real_escape_string($_POST['guardar']);
if($paisos === ""){
$queryGuardar = "UPDATE AUTORS SET NOM_AUT ='$editarAutor', FK_NACIONALITAT = null where ID_AUT = $guardar";
}else{
$queryGuardar = "UPDATE AUTORS SET NOM_AUT ='$editarAutor', FK_NACIONALITAT = '$paisos' where ID_AUT = $guardar";  
}
$resultat = $mysqli->query($queryGuardar) or die($queryGuardar);
}

//Añadir autor
if(isset($_POST['añadir'])) {
$introdueix = $mysqli->real_escape_string($_POST['introduce']);
$paisos2 = ($_POST['paisos2']);
if($paisos2 === ""){
$sql = "INSERT INTO AUTORS(ID_AUT, NOM_AUT, FK_NACIONALITAT) values((SELECT max(ID_AUT)+1 from autors as TOTAL), '$introdueix', null)";
}else{
$sql = "INSERT INTO AUTORS(ID_AUT, NOM_AUT, FK_NACIONALITAT) values((SELECT max(ID_AUT)+1 from autors as TOTAL), '$introdueix', '$paisos2')";  
}
$cursor3 = $mysqli->query($sql) OR die("Error query" .$sql);
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

$limite = 10;
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
echo "<form name='formulari' id='formulari' action='' method='post'>";
   echo "<fieldset style='width:500px; margin-left:auto;  margin-right:auto;'><legend><b>Introdueix per cercar</b></legend>";
    echo "Nom o ID: <input type='text' name='nom' value='$nom'/>";
    echo "<input type='submit' name='buscador' value='CERCAR'/>";
   echo "</fieldset>";
    echo "<br/>";
    echo " ";
    echo "<br/>";
    echo "<input type='hidden' name='mantener' value='$numero'/>";
    echo "<input type='hidden' name='mantener2' value='$numeroPagina'/>";
    echo "<input type='hidden' name='oculto' value='$orden'/>";
echo "</form>";

//SELECT 
$defecte = "Tria una opció...";
function selector($mysqli, $nom, $defecte, $formulari, $null=true) {
    echo "<select form='$formulari' name='$nom'>";
    //null
    $vacio = '';
    if($null){
        echo "<option value=$vacio>$defecte</option>";
    }
    $querySelect = "SELECT NACIONALITAT FROM NACIONALITATS";
    $cursor = $mysqli->query($querySelect) or die($querySelect);
    while($row = $cursor->fetch_assoc()){
        echo '<option value="'.$row['NACIONALITAT'].'">'.$row['NACIONALITAT'].'</option>';       
    }
    echo "</select>";   
}

//Query per ordenacio y cerca
$query = "SELECT ID_AUT, NOM_AUT, FK_NACIONALITAT as NACIONALITAT FROM AUTORs $cerca";
$query .= "ORDER BY $orden LIMIT $numero,$limite";

//taula
echo "<table border='1' style='text-align:center; width:600px;  margin-left:auto;  margin-right:auto;'>";
    echo "<tr><th>ID<input type='submit' form='formulari' name='codiASC' value='&uarr;'/>
         <input type='submit' form='formulari' name='codiDESC' value='&darr;'/></th>

         <th>NOM AUTOR<input type='submit' form='formulari' name='nomASC' value='A-Z'/>
         <input type='submit' form='formulari' name='nomDESC' value='Z-A'/></th>

         <th>NACIONALITATS</th><th colspan='2'>OPCIONS</th>";
        if ($cursor = $mysqli->query($query) or die($query)) {
        while ($row = $cursor->fetch_assoc()) {
            if($editar == $row["ID_AUT"]){
                echo "<tr><td>" . $row["ID_AUT"] . "</td>";
                echo "<td><input type='text' name='editarAutor' value='{$row["NOM_AUT"]}' form='formulari'></td>";
                echo "<td>";
                echo selector($mysqli, 'paisos', $defecte, 'formulari');
                echo "</td>";
                echo "<td>";
                    echo "<button type='submit' form='formulari' name='guardar' value='{$row["ID_AUT"]}'>OK</button></td><td>
                    <button type='submit' form='formulari' name='Cancelar' value='{$row["ID_AUT"]}'>X </button></td>";
                }else{
                    echo "<tr><td>" . $row["ID_AUT"] . "</td><td>" . $row["NOM_AUT"] . "</td><td>" . $row["NACIONALITAT"] . "</td>";
                    echo "<td><button type='submit' form='formulari' name='editar' value='{$row["ID_AUT"]}'>Edita</button></td>";
                    echo "<td><button type='submit' form='formulari' name='borrar' value='{$row["ID_AUT"]}'>
                Borrar</button></td></tr>";
                }             
            }
        //botones mover + numero de pagina
        echo "</table>";   
        echo "<input type='submit' form='formulari' name='retrocederLimite' value='<<' />";
        echo "<input type='submit' form='formulari' name='retroceder' value='<' />";
        echo " ";
        echo "<input type='submit' form='formulari' name='avanzar' value='>' />";
        echo "<input type='submit' form='formulari' name='avanzarLimite' value='>>' />";
        echo "<br/>";
        echo "$numeroPagina"."/ "."$guardarNumero <br/>";
        echo "<br/>"; 
        $cursor->free();
    }
    //formulari afegir
    echo "<form action='' id='formulari2' name='formulari2' method='post' style='text-align:center;  margin-left:auto;  margin-right:auto;'>";
        echo "<fieldset style='width:550px; margin-left:auto;  margin-right:auto;'><legend><b>Afegeix un autor</b></legend>";
            echo "Nom: <input type='text' name='introduce' id='introduce'/>";
            echo " Nacionalitat: ";
            echo selector($mysqli, 'paisos2', $defecte, 'formulari2');
            echo "<button type='submit' name='añadir'>Afegeix</button>";
            echo "<br/>";
        echo "</fieldset>";
    echo "</form>";
    $mysqli->close();
    ?>
</body>
</html>

