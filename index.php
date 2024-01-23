<?php

    //sesija je kao mala memorija koja cuva neke podatke
    //session_start();

    //konekcija na bazu
    require_once 'config.php';

    //Da li su podaci uspesno poslati
    if($_SERVER['REQUEST_METHOD'] == "POST"){
    //Podaci su poslati.
    //Uhvatimo te podatke
        $username = $_POST['username'];
        $password = $_POST['password'];

        //izvlacimo podatke iz baze
        $sql = "SELECT admin_id, password FROM admins 
                WHERE username = ?";

        //pripremamo sql za izvrsenje
        $run = $conn->prepare($sql);  
        $run->bind_param("s", $username);  // "s" - string, i - int, ss - imamo 2 stringa gore itd
        $run->execute();

        $results = $run->get_result();


        if($results->num_rows == 1){
            //echo "Admin postoji";
            //provera za password
            //iz rezultata vraca asocijativni niz
            $admin = $results->fetch_assoc();
            //pa iz asocijativnog niza uzimamo pass
            //if  $admin['password'] === $password (druga verz)
            if(password_verify($password,$admin['password'])){
                //da neko ne bi otvorio preko linka automatski
                $_SESSION['admin_id'] = $admin['admin_id'];
                $conn->close();
                header('location: admin_dashboard.php');
            }else{
                $_SESSION['error'] = "Netacan password!";
                //uradimo redirect i nakon njega uvek ide exit
                //da ne iskace stalno popup
                $conn->close();
                header('location: index.php');
                exit();
            }
        }else{
            $_SESSION['error'] = "Netacan username!";
            $conn->close();
            header('location: index.php');
            exit();
        }
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Admin Login</title>
    </head>

    <body>

        <?php

        if(isset($_SESSION['error'])){
            echo $_SESSION['error'] . "<br>";
            //kada ga ispisemo, onda ga unistimo
            unset($_SESSION['error']);
        }

        ?>

        <form action="" method="POST">
            Username: <input type="text" name="username"> <br>
            Password: <input type="password" name="password"> <br>
            <input type="submit" value="Login">
        </form>

    </body>
</html>