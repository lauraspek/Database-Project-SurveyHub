<!DOCTYPE html>
<?php
    session_start();
    include("connessione.php");
    if (isset($_SESSION['Email']) && isset($_SESSION['Password'])){
        //verifico se esistono nel database
        $email=$_SESSION['Email'];
        $password=$_SESSION['Password'];
        $sqlControllo="Select Email, Password from user WHERE ";
        $sqlControllo.="Email='".$email."' and Password='".$password."';";
        $risControllo=mysqli_query($link,$sqlControllo);

        if(mysqli_num_rows($risControllo)==0){
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
                    width: 100px;
                }
            </style>
                </head>

                <body class="page-container">
                    <form method="post">
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

                    <!-- visibile solo se utente sta ricevendo degli inviti -->
                    <?php
                    $sqlInvitiInAttesa="CALL ViewPendingInvitation('$email')";
                    $risInvitiInAttesa=mysqli_query($link,$sqlInvitiInAttesa) or die ("Query fallita");
                    if(mysqli_num_rows($risInvitiInAttesa)>0){
                    ?>
                        <h2>Waitlisted invitations:</h2>
                        <table border="1" class="survey-table">
                            <tr>
                                <th>SURVEY</th>
                                <th>DOMAIN</th>
                                <th>CREATOR</th>
                            </tr>
                            <?php
                                while($rigaInvitiInAttesa=mysqli_fetch_array($risInvitiInAttesa)){
                                    echo "<tr>";
                                        echo "<td>".$rigaInvitiInAttesa['Title']."</td>";
                                        echo "<td>".$rigaInvitiInAttesa['Keyword_Domain']."</td>";
                                        echo "<td>".$rigaInvitiInAttesa['Email_PremiumUser']."".$rigaInvitiInAttesa['Email_Company']."</td>";
                                        $idInvitoAtteso=$rigaInvitiInAttesa['Id'];
                                        ?>
                                        <td><a href="WaitlistedInvitation.php?id=<?php echo $idInvitoAtteso; ?>&esito=accepted"><input type="button" class="small-button" value="ACCEPT"/></a></td>
                                        <td><a href="WaitlistedInvitation.php?id=<?php echo $idInvitoAtteso; ?>&esito=refused"><input type="button" class="small-button" value="REFUSE"/></a></td>
                                        <?php
                                    echo "</tr>";
                                }
                            ?>
                        </table>


                    <?php
                    }
                    mysqli_close($connessione);
                    require("connessione.php");
                    $sqlInvitiPassati="Select * from survey as s, invitation as i Where i.Id_Survey=s.Id and Email_User='$email' and Outcome<>'pending'";
                    $risInvitiPassati=mysqli_query($link,$sqlInvitiPassati) or die ("Failed Query".mysqli_error($link));
                    if(mysqli_num_rows($risInvitiPassati)>0){
                    ?>
                    
                    <!-- visibile solo se utente ha accettato o rifiutato inviti nel tempo-->
                        <h2>Invitations:</h2>
                        <table border="1" class="survey-table">
                            <tr>
                                <th>SURVEY</th>
                                <th>DOMAIN</th>
                                <th>CREATOR</th>
                                <th>OUTCOME</th>
                            </tr>
                            <?php
                                while($rigaInvitiPassati=mysqli_fetch_array($risInvitiPassati)){
                                    echo "<tr>";
                                        echo "<td>".$rigaInvitiPassati['Title']."</td>";
                                        echo "<td>".$rigaInvitiPassati['Keyword_Domain']."</td>";
                                        echo "<td>".$rigaInvitiPassati['Email_PremiumUser']."".$rigaInvitiPassati['Email_Company']."</td>";
                                        echo "<td>".$rigaInvitiPassati['Outcome']."</td>";
                                        $idInvito=$rigaInvitiPassati['Id'];
                                        $esitoAttuale=$rigaInvitiPassati['Outcome'];
                                        ?>
                                        
                                        <?php
                                    echo "</tr>";
                                }
                            ?>
                        </table>
                    <?php
                    }
                    ?>
                    </form>
                </body>
            </html>

            <?php
        }
        
    } else {
        header("Location:index.php"); 
    }
?>