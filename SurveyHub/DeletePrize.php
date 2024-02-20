<?php
    include("connessione.php");
    $nomePremio=$_REQUEST['nomePremio'];
    $sqlEliminaPremio="delete from prize where Name='$nomePremio'";
    $risEliminaPremio=mysqli_query($link,$sqlEliminaPremio) or die ("ERROR!");   
    if($risEliminaPremio){header("location:Prizes.php");}
?>