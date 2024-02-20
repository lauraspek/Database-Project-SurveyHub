
<?php
include "connessione.php";
if(isset($_POST['registra'])){
    $nome=trim(strtolower($_POST['Name']));
    $sede=trim(strtolower($_POST['Venue']));
    $cf=$_POST['Cf'];
    $email=$_POST['Email'];
    $password=$_POST['Password'];
    
    $nome=ucwords($nome);
    $sede=ucwords($sede);

    
        $sqlControlloEmail="SELECT * FROM company WHERE Email='$email'";
        $risControlloEmail=mysqli_query($link,$sqlControlloEmail) or die ("Query fallita");
        if(mysqli_num_rows($risControlloEmail)>0){
            echo "<script>alert('ERROR! This email is already used!');</script>";
        } else {
            $sql="CALL RegistrationCompany('$email','$nome','$sede','$cf','$password')";
	        $ris=mysqli_query($link,$sql) or die ("Query fallita".mysqli_error($link,));
            
            if($ris){
                session_start();
                $_SESSION['Email'] = $email;
                $_SESSION['Password'] = $password;
                $_SESSION['tipologia'] = 'azienda';                
                ?>
                <script>alert('Successful login!');
                document.location.href='CompanyArea.php'
                </script>
                <?php
            }
        
    }
}
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <title>SURVEYHUB</title>
        <meta charset="utf-8">
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
        .small-button {
                    padding: 15px 5px;
                    margin-left: 350px;
                    font-size: 12px;
                    color: #007bff;
                    width: 100px;
                }
    </style>
    </head>
    
    <body>
        <nav>
            <ul class="navbar">
                <li><a href="index.php">Go back to Login Page</a></li>
            </ul>
        </nav>
        <h2>Signup as a company here:</h2>
        <form method="post">
            <table>
                <tr>
                    <th><label>Name:</label></th>
                    <td><input type="text" name="Name" required/></td>
                </tr>
                <tr>
                    <th><label>Venue:</label></th>
                    <td><input type="text" name="Venue" required/></td>
                </tr>
                <tr>
                    <th><label>CF:</label></th>
                    <td><input type="text" name="Cf" maxlength="11" required/></td>
                </tr>
                <tr>
                    <th><label>Email:</label></th>
                    <td><input type="email" name="Email" required/></td>
                </tr>
                <tr>
                    <th><label>Password:</label></th>
                    <td><input type="password" name="Password" required/></td>
                </tr>
                
            </table>
            <br/>
            <br/>
            <input type="submit" class="small-button" name="registra" value="SIGNUP"/>
            
        </form>
    </body>
</html>    

