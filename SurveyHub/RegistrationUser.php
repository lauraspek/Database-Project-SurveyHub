<?php
include "connessione.php";
if (isset($_POST['registra'])) {
    $nome = trim(strtolower($_POST['Name']));
    $cognome = trim(strtolower($_POST['Surname']));
    $annoNascita = $_POST['BirthYear'];
    $luogoNascita = $_POST['BirthPlace'];
    $email = $_POST['Email'];
    $password = $_POST['Password'];
    $user_type = $_POST['UserType'];
    

    $sqlControlloEmail = "SELECT * FROM user WHERE Email='$email'";
    $risControlloEmail = mysqli_query($link, $sqlControlloEmail) or die("Failed query");
    if (mysqli_num_rows($risControlloEmail) > 0) {
        echo "<script>alert('ERROR! This email is already used!');</script>";
    } else {
        $sql = "CALL RegistrationUser('$email','$nome','$cognome','$annoNascita','$luogoNascita','$password', '$user_type')";
        $ris = mysqli_query($link, $sql) or die("Query fallita" . mysqli_error($link));
        
       if ($ris) {
       session_start();
       $_SESSION['Email'] = $email;
       $_SESSION['Password'] = $password;
            include("functionCheckUserType.php");
            $user_type = checkUserType($email);
            if ($user_type == 1) {
                $_SESSION['tipologia'] = 'amministratore';
            } else if ($user_type == 2) {
                $_SESSION['tipologia'] = 'premium';
            } else {
                $_SESSION['tipologia'] = 'generico';
            }
?>
            <script>
                alert('Successful login!');
                document.location.href = 'UserArea.php'
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
    <h2 class="text-center">Signup as a user here:</h2>
    <form method="post">
        <table>
            <tr>
                <th><label>Name:</label></th>
                <td><input type="text" name="Name" required /></td>
            </tr>
            <tr>
                <th><label>Surname:</label></th>
                <td><input type="text" name="Surname" required /></td>
            </tr>
            <tr>
                <th><label>Birth Year:</label></th>
                <td><input type="number" min="1900" max="<?php echo date('Y'); ?>" name="BirthYear" required /></td>
            </tr>
            <tr>
                <th><label>Birth Place:</label></th>
                <td><input type="text" name="BirthPlace" required /></td>
            </tr>
            <tr>
                <th><label>Email:</label></th>
                <td><input type="email" name="Email" required /></td>
            </tr>
            <tr>
                <th><label>Password:</label></th>
                <td><input type="password" name="Password" required /></td>
            </tr>
            
            <label for="UserType">Type of account:</label>
            <select name="UserType" id="UserType">
                <option value="1">Admin</option>
                <option value="2">Premium</option>
                <option value="3">Basic</option>
            </select> <br>
        </table>
        <br />
        <br />
        <input type="submit" class="small-button" name="registra" value="SIGNUP" />

    </form>
</body>

</html>