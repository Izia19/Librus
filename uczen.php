<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librus</title>
    <link rel = "stylesheet" href = "librus_style.css">
</head>
<body>
    <?php
        session_start(); //rozpoczecie sesji
        include 'funkcje.php'; 

        $connected = mysqli_connect("localhost", "root", "", "librus"); //połączenie z bazą danych

        echo '<div id = "naglowek"> <header> LIBRUS UCZEN </header> </div>'; //naglowek
        echo '<center>'; //wysrodkowany obszar

        if (isset($_SESSION['Login']))  //jesli pole login jest uzupelniony
            {
                $Login = $_SESSION['Login']; // odczytanie wartości $Login z otwartej sesji

                $kwarenda = "SELECT ID 
                FROM uzytkownicy 
                WHERE Login = '$Login'"; //kwarenda znajdujaca ID uzytkownika o podanym loginie
                $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");
                $sprawdz = mysqli_num_rows($wynik);

                if($sprawdz == 1)
                {
                    $podpisz = $wynik -> fetch_assoc(); 
                    $ID = $podpisz["ID"]; //pobieranie wartosci z kwarendy 
                
                    $kwarenda2 = "SELECT Imie, Nazwisko, Klasa 
                    FROM uczniowie 
                    JOIN uzytkownicy ON uczniowie.ID_uzytkownika = uzytkownicy.ID 
                    JOIN klasa ON uczniowie.ID_klasa = klasa.ID 
                    WHERE uzytkownicy.ID = '$ID'"; //kwarenda laczaca tabele uzykownicyz tabela uczniowie poprzez ID oraz tabele uczniowie z tabela klasa poprzez ID gdzie ID uzytkownika jest takie same jak podane
                    $wynik2 = mysqli_query($connected, $kwarenda2) or die("Problemy z odczytem danych!");
                    $podpisz2 = mysqli_fetch_array($wynik2);

                    // przypisanie wartości kolumny do zmiennej
                    $Imie = $podpisz2['Imie'];
                    $Nazwisko = $podpisz2["Nazwisko"]; //pobieranie wartosci z kwarendy 

                    $_SESSION['Imie'] = $Imie;
                    $_SESSION['Nazwisko'] = $Nazwisko;

                    //Imie i Nazwisko
                    echo '<h4> Uczeń: '.$Imie." ".$Nazwisko.'</h4>';               
                
                    //Średnia
                    echo "<h4> Średnia ucznia: </h4>";
                    $kwarenda3 = "SELECT avg(Ocena) 
                    FROM oceny 
                    JOIN oceny_uczniow ON oceny.ID = oceny_uczniow.ID_oceny 
                    JOIN uczniowie ON uczniowie.ID = oceny_uczniow.ID_ucznia 
                    JOIN uzytkownicy ON uczniowie.ID_uzytkownika = uzytkownicy.ID 
                    WHERE uzytkownicy.ID = '$ID'"; //kwarenda laczaca tabele uzykownicyz tabela uczniowie poprzez ID oraz tabele uczniowie z tabela klasa poprzez ID gdzie ID uzytkownika jest takie same jak podane
                    $wynik3 = mysqli_query($connected, $kwarenda3) or die("Problemy z odczytem danych!");             
                    echo'<table>
                    <tr> 
                        <th> Średnia </th> 
                    </tr>';
                    while($wypisz = mysqli_fetch_row($wynik3)) 
                    {
                        echo "<tr> 
                            <td> $wypisz[0] </td>
                        </tr>";
                    }
                    echo '</table> <br>'; //wyswietla srednia OCEN w tablicy ucznia o podanym ID

                    wyswietl_opis_ocen_uczen($connected);

                    echo "<h4> Średnia z poszczegolnych przedmiotow: </h4>";
                    $kwarenda4 = "SELECT AVG(oceny.Ocena), przedmioty.Przedmiot 
                    FROM oceny 
                    JOIN oceny_uczniow ON oceny.ID = oceny_uczniow.ID_oceny 
                    JOIN uczniowie ON uczniowie.ID = oceny_uczniow.ID_ucznia 
                    JOIN uzytkownicy ON uczniowie.ID_uzytkownika = uzytkownicy.ID 
                    JOIN przedmioty ON przedmioty.ID = oceny_uczniow.ID_przedmiotu 
                    WHERE uzytkownicy.ID = '$ID' 
                    GROUP BY przedmioty.Przedmiot;"; //kwarenda laczaca tabele oceny uczniow z oceny poprzez id tabele 
                    $wynik4 = mysqli_query($connected, $kwarenda4) or die("Problemy z odczytem danych!");
                    echo'<table>
                    <tr> 
                        <th> Średnia </th> 
                        <th> Przedmiot </th> 
                    </tr>';
                    while($wypisz = mysqli_fetch_row($wynik4)) 
                    {
                        echo "<tr> 
                            <td> $wypisz[0] </td>
                            <td> $wypisz[1] </td>
                        </tr>";
                    }
                    echo '</table> <br>'; //wyswietla srednia OCEN z poszczegolnych przedmiotow               
                }
            } 

        echo '</center>'; 
        echo '<form method = "post" action = "http://localhost/iza/librus/uczen.php">
            <input id = "wyloguj" type = "submit" name = "Wyloguj" value = "Wyloguj">
            </form>'; //formularz

        echo '<div id = "prawa"> </div>';

        if(@$_POST["Wyloguj"] == true)
        {
            header("Location: logowanie.php");
        }

        
        echo '<br> <br> <footer> Autor: Iza Mazur </footer>'; //stopka
    ?>
    
</body>
</html>