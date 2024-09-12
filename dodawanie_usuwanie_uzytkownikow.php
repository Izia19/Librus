<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librus</title>
    <link rel = "stylesheet" href = "librus_style.css">
    <style>
        /* SELECT */
        select{
            background-color: #E6E6FA;
            color: #4B0082;
            border: 2px solid #008080;  
            border-style: dashed;
            position: relative;
            font-family: 'Trebuchet MS';
            padding: 6px 12px;
        }
    </style>
</head>
<body>
    <?php
    $connected = mysqli_connect("localhost", "root", "", "librus"); //połączenie z bazą danych
    
    include 'funkcje.php'; 

    echo '<div id = "naglowek"> <header> DODAWANIE I USUWANIE UŻYTKOWNIKÓW </header> </div>'; //naglowek

        echo '<div id = "lewa"> <section> <form method = "post" action="http://localhost/iza/librus/dodawanie_usuwanie_uzytkownikow.php">
            <h4> Dodawanie użytkowników: </h4>   
            <table>
                <tr> 
                    <td> Login: <input name = "Login">  </td> 
                    <td> Haslo: <input name = "Haslo"> </td> 
                    <td> <select name = "wybor">
                        <option value = "uczen" > Uczen </option>
                        <option value = "nauczyciel" > Nauczyciel </option>
                    </td>
                  </select>
                </tr>
                <tr>
                    <td> Imie: <input name = "Imie"> </td> <td> Nazwisko: <input name = "Nazwisko">';
                    $kwarenda = "SELECT ID, Klasa 
                    FROM klasa"; //wyswietla dostepne przedmioty
                    $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");
                    echo '<td> <select name = "Klasa">';
                    while($wypisz = mysqli_fetch_row($wynik))
                    {    
                        echo "<option value = '" . $wypisz[0] . "'>" . $wypisz[1] . "</option>"; //wyswietla dostepne przedmioty jako select            
                    }           
                    echo '</select> </td>        
                </tr>                  
            </table> <br>

            <input ID = "btn" type = "submit" name = "Dodaj" value = "Dodaj">  

            <h4> Usuwanie użytkowników </h4>
            <table>
                <tr>
                    <td>  ID: <input name = "ID">  </td>
                </tr>
            </table> <br>

            <input ID = "btn" type = "submit" name = "Usun" value = "Usuń"> 
            <br><br><br>
            
        <input ID = "btn" type = "submit" name = "Wyswietl_uzytkownikow" value = "Wyswietl użytkowników"> 
        <input ID = "btn" type = "submit" name = "Wyswietl_klasy" value = "Wyswietl klasy"> 
        <input ID = "btn" type = "submit" name = "Powrot" value = "Powrót">     
   
    </form> </section> </div>'; //formularz

    echo "<div id = 'prawa'>";
    if(@$_POST["Wyswietl_uzytkownikow"] == true) //po kliknieciu w przycisk 
    {
        Wyswietl_uzytkownikow($connected);
    }
    else if(@$_POST["Wyswietl_klasy"] == true)
    {
        wyswietl_klasy($connected);
    }
    else if(@$_POST['Powrot'] == true)
    {
        header("Location: admin.php"); //przenosi nas do pliku admin.php
    } 
    else if(@$_POST['Dodaj'] == true) 
    {
        if($_POST['Login'] == null || $_POST['Haslo'] == null || $_POST['Imie'] == null || $_POST['Nazwisko'] == null && $_POST['Klasa'] == null) //jesli pole nie zostalo wypelnione
        {
            echo "<div ID = komunikaty> ";
            echo "Wypełnij wymagane pola </div>"; //wyswietla komunikat
        }
        else
        {          
            dodawanie($connected); //wywolanie funkcji dodawnaie
        }   
    }
    else if(@$_POST["Usun"] == true)
    {
        if($_POST['ID'] == null) //jesli pole nie zostalo wypelnione
        {
            echo "<div ID = komunikaty> ";
            echo "Wypełnij wymagane pola </div>"; //wyswietla komunikat
        }
        else
        {          
            usun_uzytkownika($connected); //wywolanie funkcji usun uzytkownika
        }        
    }
    echo "<div>";
    echo '<footer> Autor: Iza Mazur </footer>'; //stopka
?>

    
</body>
</html>