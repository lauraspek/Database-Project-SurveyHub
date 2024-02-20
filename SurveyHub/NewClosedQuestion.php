<!DOCTYPE html>
<?php
    session_start();
    include("connessione.php");
    if (isset($_SESSION['Email']) && isset($_SESSION['Password'])){
        //verifico se esistono nel database
        $email=$_SESSION['Email'];
        $password=$_SESSION['Password'];
        $user=$_REQUEST['user'];
        $keyword=$_REQUEST['keywordDominio'];

        $sqlControllo1="Select Email, Password from user WHERE ";
        $sqlControllo1.="Email='".$email."' and Password='".$password."';";
        $risControllo1=mysqli_query($link,$sqlControllo1);

        $sqlControllo2="Select * from interest WHERE ";
        $sqlControllo2.="Keyword_Domain='".$keyword."' and Email_User='".$email."';";
        $risControllo2=mysqli_query($link,$sqlControllo2);

        if((mysqli_num_rows($risControllo1)==0 or mysqli_num_rows($risControllo2)==0) && $user!='azienda'){
            $_SESSION=array();
            session_destroy(); 
            header("Location:index.php"); 
        } else {
            ?>

            <html>
                <head>
                    <meta charset="utf-8">
                    <title>sSURVEYHUB</title>
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
                    
                    <!-- javascript per inserimento numero di opzioni domanda chiusa -->
                    <script type="text/javascript">
                        function AggiungiOpzione(n_opzioni){
                            var numero_opzioni = n_opzioni.value;
                            var box = document.getElementById('box_opzioni');
                            if(isNaN(numero_opzioni)==true){
                                box.innerHTML='';
                            }else{
                                var opzioni = "";
                                for(i=1; i<=numero_opzioni; i++){
                                    opzioni = opzioni+"Option "+i+") <input type='text' name='opzione"+i+"'/><br/>";
                                }
                                box.innerHTML=opzioni;
                            }
                        }
                    </script>
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
                            <li><a href="SurveyManage.php">Create a new survey</a></li>
                            <li><a href="UsersRanking.php">See users ranking</a></li>
                            <li><a href="Prizes.php">Prizes</a></li>
                            
                            <?php
                            if($user=='azienda'){
                                ?><li><a href="CompanyArea.php">Go back to your personal company page</a></li><?php
                            } else {
                                ?><li><a href="UserArea.php">Go back to your personal user page</a></li>
                                <li><a href="InvitationManage.php">Invitations</a></li>
                                <?php
                            }
                            ?>
                            <li><a href="index.php">Logout</a></li>
                        </ul>
                    </nav>
                    <h2>Create a new Closed Question:</h2>
                    <form method="post" enctype="multipart/form-data">
                        <table>
                            <tr>
                                <td>Text:</td>
                                <td>
                                    <textarea name="Text" rows="2" cols="50" required></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>Score:</td>
                                <td><input type="number" name="Score" min="0.05" step="0.05" required/></td>
                            </tr>
                            <tr>
                                <td>Photo:</td>
                                <td><input type="file" name="immagineDomanda" accept=".jpg,.jpeg,.png"/></td>
                            </tr>
                            <tr>
                                <td>Number of options:</td>
                                <td><input type="number" name="numOptions" min="2" max="6" oninput="AggiungiOpzione(this)" required/></td>
                            </tr>
                        </table>
                        <div style="margin-left: 30px">
                            <span id='box_opzioni'></span>
                        </div>
                        <br>
                        <div class="button-container">
                    <input type="submit" class="small-button" name="inserisciDomanda" value="CREATE" />
                </div>
                    </form>
                </body>
            </html>

            <?php
        }
        
    } else {
        header("Location:index.php"); 
    }

    if(isset($_POST['inserisciDomanda'])){

        $testoDomanda=trim($_POST['Text']);
        $testoDomanda=ucwords($testoDomanda);
        $punteggioDomanda=$_POST['Score'];
        
        if(!empty($_FILES["immagineDomanda"]["tmp_name"]) && file_exists($_FILES["immagineDomanda"]["tmp_name"])){
            $immagineDomanda=file_get_contents($_FILES["immagineDomanda"]["tmp_name"]);
            $immagineDomanda=addslashes($immagineDomanda);        
        } else {
            $immagineDomanda=null;
        }

        if($user!='azienda'){
            $sqlUno="CALL InsertPremiumClosedQuestion('$testoDomanda','$email','$immagineDomanda','$punteggioDomanda')";
        } else {
            $sqlUno="CALL InsertCompanyClosedQuestion('$testoDomanda','$email','$immagineDomanda','$punteggioDomanda')";
        }
	    $risUno=mysqli_query($link,$sqlUno) or die ("Failed query1".mysqli_error($link));

    

        $numeroOpzioni=$_POST['numOptions'];
        for($i=1; $i<=$numeroOpzioni; $i++){
            $var="opzione".$i;
            $$var=$_POST["$var"];
            $varProcedura=$$var;

            $sqlDue="CALL InsertOption(LAST_INSERT_ID(),'$i','$varProcedura')";
            $risDue=mysqli_query($link,$sqlDue) or die ("Failed query2".mysqli_error($link));
        }
	
        if($risUno && $risDue){
            echo "<script>alert('Closed question inserted successfully!');</script>";
        }

    }
?>