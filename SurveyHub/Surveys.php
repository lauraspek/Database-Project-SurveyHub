<!DOCTYPE html>
<?php
    session_start();
    include("connessione.php");
    if (isset($_SESSION['Email']) && isset($_SESSION['Password'])){
        //verifico se esistono nel database
        $email=$_SESSION['Email'];
        $password=$_SESSION['Password'];
        $user_type=$_SESSION['tipologia'];
        $idSondaggio=$_REQUEST['idSondaggio'];
    

        $sqlControllo1="Select Email, Password from user WHERE ";
        $sqlControllo1.="Email='".$email."' and Password='".$password."';";
        $risControllo1=mysqli_query($link,$sqlControllo1);

        $sqlControllo2="Select * from invitation WHERE ";
        $sqlControllo2.="Email_User='".$email."' and Outcome='accepted' and Id_Survey='".$idSondaggio."';";
        $risControllo2=mysqli_query($link,$sqlControllo2);

        if ((mysqli_num_rows($risControllo1) == 0 || (mysqli_num_rows($risControllo2) == 0 && $user_type != 'premium')) && $user_type != 'azienda') {
            
            // Debug: Stampa le variabili o condizioni rilevanti per identificare il problema.
            // var_dump($user_type, mysqli_num_rows($risControllo1), mysqli_num_rows($risControllo2));
            $_SESSION = array();
            session_destroy();
            header("Location: index.php");
        } else {
?>

        <html>
            <head>
                <meta charset="utf-8">
                <title>SURVEYHUB</title>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
                <style>
                .survey-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 20px 0;
                    background-color: #fff;
                    border: 2px solid #007bff;
                }

                .survey-table th,
                .survey-table td {
                    padding: 10px;
                    text-align: center;
                    border: 1px solid #ddd;
                }

                .survey-table th {
                    background-color: #007bff;
                    color: #fff;
                }
                .survey-table tr:hover {
                    background-color: #f5f5f5;
                }

                nav {
                    background-color: #007bff;
                    padding: 10px;
                }

                nav ul.navbar {
                    list-style: none;
                    padding: 0;
                    margin: 0;
                    display: flex;
                    justify-content: space-around;
                }

                nav .navbar li {
                    margin-right: 10px;
                }

                nav .navbar a {
                    text-decoration: none;
                    color: #fff;
                }

                .page-container {
                    max-width: 1200px;
                    margin: 0 auto;
                    padding: 20px;
                }

                .small-button {
                    padding: 10px 5px;
                    font-size: 12px;
                    color: #007bff;
                    width: 150px;
                }
            </style>
            </head>
            
            <body class="page-container">
                <nav>
                    <ul class="navbar">
                        <li><a href="SurveyManage.php">Surveys / Domains</a></li>
                        <li><a href="UsersRanking.php">See users ranking</a></li>
                        <li><a href="Prizes.php">Prizes</a></li>
                        <?php
                        if($user_type=='azienda'){
                            ?><li><a href="CompanyArea.php">Go back to your personal company page</a></li><?php
                        } else {
                            ?><li><a href="UserArea.php">Go back to your personal user page</a></li>
                            <li><a href="InvitationManage.php">Invitations</a></li><?php
                        }
                        ?>
                        <li><a href="index.php">Logout</a></li>
                    </ul>
                </nav>
                
                <form method="post">
                <?php
                    $sqlInfoSondaggio="SELECT * FROM survey WHERE Id='$idSondaggio'";
                    $risInfoSondaggio=mysqli_query($link,$sqlInfoSondaggio);
                    $rigaInfo=mysqli_fetch_array($risInfoSondaggio)
                ?>
                    <h2>Survey about: <i><?php echo $rigaInfo['Title']; ?></i></h2>
                <?php
                    $sqlDomanda="SELECT * FROM question as q, composition as c WHERE q.Id=c.Id_Question AND Id_Survey='$idSondaggio'";
                    $risDomanda=mysqli_query($link,$sqlDomanda) or die (mysqli_error($link));
                    $contaDomande=1;

                    while($rigaDomanda=mysqli_fetch_array($risDomanda)){
                        $idDomanda=$rigaDomanda['Id'];
                        $sqlTipologiaDomanda="SELECT * FROM open_question WHERE Id_Question='$idDomanda'";
                        $risTipologiaDomanda=mysqli_query($link,$sqlTipologiaDomanda) or die (mysqli_error($link));
                        if(mysqli_num_rows($risTipologiaDomanda)>0){
                            
                            //OPEN QUESTION
                            echo "QUESTION ".$contaDomande.")";
                            echo "&nbsp &nbsp &nbsp;";
                            if($rigaDomanda['Score']==1){
                                echo $rigaDomanda['Score']." Point";
                            } else {
                                echo $rigaDomanda['Score']." Points";
                            }
                            $rigaDomandaAperta=mysqli_fetch_array($risTipologiaDomanda);
                            $maxCaratteri=$rigaDomandaAperta['MaxChar'];
                            ?>
                            <table>
                                <tr>
                                    <td>
                                        <?php echo $rigaDomanda['Text']; ?>
                                    </td>
                                    <td>
                                        <?php
                                        if(!empty($rigaDomanda['Photo'])){
                                        ?>
                                            <img width="100" src="data:image/jpeg;base64,<?php echo base64_encode($rigaDomanda['Photo']); ?>"/>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $sqlVisualizzaRisposta="SELECT Text FROM open_answer WHERE Id_OpenQuestion='$idDomanda' AND Email_User='$email'";
                                        $risVisualizzaRisposta=mysqli_query($link,$sqlVisualizzaRisposta) or die (mysqli_error($link));
                                        ?>
                                        
                                        
                                        <textarea name="<?php echo $idDomanda; ?>" rows="3" cols="35" maxlength="<?php echo $maxCaratteri; ?>"><?php if($testo=mysqli_fetch_array($risVisualizzaRisposta)){ echo $testo['Testo'];} ?></textarea> 
                                    </td>
                                    
                                    <?php
                                    if($rigaInfo['Email_PremiumUser']==$email || $rigaInfo['Email_Company']==$email){
                                        ?>
                                        <td>&nbsp;</td> <!-- per spaziatura -->
                                        <td><a href="<?php echo 'DeleteQuestion.php?idSondaggio='.$idSondaggio.'&idDomanda='.$idDomanda;?>"><input type="button" class="small-button" value="DELETE"/></a></td>
                                        <?php
                                     }
                                     if($user_type=='premium'){
                                        ?>
                                        
                                        <td><a href="<?php echo 'ViewAnswers.php?idSondaggio='.$idSondaggio.'&idDomanda='.$idDomanda;?>"><input type="button" class="small-button" value="VIEW ANSWERS"/></a></td>
                                        <?php
                                    }
                                    ?>
                                </tr>
                            </table>
                        <?php
                        } else {

                            //CLOSED QUESTION
                            echo "QUESTION ".$contaDomande.")";
                            echo "&nbsp &nbsp &nbsp;";
                            if($rigaDomanda['Score']==1){
                                echo $rigaDomanda['Score']." Point";
                            } else {
                                echo $rigaDomanda['Score']." Points";
                            }
                            $sqlOpzioni="SELECT * FROM options WHERE Id_ClosedQuestion='$idDomanda' ORDER BY Id";
                            $risOpzioni=mysqli_query($link,$sqlOpzioni) or die (mysqli_error($link));
                            ?>
                            <table>
                                <tr>
                                    <td>
                                        <?php echo $rigaDomanda['Text']; ?>
                                    </td>
                                    <td>
                                    <?php
                                        if(!empty($rigaDomanda['Photo'])){
                                        ?>
                                            <img width="100" src="data:image/jpeg;base64,<?php echo base64_encode($rigaDomanda['Photo']); ?>"/>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $sqlVisualizzaRisposta="SELECT * FROM closed_answer AS c, options AS o WHERE c.Id_Options= o.Id AND Id_ClosedQuestion='$idDomanda' AND Email_User='$email'";
                                        $risVisualizzaRisposta=mysqli_query($link,$sqlVisualizzaRisposta) or die (mysqli_error($link));
                                        ?>
                                        <select name="<?php echo $idDomanda; ?>">
                                            <?php 
                                            if($testo=mysqli_fetch_array($risVisualizzaRisposta)){
                                                $numero=$testo['Id_Options'];
                                                ?>
                                                <option value='<?php echo $numero; ?>' hidden selected><?php echo $testo['Text']; ?></option>
                                                <?php
                                            } else {
                                                 echo "<option hidden selected></option>";
                                            }

                                            while($opzione=mysqli_fetch_array($risOpzioni)){
                                                $numero=$opzione['Id'];
                                                echo "<option value='$numero'>".$opzione['Text']."</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <?php
                                    if($rigaInfo['Email_PremiumUser']==$email or $rigaInfo['Email_Company']==$email){
                                        ?>
                                        <td>&nbsp;</td>
                                        <td><a href="<?php echo 'DeleteQuestion.php?idSondaggio='.$idSondaggio.'&idDomanda='.$idDomanda;?>"><input type="button" class="small-button" value="DELETE"/></a></td>
                                        <?php
                                    }
                                    if($user_type=='premium'){
                                        ?>
                                        <td><a href="<?php echo 'ViewAnswers.php?idSondaggio='.$idSondaggio.'&idDomanda='.$idDomanda;?>"><input type="button" class="small-button" value="VIEW ANSWERS"/></a></td>
                                        <?php
                                    }
                                    ?>
                                </tr>
                            </table>
                        <?php
                        }
                        $contaDomande++;
                        echo "<br><br>";
                    }

                    //SALVATAGGIO RISPOSTE DEGLI UTENTI (TUTTE LE TIPOLOGIE)
                if($user_type!='azienda'){
                ?>
                    <input type="submit" class="small-button" value="SAVE ANSWERS" name="save"/>
                    <!-- <input type="reset" class="small-button" value="DELETE ANSWERS"/> -->
                <?php
                }
                ?>
                </form>
                
                <?php
                if($rigaInfo['Email_PremiumUser']==$email or $rigaInfo['Email_Company']==$email){
                    ?>
                    <form method="post">
                        <br>
                        <h3>Add a question here:</h3>
                        <?php
                        // query per prendere tutte le domande che non sono ancora risultate come inserite nel survey.
                        $sqlNuoveDomande="SELECT * FROM question WHERE (Email_PremiumUser='$email' OR Email_Company='$email') AND Id NOT IN (SELECT Id FROM question AS q, composition AS c WHERE q.Id=c.Id_Question AND c.Id_Survey='$idSondaggio')";
                        $risNuoveDomande=mysqli_query($link,$sqlNuoveDomande) or die (mysqli_error($link));
                        echo "<select name='nuovaDomanda' required>";
                            while($nuovaDomanda=mysqli_fetch_array($risNuoveDomande)){
                                $idNuovaDomanda=$nuovaDomanda['Id'];
                                echo "<option value='$idNuovaDomanda'>".$nuovaDomanda['Text']."</option>";
                            }
                        echo "</select>";
                        ?>
                        <input type="submit" class="small-button" value="ADD" name="aggiungi"/>
                    </form>
                    <br>
                    <br>
                <?php
                }
                ?>
            </body>
        </html>
        <?php
        }  
         
    } else {
        header("Location:index.php"); 
    }

    if(isset($_POST['save'])){
        $sqlDomanda="SELECT * FROM question as q, composition as c WHERE q.Id=c.Id_Question AND c.Id_Survey='$idSondaggio'";
        $risDomanda=mysqli_query($link,$sqlDomanda) or die (mysqli_error($link));
        
        while($rigaDomanda=mysqli_fetch_array($risDomanda)){
            $id=$rigaDomanda['Id'];
            $punteggio=$rigaDomanda['Score'];
            $risposta=$_POST["$id"];

            if($risposta!=""){

            $sqlTipologiaDomanda="SELECT * FROM open_question WHERE Id_Question='$id'";
            $risTipologiaDomanda=mysqli_query($link,$sqlTipologiaDomanda) or die (mysqli_error($link));
            
            $sql1="DELETE FROM open_answer WHERE Id_OpenQuestion='$id' and Email_User='$email'";
            $ris1=mysqli_query($link,$sql1) or die (mysqli_error($link));
            $sql2="DELETE FROM closed_answer WHERE Id_OptionClosedQuestion='$id' and Email_User='$email'";
            $ris2=mysqli_query($link,$sql2) or die (mysqli_error($link));

            if(mysqli_num_rows($risTipologiaDomanda)>0){
                
                //SALVATAGGIO RISPOSTA APERTA
                $sqlRisposta="INSERT INTO open_answer (Text, Email_User, Id_OpenQuestion)
	                            values
	                            ('".$risposta."','".$email."','".$id."');";
	            $risRisposta= mysqli_query($link,$sqlRisposta) or die (mysqli_error($link,));


            } else {
                //SALVATAGGIO RISPOSTA CHIUSA
                $sqlRisposta="INSERT INTO closed_answer (Email_User, Id_Options, Id_OptionClosedQuestion)
	                            values
	                            ('".$email."','".$risposta."','".$id."');";
	            $risRisposta= mysqli_query($link,$sqlRisposta) or die (mysqli_error($link));

            }

            
            }
        }
        echo "<script>alert('Survey compiled successfully!');document.location.href='Surveys.php?idSondaggio=".$idSondaggio."'</script>";
    }

    //AGGIUNTA DOMANDA 
    if(isset($_POST['aggiungi'])){
        $nuovaDomanda=$_POST['nuovaDomanda'];
        $sql="INSERT INTO composition (Id_Survey,Id_Question)
	                            values
	                            ('".$idSondaggio."','".$nuovaDomanda."');";
	    $ris=mysqli_query($link,$sql) or die (mysqli_error($link,));

        if($ris){
            echo "<script>alert('Question added successfully!');document.location.href='Surveys.php?idSondaggio=".$idSondaggio."'</script>";
        }
    }

    
?>