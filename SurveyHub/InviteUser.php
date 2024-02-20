<!DOCTYPE html>
<?php
    session_start();
    include("connessione.php");
    if (isset($_SESSION['Email']) && isset($_SESSION['Password'])){
        //verifico se esistono nel database
        $email=$_SESSION['Email'];
        $password=$_SESSION['Password'];
        $idSondaggio=$_REQUEST['idSondaggio'];
        $user_type=$_SESSION['tipologia'];

        //PARTE AZIENDA
        if($user_type=='azienda'){
           
            $sqlUtentiNonInvitati="SELECT * FROM user WHERE Email <> '$email' and Email NOT IN 
                                    (SELECT Email FROM user as u, invitation as i 
                                    WHERE u.Email=i.Email_User and i.Id_Survey='$idSondaggio')
                                    and Email IN (SELECT Email_User FROM interest WHERE Keyword_Domain = 
                                    (SELECT Keyword_Domain FROM survey WHERE Id='$idSondaggio')) ORDER BY RAND()";
            $risUtentiNonInvitati=mysqli_query($link, $sqlUtentiNonInvitati) or die (mysqli_error($link)."Query fallita");
            
             $i=1;
            $count=0;
            while($utentiDaInvitare=mysqli_fetch_array($risUtentiNonInvitati)) {
                 if ($i % 2 == 0) {
                    $emailUtente=$utentiDaInvitare['Email'];
                    $sql="insert into invitation (Outcome,Email_User,Id_Survey,Email_Company)
	                    values
	                    ('pending','".$emailUtente."','".$idSondaggio."', '".$email."');";
	
	                $ris=mysqli_query($link,$sql) or die ("Query fallita");
                    if($ris) $count++;
                 }
                 $i++;
            }
            ?>
            <script>alert('<?php echo $count; ?> Utenti Invitati!');document.location.href='CompanyArea.php'</script>
           <?php 
        }

        //PARTE UTENTI
        $sqlControllo1="Select Email, Password from user WHERE ";
        $sqlControllo1.="Email='".$email."' and Password='".$password."';";
        $risControllo1=mysqli_query($link,$sqlControllo1);

        $sqlControllo2="Select * from survey WHERE ";
        $sqlControllo2.="Id='".$idSondaggio."' and Email_PremiumUser='".$email."';";
        $risControllo2=mysqli_query($link,$sqlControllo2);

        if((mysqli_num_rows($risControllo1)==0 or mysqli_num_rows($risControllo2)==0) && $user_type!='azienda'){
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
                    width: 160px;
                }
            </style>
                </head>
                
                <body class="page-container" >
                    <nav>
                        <ul class="navbar">
                        <li><a href="SurveyManage.php">Surveys / Domains</a></li>
                        <li><a href="UsersRanking.php">See users ranking</a></li>
                        <li><a href="Prizes.php">Prizes</a></li>
                        <li><a href="InvitationManage.php">Invitations</a></li>
                        <li><a href="UserArea.php">Go back to your personal user page</a></li>
                            <li><a href="index.php">Logout</a></li>
                        </ul>
                    </nav>
                    <?php
                    $sqlUtentiInvitati="Select * from invitation as i, user as u where i.Email_User=u.Email and Id_Survey='$idSondaggio' order by i.Outcome";
                    $risUtentiInvitati=mysqli_query($link,$sqlUtentiInvitati) or die ("Query fallita");
                    ?>
                    <br><p><b>Total invited users: <?php echo mysqli_num_rows($risUtentiInvitati); ?></b></p>
                    <table border="1" class="survey-table">
                        <tr>
                            <th>EMAIL</th>
                            <th>OUTCOME</th>
                        </tr>
                        <?php
                            while($rigaUtentiInvitati=mysqli_fetch_array($risUtentiInvitati)){
                                echo "<tr>";
                                    echo "<td>".$rigaUtentiInvitati['Email']."</td>";
                                    echo "<td>".$rigaUtentiInvitati['Outcome']."</td>";
                                echo "</tr>";
                            }
                        ?>
                    </table>

                    <?php
                    $sqlUtentiNonInvitati="SELECT * FROM user WHERE Email <> '$email' and Email NOT IN 
                                            (SELECT Email FROM user as u, invitation as i 
                                            WHERE u.Email=i.Email_User and i.Id_Survey='$idSondaggio')
                                            and Email IN (SELECT Email_User FROM interest WHERE Keyword_Domain = 
                                            (SELECT Keyword_Domain FROM survey WHERE Id='$idSondaggio'))";
                    $risUtentiNonInvitati=mysqli_query($link,$sqlUtentiNonInvitati) or die (mysqli_error($link)."Query fallita");
                    if(mysqli_num_rows($risUtentiNonInvitati)>0){
                        ?>
                        <form method="post">
                            <table>
                                <tr>
                                    <br><td><p><i>Ask a new user to take part to your survey!</i></p></td>
                                    <td>
                                        <select name="utenteInvitato" required>
                                            <option hidden selected></option>
                                            <?php
                                            while($rigaUtentiNonInvitati=mysqli_fetch_array($risUtentiNonInvitati)){
                                            ?>
                                                <option value="<?php echo $rigaUtentiNonInvitati['Email']; ?>">
                                                    <?php echo $rigaUtentiNonInvitati['Email'] ?>
                                                </option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td><input type="submit" name="invitaUtente" class="small-button" value="SEND INVITATION"/></td>
                                <tr>
                            </table>
                        </form>
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

    if(isset($_POST['invitaUtente'])){

        $utenteInvitato=$_POST['utenteInvitato'];

        $sql="insert into invitation (Outcome,Email_User,Id_Survey)
	        values
	        ('pending','".$utenteInvitato."','".$idSondaggio."');";
	
	    $ris= mysqli_query($link,$sql) or die ("Query fallita");
	
        if($ris){header("location:InviteUser.php?idSondaggio=$idSondaggio");}
    }
?>