<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librus</title>
    <link rel = "stylesheet" href = "librus_style.css">
    <style>
        #buton {
            background-color: #E6E6FA;
            border: 2px solid #008080;
            border-style: dashed;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php
        echo '<div id = "srodek"> <center>'; //wysrodkowanie
        function wyswietl_opis_ocen_uczen($connected) //wyswietla opis dla ocen
        {
            $Imie = $_SESSION['Imie'];
            $Nazwisko = $_SESSION['Nazwisko']; //pobiera wartosci z sesji

            echo "<h4> Opis ocen: </h4>";

            $kwarenda = "SELECT ID 
            FROM uczniowie 
            WHERE Imie = '$Imie' 
            AND Nazwisko = '$Nazwisko'"; //kwarenda znajdujaca ID uzytkownika o podanym loginie
            $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");
            $sprawdz = mysqli_num_rows($wynik); //sprawdza ilosc wynikow

            if($sprawdz == 1)
            {
                $podpisz = $wynik -> fetch_assoc(); 
                $ID_ucznia = $podpisz["ID"]; //pobieranie wartosci z kwarendy 

                $kwarenda2 = "SELECT Ocena, Przedmiot 
                FROM uczniowie, oceny_uczniow, oceny, przedmioty 
                WHERE uczniowie.ID = '$ID_ucznia' 
                AND  oceny.ID = oceny_uczniow.ID_oceny 
                AND uczniowie.ID = oceny_uczniow.ID_ucznia 
                AND  przedmioty.ID = oceny_uczniow.ID_przedmiotu"; //kwarenda laczaca tabele uczniowie z oceny_uczniow i oceny z oceny_uczniow oraz przedmioty z oceny uczniow gdzie ID_ucznia jest takie jak pobrane
                $wynik2 = mysqli_query($connected, $kwarenda2) or die("Problemy z odczytem danych!");
                echo'<table>
                <tr> 
                    <th> Oceny </th>  
                    <th> Przedmiot </th> 
                </tr>';
                
                while($wypisz = mysqli_fetch_row($wynik2))
                {   
                    echo "<tr> 
                        <td> $wypisz[0] </td>
                        <td> $wypisz[1] </td>
                    </tr>";      
                }
                echo'</table>';
                session_destroy(); //zamkniecie sesji
            }
            else
            {
                echo "<div id = 'komunikat' >";
                echo " Wystąpil błąd </div>";
            }
            
        }

        //logowanie.php
        function zaloguj($connected) //logowanie
        {
            $Login = $_POST['Login'];
            $Haslo = $_POST['Haslo'];

            $a = "SELECT ID 
            FROM uzytkownicy 
            WHERE Login = '$Login' 
            AND Haslo = '$Haslo' 
            AND Uprawnienia = 'admin'"; //kwerenda do logowanie sprawdzjaca czy haslo i login sie zgadzaja i czuy logujacy jesyt adminem
            $result = mysqli_query($connected, $a) or die("Problemy z odczytem danych!");  
            $czy_login = mysqli_num_rows($result);

            $b = "SELECT ID 
            FROM uzytkownicy 
            WHERE Login = '$Login' 
            AND Haslo = '$Haslo' 
            AND Uprawnienia = 'nauczyciel'"; //kwerenda do logowanie sprawdzjaca czy haslo i login sie zgadzaja i czuy logujacy jesyt nauczycielem
            $result2 = mysqli_query($connected, $b) or die("Problemy z odczytem danych!");  
            $czy_login2 = mysqli_num_rows($result2);

            $c = "SELECT ID 
            FROM uzytkownicy 
            WHERE Login = '$Login' 
            AND Haslo = '$Haslo' 
            AND Uprawnienia = 'uczen'"; //kwerenda do logowanie sprawdzjaca czy haslo i login sie zgadzaja i czuy logujacy jesyt uczniem
            $result3 = mysqli_query($connected, $c) or die("Problemy z odczytem danych!");  
            $czy_login3 = mysqli_num_rows($result3);

            if($czy_login == 1) //jesli jest jeden wynik (oznacza to ze takie haslo i login sa zarejestrowane) przekierowywuje do stony dla admina
            {
                header("Location: admin.php");
            }
            if($czy_login2 == 1) //jest jest jeden wynik przekierowywuje do strony dla nauczyciela
            {
                header("Location: nauczyciel.php"); 
            }
            if($czy_login3 == 1) //jesli jest jeden wynik przekierowywuje do strony dla ucznia
            {
                $_SESSION['Login'] = $Login; //rozpoczecie sesji zeby przeniesc dane na strone ucznia
                header("Location: uczen.php");
            }
            else //jesli zadne dane sie nie zgadzaja wyswietla komunikat 
            {
                echo " <br> Niepoprawne dane! <br>";
            }            
        }

        echo '</center> </div>';
        echo '<div id = "prawa">'; //prawa strona 

        //admin.php
        function wyswietl_opis_ocen_admin($connected) //wyswietla opis ocen
        {
            $Imie = $_SESSION['Imie']; 
            $Nazwisko = $_SESSION['Nazwisko']; //pobiera wartosci z sesji

            echo "<h4> Oceny ucznia: ".$Imie." ".$Nazwisko."</h4>";
        
            $kwarenda = "SELECT ID 
            FROM uczniowie 
            WHERE Imie = '$Imie' 
            AND Nazwisko = '$Nazwisko'"; //kwarenda znajdujaca ID uzytkownika o podanym Imieniu i Nazwisku
            $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");
            $sprawdz = mysqli_num_rows($wynik); //sprawdza ilosc wynikow

            if($sprawdz == 1) //jesli jest jeden wynik
            {
                $podpisz = $wynik -> fetch_assoc(); 
                $ID_ucznia = $podpisz["ID"]; //pobieranie wartosci z kwarendy 

                $kwarenda2 = "SELECT oceny_uczniow.ID, Ocena, Przedmiot, Data 
                FROM uczniowie, oceny_uczniow, oceny, przedmioty 
                WHERE uczniowie.ID = '$ID_ucznia' 
                AND  oceny.ID = oceny_uczniow.ID_oceny 
                AND uczniowie.ID = oceny_uczniow.ID_ucznia 
                AND przedmioty.ID = oceny_uczniow.ID_przedmiotu"; //kwarenda laczaca tabele uczniowie z oceny_uczniow i oceny z oceny_uczniow oraz przedmioty z oceny uczniow gdzie ID_ucznia jest takie jak pobrane
                $wynik2 = mysqli_query($connected, $kwarenda2) or die("Problemy z odczytem danych!");
                echo'<table>
                <tr> 
                    <th> Oceny </th>  
                    <th> Przedmiot </th> 
                    <th> Data dodania/modyfikacji </th> 
                </tr>';
                echo '<form method = "post" action="http://localhost/iza/librus/admin.php">';
                    while($wypisz = mysqli_fetch_row($wynik2))
                    {   
                        echo "<tr> 
                            <td> $wypisz[1] </td>
                            <td> $wypisz[2] </td>
                            <td> $wypisz[3] </td>
                            <td> <button id = 'buton' type = 'submit' name = 'Usun' value = $wypisz[0]> Usuń </button> </td>"; //przycisk Usun
                            $kwarenda = "SELECT ID, Ocena 
                            FROM oceny"; //wyswietla dostepne oceny
                            
                            $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");
                            echo '<td> <select name = "ocena">';
                            while($wypisz2 = mysqli_fetch_row($wynik))
                            {    
                                echo "<option value = '" . $wypisz2[0] . "'>" . $wypisz2[1] . "</option>";           
                            }           
                            echo "</select></td>  
                            <td> <button id = 'buton' type = 'submit' name = 'Edytuj' value = $wypisz[0]> Edytuj </button> </td>
                        </tr>";      
                    }
                echo '</table> </form>';     
                session_destroy(); //zamkniecie sesji
            }
            else
            {
                echo "<div id = 'komunikat'>";
                echo " Wystąpil błąd </div>";
                session_destroy(); //zamkniecie sesji
            }
        }

        function wyswietl_oceny_konkretnych_uczniow_admin($connected) //wyswietla oceny konkretnych uczniow
        {
            $Imie = $_POST['Imie'];
            $Nazwisko = $_POST['Nazwisko']; //przypisuje wartosci pod zmienne

            $kwarenda = "SELECT ID 
            FROM uczniowie 
            WHERE Imie = '$Imie' 
            AND Nazwisko = '$Nazwisko'"; //kwarenda znajdujaca ID uzytkownika o podanym loginie
            $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");
            $sprawdz = mysqli_num_rows($wynik);

            if($sprawdz == 1)
            {
                echo "<h4> Oceny ucznia: ".$Imie." ".$Nazwisko."</h4>";

                $kwarenda2 = "SELECT Ocena 
                FROM uczniowie, oceny_uczniow, oceny 
                WHERE uczniowie.Imie = '$Imie' 
                AND uczniowie.Nazwisko = '$Nazwisko' 
                AND oceny.ID = oceny_uczniow.ID_oceny 
                AND uczniowie.ID = oceny_uczniow.ID_ucznia "; //kwarenda laczaca tabele uczniowie z oceny_uczniow i oceny z oceny_uczniow
                $wynik2 = mysqli_query($connected, $kwarenda2) or die("Problemy z odczytem danych!");
                echo'<table>
                <tr> 
                    <th> Oceny </th>   
                </tr>';
                echo "<tr> <td>";
                while($wypisz = mysqli_fetch_row($wynik2))
                {    
                    echo "$wypisz[0]"." ";       
                }
                echo "</td> </tr>";
                echo '</table>'; //wyswietla IMIE NAZWISKO OCENY ucznia o wybranym ID
                echo '<br> <form method = "post" action = "http://localhost/iza/librus/admin.php"> <input ID = "btn" type = "submit" name = "Rozwin" value = "Rozwiń"> </form> ';
            }
            else
            {
                echo "<div ID = 'komunikaty'> ";
                echo "Upewnij sie ze uczen o podanych danych istenieje! </div>"; //wyswietla komunikat
            }            
        }

        function dodaj_ocene_admin($connected) //dodaje oceny dla danego ucznia
        {
            $Imie = $_SESSION['Imie'];
            $Nazwisko = $_SESSION['Nazwisko'];
            $ID_oceny = $_POST['Ocena'];
            $ID_przedmiotu = $_POST['przedmiot'];  //przypisuje wartosci pod zmienne
            $data = date('Y-m-d');

            $kwarenda = "SELECT ID 
            FROM uczniowie 
            WHERE Imie = '$Imie' 
            AND Nazwisko = '$Nazwisko'"; //kwarenda znajdujaca ID uzytkownika o podanym loginie
            $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");
            $sprawdz = mysqli_num_rows($wynik);

            if($sprawdz == 1)
            {
                $podpisz = $wynik -> fetch_assoc(); 
                $ID_ucznia = $podpisz["ID"]; //pobieranie wartosci z kwarendy 

                $kwarenda2 = "INSERT INTO oceny_uczniow (ID_ucznia, ID_oceny, ID_przedmiotu, Data) 
                VALUES ('$ID_ucznia', '$ID_oceny','$ID_przedmiotu','$data')"; // dodaje do tabeli podane w formularzu wartosci 
                mysqli_query($connected, $kwarenda2) or die("Problemy z odczytem danych!"); 
                
                $kwarenda3 = "SELECT Imie, Nazwisko 
                FROM uczniowie 
                WHERE ID = $ID_ucznia"; //kwarenda wyswietla Imie i Nazwisko ucznia ktorego oceny wyswietlamy 
                $wynik = mysqli_query($connected, $kwarenda3) or die("Problemy z odczytem danych!");
                while($wypisz = mysqli_fetch_row($wynik))
                {
                    echo "<h4> Oceny ucznia: ".$wypisz[0]." ".$wypisz[1]."</h4>"; //wyswietla IMIE NAZWISKO ucznia o poadnym ID
                }

                $kwarenda3 = "SELECT Ocena 
                FROM uczniowie, oceny_uczniow, oceny 
                WHERE uczniowie.ID = '$ID_ucznia' 
                AND  oceny.ID = oceny_uczniow.ID_oceny 
                AND uczniowie.ID = oceny_uczniow.ID_ucznia "; //kwarenda laczaca tabele uczniowie z oceny_uczniow i oceny z oceny_uczniow gdzie ID ucznia jest takie jak podane w formularzu 
                $wynik2 = mysqli_query($connected, $kwarenda3) or die("Problemy z odczytem danych!");
                echo'<table>
                <tr> 
                    <th> Oceny </th>   
                </tr>';
                echo "<tr> <td>";
                while($wypisz = mysqli_fetch_row($wynik2))
                {    
                    echo "$wypisz[0]"." "; 
                }
                echo "</td> </tr> </table>"; //wyswietla OCENY ucznia o wybranym ID
                echo '<br> <form method = "post" action="http://localhost/iza/librus/admin.php"> <input ID = "btn" type = "submit" name = "Rozwin" value = "Rozwiń"> </form> '; //wyswietla IMIE NAZWISKO OCENY ucznia o wybranym ID
            }
            else
            {
                echo "<div ID = 'komunikaty'> ";
                echo "Upewnij sie ze uczen o podanych danych istenieje! </div>"; //wyswietla komunikat
                session_destroy(); //zamkniecie sesji
            }
        }

        //nauczyciel.php
        function wyswietl_oceny_konkretnych_uczniow_nauczyciel($connected) //wyswietla oceny konkretnych uczniow
        {
            $Imie = $_POST['Imie'];
            $Nazwisko = $_POST['Nazwisko']; //przypisuje wartosci pod zmienne

            $kwarenda = "SELECT ID 
            FROM uczniowie 
            WHERE Imie = '$Imie' 
            AND Nazwisko = '$Nazwisko'"; //kwarenda znajdujaca ID uzytkownika o podanym loginie
            $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");
            $sprawdz = mysqli_num_rows($wynik);

            if($sprawdz == 1)
            {
                echo "<h4> Oceny ucznia: ".$Imie." ".$Nazwisko."</h4>";

                $kwarenda2 = "SELECT Ocena 
                FROM uczniowie, oceny_uczniow, oceny 
                WHERE uczniowie.Imie = '$Imie' 
                AND uczniowie.Nazwisko = '$Nazwisko' 
                AND oceny.ID = oceny_uczniow.ID_oceny 
                AND uczniowie.ID = oceny_uczniow.ID_ucznia "; //kwarenda laczaca tabele uczniowie z oceny_uczniow i oceny z oceny_uczniow
                $wynik2 = mysqli_query($connected, $kwarenda2) or die("Problemy z odczytem danych!");
                echo'<table>
                <tr> 
                    <th> Oceny </th>   
                </tr>';
                echo "<tr> <td>";
                while($wypisz = mysqli_fetch_row($wynik2))
                {    
                    echo "$wypisz[0]"." ";       
                }
                echo "</td> </tr>";
                echo '</table>'; //wyswietla IMIE NAZWISKO OCENY ucznia o wybranym ID
                echo '<br> <form method = "post" action = "http://localhost/iza/librus/nauczyciel.php"> <input ID = "btn" type = "submit" name = "Rozwin" value = "Rozwiń"> </form> ';
            }
            else
            {
                echo "<div ID = 'komunikaty'> ";
                echo "Upewnij sie ze uczen o podanych danych istenieje! </div>"; //wyswietla komunikat
            }            
        }

        function wyswietl_opis_ocen_nauczyciel($connected) //wyswietla opis ocen
        {
            $Imie = $_SESSION['Imie']; 
            $Nazwisko = $_SESSION['Nazwisko']; //pobiera wartosci z sesji

            echo "<h4> Oceny ucznia: ".$Imie." ".$Nazwisko."</h4>";
        
            $kwarenda = "SELECT ID 
            FROM uczniowie 
            WHERE Imie = '$Imie' 
            AND Nazwisko = '$Nazwisko'"; //kwarenda znajdujaca ID uzytkownika o podanym Imieniu i Nazwisku
            $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");
            $sprawdz = mysqli_num_rows($wynik); //sprawdza ilosc wynikow

            if($sprawdz == 1) //jesli jest jeden wynik
            {
                $podpisz = $wynik -> fetch_assoc(); 
                $ID_ucznia = $podpisz["ID"]; //pobieranie wartosci z kwarendy 

                $kwarenda2 = "SELECT oceny_uczniow.ID, Ocena, Przedmiot, Data 
                FROM uczniowie, oceny_uczniow, oceny, przedmioty 
                WHERE uczniowie.ID = '$ID_ucznia' 
                AND  oceny.ID = oceny_uczniow.ID_oceny 
                AND uczniowie.ID = oceny_uczniow.ID_ucznia 
                AND  przedmioty.ID = oceny_uczniow.ID_przedmiotu"; //kwarenda laczaca tabele uczniowie z oceny_uczniow i oceny z oceny_uczniow oraz przedmioty z oceny uczniow gdzie ID_ucznia jest takie jak pobrane
                $wynik2 = mysqli_query($connected, $kwarenda2) or die("Problemy z odczytem danych!");
                echo'<table>
                <tr> 
                    <th> Oceny </th>  
                    <th> Przedmiot </th> 
                    <th> Data dodania/modyfikacji </th> 
                </tr>';
                echo '<form method = "post" action="http://localhost/iza/librus/nauczyciel.php">';
                while($wypisz = mysqli_fetch_row($wynik2))
                {   
                    echo "<tr> 
                        <td> $wypisz[1] </td>
                        <td> $wypisz[2] </td>
                        <td> $wypisz[3] </td>
                        <td> <button id = 'buton' type = 'submit' name = 'Usun' value = $wypisz[0]> Usuń </button> </td>";
                        $kwarenda = "SELECT ID, Ocena 
                        FROM oceny"; //wyswietla dostepne przedmioty
                        $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");
                        echo '<td> <select name = "ocena">';
                        while($wypisz2 = mysqli_fetch_row($wynik))
                        {    
                            echo "<option value = '" . $wypisz2[0] . "'>" . $wypisz2[1] . "</option>"; //wyswietla dostepne przedmioty jako select 
                
                        }           
                        echo "</select></td>  
                        <td> <button id = 'buton' type = 'submit' name = 'Edytuj' value = $wypisz[0]> Edytuj </button> </td>
                    </tr>";      
                }
                echo '</table> </form>';     
                session_destroy(); //zamkniecie sesji
            }
            else
            {
                echo "<div id = 'komunikat'>";
                echo " Wystąpil błąd </div>";
                session_destroy(); //zamkniecie sesji
            }
        }

        function dodaj_ocene_nauczyciel($connected) //dodaje oceny dla danego ucznia
        {
            $Imie = $_SESSION['Imie'];
            $Nazwisko = $_SESSION['Nazwisko'];
            $ID_oceny = $_POST['Ocena'];
            $ID_przedmiotu = $_POST['przedmiot'];  //przypisuje wartosci pod zmienne
            $data = date('Y-m-d');

            $kwarenda = "SELECT ID 
            FROM uczniowie 
            WHERE Imie = '$Imie' 
            AND Nazwisko = '$Nazwisko'"; //kwarenda znajdujaca ID uzytkownika o podanym loginie
            $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");
            $sprawdz = mysqli_num_rows($wynik);

            if($sprawdz == 1)
            {
                $podpisz = $wynik -> fetch_assoc(); 
                $ID_ucznia = $podpisz["ID"]; //pobieranie wartosci z kwarendy 

                $kwarenda2 = "INSERT INTO oceny_uczniow (ID_ucznia, ID_oceny, ID_przedmiotu, Data) 
                VALUES ('$ID_ucznia', '$ID_oceny','$ID_przedmiotu','$data')"; // dodaje do tabeli podane w formularzu wartosci 
                mysqli_query($connected, $kwarenda2) or die("Problemy z odczytem danych!"); 
                
                $kwarenda3 = "SELECT Imie, Nazwisko 
                FROM uczniowie 
                WHERE ID = $ID_ucznia"; //kwarenda wyswietla Imie i Nazwisko ucznia ktorego oceny wyswietlamy 
                $wynik = mysqli_query($connected, $kwarenda3) or die("Problemy z odczytem danych!");
                while($wypisz = mysqli_fetch_row($wynik))
                {
                    echo "<h4> Oceny ucznia: ".$wypisz[0]." ".$wypisz[1]."</h4>"; //wyswietla IMIE NAZWISKO ucznia o poadnym ID
                }

                $kwarenda3 = "SELECT Ocena 
                FROM uczniowie, oceny_uczniow, oceny 
                WHERE uczniowie.ID = '$ID_ucznia' 
                AND  oceny.ID = oceny_uczniow.ID_oceny 
                AND uczniowie.ID = oceny_uczniow.ID_ucznia "; //kwarenda laczaca tabele uczniowie z oceny_uczniow i oceny z oceny_uczniow gdzie ID ucznia jest takie jak podane w formularzu 
                $wynik2 = mysqli_query($connected, $kwarenda3) or die("Problemy z odczytem danych!");
                echo'<table>
                <tr> 
                    <th> Oceny </th>   
                </tr>';
                echo "<tr> <td>";
                while($wypisz = mysqli_fetch_row($wynik2))
                {    

                    echo "$wypisz[0]"." "; 
        
                }
                echo "</td> </tr>";

                echo '</table> <br> <form method = "post" action="http://localhost/iza/librus/nauczyciel.php"> <input ID = "btn" type = "submit" name = "Rozwin" value = "Rozwiń"> </form> '; //wyswietla IMIE NAZWISKO OCENY ucznia o wybranym ID
            echo '</table>'; //wyswietla OCENY ucznia o wybranym ID
            }
            else
            {
                echo "<div ID = komunikaty> ";
                echo "Upewnij sie ze uczen o podanych danych istenieje! </div>"; //wyswietla komunikat
            }
        }

        //admin.php i nauczyciel.php
        function dodaj_klase($connected) //dodwanie klasy
        {
            $Klasa = $_POST['Klasa']; //przypisuje wartosci pod zmienne

            $kwarenda = "INSERT INTO klasa (Klasa) 
            VALUES ('$Klasa')"; //kwarenda dodajaca klase do tabeli klasa
            mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");   
            
            wyswietl_klasy($connected); //wyswietla klasy
        }
        
        function dodaj_przedmiot($connected) //wyswietla przedmioty
        {
            $Przedmiot = $_POST['Przedmiot'];

            $kwarenda = "SELECT ID 
            FROM przedmioty 
            WHERE Przedmiot = '$Przedmiot'"; //kwarenda znajdujaca ID uzytkownika o podanym loginie
            $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");
            $sprawdz = mysqli_num_rows($wynik);

            if($sprawdz == 1)
            {
                echo "<div id = 'komunikaty'>";
                echo "Taki przedmiot juz istnieje </div>";
            }
            else
            {
                $kwarenda = "INSERT INTO przedmioty (Przedmiot) 
                VALUES ('$Przedmiot')"; //wyswietla dostepne przedmioty
                mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");
                header("Location: admin.php");
            }           
        }

        function edytuj_oceny($connected, $ID, $Nowa_ocena) //edytuj ocene dla konkretnego ucznia
        {
            $data = date('Y-m-d');
            $kwarenda = "UPDATE oceny_uczniow 
            SET ID_oceny = $Nowa_ocena, Data = '$data' 
            WHERE ID = $ID"; // dodaje do tabeli podane w formularzu wartosci 
            echo "<div id = 'komunikaty'> Pomyślnie zmieniono ocene na: ".$Nowa_ocena."</div>";
            mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");   
        }

        function usun_ocene($connected, $ID) //usuwa ucznia i jego oceny
        { 
            $kwarenda = "DELETE FROM oceny_uczniow 
            WHERE ID = $ID"; //usuwa ocene o podanym id
            mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!"); 

            echo "<div ID = 'komunikaty'> ";
            echo "Usunięto ocene </div>"; //wyswietla komunikat

            session_destroy(); //zamkniecie sesji  
        }

        function wyswietl_uczniow_klas($connected) //wyswietla uczniow dla danej klasy
        {
            $ID_klasy = $_POST['Klasa']; //przypisuje wartosci pod zmienne

            $kwarenda = "SELECT Klasa 
            FROM klasa 
            WHERE ID = $ID_klasy"; //kwerenda wyswietlajaca klase o podanym ID
            $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");

            while($wypisz = mysqli_fetch_row($wynik)) 
            {
                echo "<h4> Uczniowie klasy ".$wypisz[0]."</h4>"; //wyswietla KLASE
            }           

            $kwarenda2 = "SELECT uczniowie.ID, Imie, Nazwisko, klasa.Klasa 
            FROM uczniowie, klasa 
            WHERE uczniowie.ID_klasa = klasa.ID 
            AND uczniowie.ID_klasa = $ID_klasy"; //kwarenda laczaca tabele uczniowie z tabela klase dla uczniow ktorych klasa zgadza sie z ta podana w formularzu
            $wynik2 = mysqli_query($connected, $kwarenda2) or die("Problemy z odczytem danych!");

            echo'<table>
            <tr> 
                <th> ID </th> 
                <th> Imie </th> 
                <th> Nazwisko </th> 
                <th> Klasa </th> 
            </tr>';
            while($wypisz = mysqli_fetch_row($wynik2)) 
            {
                echo "<tr> 
                    <td> $wypisz[0] </td>
                    <td> $wypisz[1] </td>
                    <td> $wypisz[2] </td>
                    <td> $wypisz[3] </td>
                </tr>";
            }
            echo '</table>'; //wyswietla ID IMIE NAZWISKO KLASA dla uczniow z danej klasy
        }

        function wyswietl_srednia($connected) //wyswietla srednia danego ucznia
        {
            $Imie = $_POST['Imie'];
            $Nazwisko = $_POST['Nazwisko'];  //przypisuje wartosci pod zmienne

            $kwarenda = "SELECT ID 
            FROM uczniowie 
            WHERE Imie = '$Imie' 
            AND Nazwisko = '$Nazwisko'"; //kwarenda znajdujaca ID uzytkownika o podanym loginie
            $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");
            $sprawdz = mysqli_num_rows($wynik);

            if($sprawdz == 1)
            {
                echo "<h4> Średnia ucznia: ".$Imie." ".$Nazwisko."</h4>";

                $kwarenda = "SELECT avg(Ocena) 
                FROM uczniowie, oceny_uczniow, oceny 
                WHERE uczniowie.Imie = '$Imie' 
                AND uczniowie.Nazwisko = '$Nazwisko' 
                AND oceny.ID = oceny_uczniow.ID_oceny 
                AND uczniowie.ID = oceny_uczniow.ID_ucznia"; //kwarenda laczaca dwie tabele uczniowie z oceny_uczniow i oceny z oceny_uczniow
                $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");

                echo'<table>
                <tr> 
                    <th> Srednia </th> 
                </tr>';
                while($wypisz = mysqli_fetch_row($wynik)) 
                {
                    echo "<tr> 
                        <td> $wypisz[0] </td>
                    </tr>";
                }
                echo '</table>'; //wyswietla ID IMIE NAZWISKO srednia OCEN w tablicy ucznia o pobranym ID
            }
            else 
            {
                echo "<div ID = 'komunikaty'> ";
                echo "Upewnij sie ze uczen o podanych danych istenieje! </div>"; //wyswietla komunikat
            }
        }

        function wyswietl_uczniow($connected) //wyswietla wszystkich uczniow
        {
            echo "<h4> Uczniowie: </h4>";

            $kwarenda = "SELECT uczniowie.ID, Imie, Nazwisko, klasa.Klasa 
            FROM uczniowie, klasa 
            WHERE uczniowie.ID_klasa = klasa.ID"; //kwarenda wyswietlajaca wszystkich uczniow
            $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");
            echo'<table>
            <tr> 
                <th> ID </th> 
                <th> Imie </th> 
                <th> Nazwisko </th> 
                <th> Klasa </th> 
            </tr>';
            while($wypisz = mysqli_fetch_row($wynik))
            {
                echo "<tr> 
                    <td> $wypisz[0] </td>
                    <td> $wypisz[1] </td>
                    <td> $wypisz[2] </td>
                    <td> $wypisz[3] </td>
                </tr>";
            }
            echo '</table>'; //wyswietla ID IMIE NAZWISKO KLASE wszystkih uczniow
        }

        function wyswietl_nauczycieli($connected) //wyswietla wszystkich uczniow
        {
            echo "<h4> Nauczyciele: </h4>";

            $kwarenda = "SELECT nauczyciele.ID, Imie, Nazwisko 
            FROM nauczyciele"; //kwarenda wyswietlajaca wszystkich nauczycieli
            $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");
            echo'<table>
            <tr> 
                <th> ID </th> 
                <th> Imie </th> 
                <th> Nazwisko </th> 
            </tr>';
            while($wypisz = mysqli_fetch_row($wynik))
            {
                echo "<tr> 
                    <td> $wypisz[0] </td>
                    <td> $wypisz[1] </td>
                    <td> $wypisz[2] </td>
                </tr>";
            }
            echo '</table>'; //wyswietla ID IMIE NAZWISKO wszystkih nauczycieli
        }

        function wyswietl_klasy($connected) //wyswietla klasy
        {
            echo '<h4> Klasy: </h4>';
            echo '<table>
            <tr> 
                <th> ID </th> 
                <th> Klasa </th>  
            </tr>';

            $kwarenda = "SELECT ID, Klasa 
            FROM klasa;"; //kwerenda wyswietlajaca ID i Klasa 
            $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");
            while($wypisz = mysqli_fetch_row($wynik)) // wyswietla klasy
                {
                    echo "<tr> 
                    <td> $wypisz[0] </td>
                    <td> $wypisz[1] </td>
                    </tr>";
                }
            echo '</table>'; //wyswietla ID KLASA
        }

        function wyswietl_uzytkownikow($connected) //wyswietla wszystkich uzytkownikow
        {
            echo "<h4> Użytkownicy: </h4>";

            $kwarenda = "SELECT uzytkownicy.ID, Imie, Nazwisko, Login, Haslo, Uprawnienia 
            FROM uzytkownicy 
            JOIN nauczyciele ON nauczyciele.ID_uzytkownika = uzytkownicy.ID "; //kwarenda laczaca tabele uzytkownicy z tabela nauczyciele poprzez ID
            $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");

            $kwarenda2 = "SELECT uzytkownicy.ID, Imie, Nazwisko, Login, Haslo, Uprawnienia 
            FROM uzytkownicy 
            JOIN uczniowie ON uczniowie.ID_uzytkownika = uzytkownicy.ID "; //kwarenda laczaca tabele uzytkownicy z tabela uczniowie poprzez ID
            $wynik2 = mysqli_query($connected, $kwarenda2) or die("Problemy z odczytem danych!");

            echo'<table>
            <tr> 
                <th> ID </th> 
                <th> Imie </th> 
                <th> Nazwisko </th> 
                <th> Login </th> 
                <th> Haslo </th> 
                <th> Uprawnienia </th> 
            </tr>';
            
            while($wypisz = mysqli_fetch_row($wynik))
            {
                echo "<tr>
                    <td> $wypisz[0] </td>
                    <td> $wypisz[1] </td>
                    <td> $wypisz[2] </td>
                    <td> $wypisz[3] </td>
                    <td> $wypisz[4] </td>
                    <td> $wypisz[5] </td> </tr>";
            } //wyswietla ID IMIE NAZWISKO LOGIN HASLO I UPRAWNIENIA 

            while($wypisz = mysqli_fetch_row($wynik2))
            {
                echo "<tr>
                    <td> $wypisz[0] </td>
                    <td> $wypisz[1] </td>
                    <td> $wypisz[2] </td>
                    <td> $wypisz[3] </td>
                    <td> $wypisz[4] </td>
                    <td> $wypisz[5] </td> </tr>";
                
            } //wyswietla ID IMIE NAZWISKO LOGIN HASLO I UPRAWNIENIA 
            echo '</table>';
        }

        //dodawanie_usuwanie_uzytkownikow.php
        function usun_uzytkownika($connected) //usuwanie uzytkownikow
        {
            $ID = $_POST['ID']; //przypisuje wartosci pod zmienne

            $kwarenda = "DELETE FROM uzytkownicy 
            WHERE ID = $ID"; //usuwa ucznia z tabeli uczniowie 
            mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");  

            $kwarenda2 = "DELETE FROM oceny_uczniow 
            WHERE ID_ucznia = 
            (SELECT ID 
            FROM uczniowie 
            WHERE ID_uzytkownika = $ID)"; //usuwa oceny dla ucznia ktorego ID_uzytkownika jest takie jak podane
            mysqli_query($connected, $kwarenda2) or die("Problemy z odczytem danych!"); 

            $kwarenda3 = "DELETE FROM uczniowie 
            WHERE ID_uzytkownika = $ID"; //usuwa ucznia z tabeli uczniowie 
            mysqli_query($connected, $kwarenda3) or die("Problemy z odczytem danych!");

            $kwarenda4 = "DELETE FROM nauczyciele 
            WHERE ID_uzytkownika = $ID"; //usuwa ucznia z tabeli uczniowie 
            mysqli_query($connected, $kwarenda4) or die("Problemy z odczytem danych!"); 
        }

        function dodawanie($connected) //dodawanie uzytkownikow
        {
            $Wybor = $_POST['wybor'];
            $Login = $_POST['Login'];
            $Haslo = $_POST['Haslo'];
            $Imie = $_POST['Imie'];
            $Nazwisko = $_POST['Nazwisko'];
            $ID_klasy = $_POST['Klasa']; //przypisywanie pod zmienne wartosci z formularza

            $kwarenda = "SELECT ID 
            FROM uzytkownicy 
            WHERE '$Login' = Login"; //kwerenda sprawdzjaca czy jest juz uzytkownik o podanym loginie
            $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");  
            $czy_login = mysqli_num_rows($wynik); //ilosc wyników

            if($czy_login == 1) //jesli ilosc wyników jest rowna 1 oznacza ze login jest juz zajety
            {
                echo "<br>";
                echo "Ten login jest juz zajety";
                echo "<br>";
            }
            else //w przeciwnym przypadku
            {
                if($Wybor == 'uczen') //jesli dodawany uzytkownik jest uczniem
                {
                    $a = "INSERT INTO uczniowie (Imie, Nazwisko, ID_klasa, ID_uzytkownika) 
                    VALUES ('$Imie', '$Nazwisko','$ID_klasy','0')"; // dodaje do tabeli podane w formularzu wartosci 
                    $result = mysqli_query($connected, $a) or die("Problemy z odczytem danych!"); 

                    $b = "INSERT INTO uzytkownicy (Login, Haslo, Uprawnienia) 
                    VALUES ('$Login', '$Haslo','$Wybor')"; // dodaje do tabeli podane w formularzu wartosci 
                    $result2 = mysqli_query($connected, $b) or die("Problemy z odczytem danych!"); 

                    $c = "UPDATE uczniowie, uzytkownicy 
                    SET ID_uzytkownika = uzytkownicy.ID 
                    WHERE '$Login' = uzytkownicy.Login 
                    AND uczniowie.Imie = '$Imie' 
                    AND uczniowie.ID_uzytkownika = 0"; //kwarenda zmieniajaca dane ID_uzytkownika na ID w tabeli uzytkownicy gdzie Login i imie zgadzaja sie z podanymi danymi oraz ID_uzytkownika jest rowne 0
                    $result3 = mysqli_query($connected, $c) or die("Problemy z odczytem danych!"); 
                }
                else if($Wybor == 'nauczyciel') //jesli podany uzytkownik jest nauczycielem
                {
                    $a = "INSERT INTO nauczyciele (Imie, Nazwisko, ID_uzytkownika) 
                    VALUES ('$Imie', '$Nazwisko','0')"; // dodaje do tabeli podane w formularzu wartosci 
                    $result = mysqli_query($connected, $a) or die("Problemy z odczytem danych!"); 

                    $b = "INSERT INTO uzytkownicy (Login, Haslo, Uprawnienia) 
                    VALUES ('$Login', '$Haslo','$Wybor')"; // dodaje do tabeli podane w formularzu wartosci 
                    $result2 = mysqli_query($connected, $b) or die("Problemy z odczytem danych!"); 

                    $c = "UPDATE nauczyciele, uzytkownicy 
                    SET ID_uzytkownika = uzytkownicy.ID 
                    WHERE '$Login' = uzytkownicy.Login 
                    AND nauczyciele.Imie = '$Imie' 
                    AND nauczyciele.ID_uzytkownika = 0"; //kwarenda zmieniajaca dane ID_uzytkownika na ID w tabeli uzytkownicy gdzie Login i imie zgadzaja sie z podanymi danymi oraz ID_uzytkownika jest rowne 0
                    $result3 = mysqli_query($connected, $c) or die("Problemy z odczytem danych!"); 
                }          
            }                 
        }
        
        echo '</div>';

    ?>
</body>
</html>