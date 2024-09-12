<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librus</title>
    <link rel = "stylesheet" href = "librus_style.css">
    <style>

    </style>
</head>
<body>
    <?php
    session_start(); //rozpoczecie sesji
    include 'funkcje.php'; 

    $connected = mysqli_connect("localhost", "root", "", "librus"); //połączenie z bazą danych
        echo '<div id = "naglowek"> <header> LOGOWANIE </header> </div>'; //naglowek
        echo '<div id = "srodek"><center> <form method = "post" action="http://localhost/iza/librus/logowanie.php">
            <h4> Logowanie: </h4>
                <table>
                    <tr> 
                        <td> Login: <input name = "Login">  </td> <td> Haslo: <input type = "password" name = "Haslo"> </td> 
                    </tr>
                </table> <br>
            <input ID = "btn" type = "submit" name = "Zaloguj" value = "Zaloguj">      
           
       </form> '; //formularz   

       if(@$_POST['Zaloguj'] == true) //po nacisnieciu przycisku wywoluje metode
       {
            zaloguj($connected);
       }
       echo '<br> </center> </div>';     

       echo '<footer> Autor: Iza Mazur </footer>'; //stopka
    ?>
    
</body>
</html>