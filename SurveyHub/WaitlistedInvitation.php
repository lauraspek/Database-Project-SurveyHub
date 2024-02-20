<?php
    include("connessione.php");
    $idInvitoAtteso=$_REQUEST['id'];
    $esito=$_REQUEST['esito'];
    
    if($esito=="accepted"){
        $sqlaggiuntaBonus="UPDATE user SET BonusTot = BonusTot + 0.5 WHERE Email = (SELECT Email_User FROM invitation WHERE Id='$idInvitoAtteso')";
        $risaggiuntaBonus=mysqli_query($link,$sqlaggiuntaBonus) or die ("Query fallita");
        
        $sql="UPDATE invitation SET Outcome='accepted' WHERE Id='$idInvitoAtteso'";
        $ris=mysqli_query($link,$sql) or die ("Query fallita");
        if($risaggiuntaBonus && $ris){header("location:InvitationManage.php");}
    }
        
    if($esito=="refused"){  
        $sql="UPDATE invitation SET Outcome='refused' WHERE Id='$idInvitoAtteso'";
        $ris=mysqli_query($link,$sql) or die ("Query fallita");
        if($ris){header("location:InvitationManage.php");}
    }
?>