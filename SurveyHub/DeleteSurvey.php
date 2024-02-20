<?php
    session_start();
    include("connessione.php");
    $idSondaggio=$_REQUEST['idSondaggio'];
    $sqlEliminaSondaggio="delete from survey where Id='$idSondaggio'";
    $risEliminaSondaggio=mysqli_query($link,$sqlEliminaSondaggio) or die ("ERROR!");   
    if($risEliminaSondaggio){
        if($_SESSION['tipologia']!='azienda'){
            header("location:UserArea.php");
        } else {
            header("location:CompanyArea.php");
        }
    }
?>