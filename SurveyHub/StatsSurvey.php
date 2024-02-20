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

        if((mysqli_num_rows($risControllo1)==0 or (mysqli_num_rows($risControllo2)==0 && $user_type!='premium')) && $user_type!='azienda'){
            $_SESSION=array();
            session_destroy(); 
            header("Location:index.php"); 
        } else {
            ?>
            <html>
            <head>
                <meta charset="utf-8">
                <title>SURVEYHUB</title>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
                <style>
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

                .small-button {
                    padding: 10px 5px;
                    font-size: 12px;
                    color: #007bff;
                    width: 100px;
                }

                .button-container {
                    text-align: center;
                    /* Allinea il testo al centro (orizzontalmente) */
                    margin-top: 20px;
                    /* Aggiungi spazio sopra i pulsanti per centrarli verticalmente */
                }

                .button-container input[type="submit"],
                .button-container input[type="reset"] {
                    display: inline-block;
                    margin-right: 10px;
                    /* Aggiungi spazio tra i pulsanti */
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

                <?php
                $sqlInfoSondaggio="SELECT * FROM survey WHERE Id='$idSondaggio'";
                $risInfoSondaggio=mysqli_query($link,$sqlInfoSondaggio);
                $rigaInfo=mysqli_fetch_array($risInfoSondaggio)
                ?>
                
                <h2>Stats about: <i><?php echo $rigaInfo['Title']; ?></i></h2>
                <?php
                $sqlDomanda="SELECT * FROM question as q, composition as c WHERE q.Id=c.Id_Question AND Id_Survey='$idSondaggio'";
                $risDomanda=mysqli_query($link,$sqlDomanda) or die (mysqli_error($link,));
                $contaDomande=1;
                
                while($rigaDomanda=mysqli_fetch_array($risDomanda)){
                    $idDomanda=$rigaDomanda['Id'];
                    $sqlTipologiaDomanda="SELECT * FROM open_question WHERE Id_Question='$idDomanda'";
                    $risTipologiaDomanda=mysqli_query($link,$sqlTipologiaDomanda) or die (mysqli_error($link));
                    if(mysqli_num_rows($risTipologiaDomanda)>0){
                        
                        //STATS OPEN QUESTION
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
                                
                                <td><p>
                                    <?php
                                    $sqlVisualizzaRispostaA="SELECT Text FROM open_answer  WHERE Id_OpenQuestion='$idDomanda'";
                                    $risVisualizzaRispostaA=mysqli_query($link,$sqlVisualizzaRispostaA) or die (mysqli_error($link));
                                    if(mysqli_num_rows($risVisualizzaRispostaA)>0){
                                        $max=0;
                                        $min=$maxCaratteri;
                                        $count=0;
                                        $sommaCaratteri=0;
                                        while($testo=mysqli_fetch_array($risVisualizzaRispostaA)){
                                            $lunghezza=strlen($testo['Text']);
                                            if($lunghezza>$max) {$max=$lunghezza;}
                                            if($lunghezza<$min) {$min=$lunghezza;}
                                            $count++;
                                            $sommaCaratteri=$sommaCaratteri+$lunghezza;
                                        }
                                        $media=$sommaCaratteri/$count;
                                        ?>
                                        Mean value of char: <?php echo $media; ?>
                                        <br>Minumun value of char: <?php echo $min; ?>
                                        <br>Maximum value of char: <?php echo $max; ?>
                                    <?php
                                    } else {
                                        echo "There is no answers!";
                                    }
                                    ?>
                                </p></td>
                            </tr>
                        </table>
                    <?php
                    } else {

                        //STATS CLOSED QUESTION
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
                                    $sqlVisualizzaRispostaC="SELECT Text, COUNT(*) AS Tot FROM closed_answer AS c, options AS o WHERE Id_ClosedQuestion='$idDomanda' AND c.Id_Options=o.Id  GROUP BY Text";
                                    $risVisualizzaRispostaC=mysqli_query($link,$sqlVisualizzaRispostaC) or die (mysqli_error($link,));
                                    if(mysqli_num_rows($risVisualizzaRispostaC)>0){
                                        while($testo=mysqli_fetch_array($risVisualizzaRispostaC)){
                                            echo $testo=$testo['Text']." -> ".$totale=$testo['Tot']."<br>";
                                        }
                                    } else {
                                        echo "There is no answers!";
                                    }
                                    ?>    
                                </td>
                            </tr>
                        </table>
                    <?php
                    }
                    $contaDomande++;
                    echo "<br><br>";
                }
        }   
    } else {
        header("Location:index.php"); 
    }
?>