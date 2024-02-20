<?php
    include("connessione.php");
    $idDomanda=$_REQUEST['idDomanda'];
    $idSondaggio=$_REQUEST['idSondaggio'];
    $sqlEliminaDomanda="delete from composition where Id_Question='$idDomanda'";
    $risEliminaDomanda=mysqli_query($link,$sqlEliminaDomanda) or die ("ERRORE!");   
    if($risEliminaDomanda){header("location:Surveys.php?idSondaggio=$idSondaggio");}
?>