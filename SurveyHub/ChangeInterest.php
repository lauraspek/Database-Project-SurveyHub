<?php
    include("connessione.php");
    $keyword=$_REQUEST['keyword'];
    $email=$_REQUEST['email'];

    $sqlChecked="SELECT * FROM interest WHERE Email_User='$email' and Keyword_Domain='$keyword'";
    $risChecked=mysqli_query($link,$sqlChecked) or die ("Query fallita");   
    
    if(mysqli_num_rows($risChecked)>0){
        $sql="DELETE FROM interest WHERE Email_User='$email' and Keyword_Domain='$keyword'";
	    $ris= mysqli_query($link,$sql) or die ("Query fallita");
        if($ris){header("location:SurveyManage.php");}
    
    } else {
        $sql="INSERT INTO interest (Email_User,Keyword_Domain)
	        values
	        ('".$email."','".$keyword."');";
	    $ris= mysqli_query($link,$sql) or die ("Query fallita");
        if($ris){header("location:SurveyManage.php");}
    }
?>