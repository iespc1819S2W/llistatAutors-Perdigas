<?php
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
?>