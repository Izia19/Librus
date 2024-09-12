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
        session_start(); //rozpoczecie sesji
        $connected = mysqli_connect("localhost", "root", "", "librus"); //połączenie z bazą danych

        include 'funkcje.php'; 

        echo '<div id = "naglowek"> <header> LIBRUS NAUCZYCIEL </header> </div>'; //naglowek
        echo '<div id = "lewa"> <section> <form method = "post" action="http://localhost/iza/librus/nauczyciel.php">
            
            <h4> Oceny: </h4>
            <table>
                <tr> 
                    <td> Imie: <input name = "Imie"> </td> 
                    <td> <input type = "submit" name = "Dodaj_ocene" value = "Dodaj ocene dla ucznia"> </td>
                </tr>
                <tr>
                    <td> Nazwisko: <input name = "Nazwisko"> </td> 
                    <td> <input type = "submit" name = "Wyswietl_oceny" value = "Wyswietl oceny i srednia"> </td>
                </tr>
                <tr>
                    <td> Ocena: <input name = "Ocena"> </td>                     
                </tr>
                <tr>';
                    $kwarenda = "SELECT ID, Przedmiot FROM przedmioty"; //wyswietla dostepne przedmioty
                    $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");
                    echo '<td> <select name = "przedmiot">';
                    while($wypisz = mysqli_fetch_row($wynik))
                    {    
                        echo "<option value = '" . $wypisz[0] . "'>" . $wypisz[1] . "</option>"; //wyswietla dostepne przedmioty jako select 
            
                    }           
                    echo '</select></td>              
                </tr>
            </table>
               
            <br>
            <input ID = "btn" type = "submit" name = "Wyswietl_uczniow" value = "Wyswietl uczniow"> 
            <input ID = "btn" type = "submit" name = "Wyswietl_klasy" value = "Wyswietl klasy"> 
            <br> <br> 
            <input ID = "wyloguj" type = "submit" name = "Wyloguj" value = "Wyloguj"> 
            
        </form> </section> </div>'; //formularz

        echo " <div id = 'prawa'>";
        if(@$_POST["Wyswietl_klasy"] == true)
        {
            wyswietl_klasy($connected);
        }
        else if(@$_POST["Wyswietl_uczniow_klasy"])
        {
            wyswietl_uczniow_klas($connected);
        }
        else if(@$_POST["Wyswietl_uczniow"] == true)
        {
            wyswietl_uczniow($connected);      
        }
        else if(@$_POST["Wyswietl_oceny"] == true)
        {
            if($_POST['Imie'] == null || $_POST['Nazwisko'] == null) //jesli pole nie zostalo wypelnione
            {
                echo "<div ID = komunikaty>";
                echo "Podaj Imie i Nazwisko </div>"; //wyswietla komunikat
            }
            else
            {    
                $Imie = $_POST['Imie'];
                $Nazwisko = $_POST['Nazwisko'];

                $_SESSION['Imie'] = $Imie;
                $_SESSION['Nazwisko'] = $Nazwisko;
                wyswietl_srednia($connected);
                wyswietl_oceny_konkretnych_uczniow_nauczyciel($connected); //wywolanie funkcji wyswietl oceny konkretnych uczniow
            }          
        } 
        else if(@$_POST["Rozwin"] == true) //jesli kliknie przycisk zostaje przekierowany do innej strony
        {
            wyswietl_opis_ocen_nauczyciel($connected);
        }
        else if(@$_POST["Wyloguj"] == true) //jesli kliknie przycisk zostaje przekierowany do innej strony
        {
            header("Location: logowanie.php");
        }
        else if(@$_POST["Dodaj_ocene"] == true)
        {
            if($_POST['Ocena'] == null || $_POST['Imie'] == null || $_POST['Nazwisko'] == null) //jesli pole nie zostalo wypelnione
            {
                
                echo "<div ID = komunikaty> Podaj ocene Imie i Nawisko ucznia </div>"; //wyswietla komunikat
            }
            else
            {  
                $Imie = $_POST['Imie'];
                $Nazwisko = $_POST['Nazwisko'];

                $_SESSION['Imie'] = $Imie;
                $_SESSION['Nazwisko'] = $Nazwisko;
                dodaj_ocene_nauczyciel($connected); //wywolanie funkcji dodaj ocene
            }           
        }     
        else if(@$_POST["Edytuj"] == true)
        {
            if($_POST['ocena'] == null) //jesli pole nie zostalo wypelnione
            {
                echo "<div ID = komunikaty> ";
                echo "Wtebierz ID oceny i nowa ocene </div>"; //wyswietla komunikat
            }
            else
            {          
                $ID = $_POST['Edytuj'];
                $Nowa_ocena = $_POST['ocena']; //przypisuje wartosci pod zmienne
                edytuj_oceny($connected, $ID, $Nowa_ocena); //wywolanie funkcji edytuj ocene
            }        
        }
        else if(@$_POST["Usun"] == true)
        {        
            $ID = $_POST['Usun'];
            usun_ocene($connected, $ID); //wywolanie funkcji usun ocene                   
        }  
        echo "</div> ";
        
        echo '<footer> Autor: Iza Mazur </footer>'; //stopka

    ?>
    
</body>
</html>