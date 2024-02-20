<?php
include("connessione.php");
    function checkUserType($email){
        global $link; 
        $sqlIsAdmin="Select Email_User from admin where Email_User = '$email'";
        $risIsAdmin=mysqli_query($link,$sqlIsAdmin);
        if (mysqli_num_rows($risIsAdmin)>0) {
            return 1;
        }

        $sqlIsPremium = "Select Email_User from premium where Email_User = '$email'";
        $risIsPremium=mysqli_query($link,$sqlIsPremium);
        if (mysqli_num_rows($risIsPremium)>0) {
            return 2;
        }

        return 3;
    }
?>
         