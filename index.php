<?php 
session_start();
require_once('php/menu.php');


?>

<html>

    <head>
        <!-- Nella head sono inclusi elementi di conoscenze pregresse  -->
        <title>Cineclick - Home Page</title>
        <meta charset="utf-8"/> 
        <link href="css/style.css" rel="stylesheet" type="text/css">
    </head> 

    <body>
    <div class="container">
        <?php
                echo costruisci_menu();
            ?>
        <div class="section">
                    <img src='img/logo.png' alt="Logo col nome del sito" class="logo">
            </div>
            <div class=clearfix></div>
            <div class="section">
                <div class=sezione_sinistra>
                    <div id="testo_sezione">
                        <h3>SCEGLI IL FILM</h3>
                        <p>Clicca sul film di tuo interesse per vedere in che cinema è proiettato 
                         e prenotarlo in pochi click.</p>
                        <table><tbody>
                            <tr><td>
                                <a class="locandina" href='cinema.php?idCinema=2&idSpet=4'><img src="img\locandina_Black_Widow.jpg" alt="Locandina Black widow" width="200" height="300"></a><br/><p>Black Widow</p></td>
                                <td><a class="locandina" href='cinema.php?idCinema=2&idSpet=2'><img src="img\locandina_Dolittle.jpg" alt="Locandina dolittle" width="200" height="300"></a><br/><p>Dolittle</p></td>
                                <td><a class="locandina" href='cinema.php?idCinema=2&idSpet=1'><img src="img\locandina_Piccolo_Principe.jpg" alt="Locandina piccolo principe" width="200" height="300"></a><br/><p>Il piccolo principe</p>
                            </td></tr>
                            <tr><td>
                                <a class="locandina" href='cinema.php?idCinema=2&idSpet=3'> <img src="img\locandina_odio_estate.jpg" alt="Locandina odio l'estate" width="200" height="300"></a><br/><p>Odio l'estate</p></td>
                                <td><a class="locandina" href='cinema.php?idCinema=2&idSpet=6'><img src="img\locandina_ghostbuster_legacy.jpg" alt="Ghostbuster Legacy" width="200" height="300"></a><br/><p>Ghostbuster Legacy</p></td>
                                <td><a class="locandina" href='cinema.php?idCinema=2&idSpet=5'><img src="img\locandina_18_regali.jpg" alt="Locandina 18 regali" width="200" height="300"></a><br/><p>18 regali</p>
                            </td></tr>
                        </tbody></table>
                    </div>
                </div>

            <div class="sezione_desta">
                <div id="testo_sezione" class="testi">
                    <h3>I NOSTRI CINEMA</h3>
                    <ol>
                        <li><b>Cinema Monterosa</b><br/><small>Via Brandizzo 65 - Torino (To) Tel: 011/2304153</small></li>
                        <li><b>Cinema Agnelli</b><br/><small>Via Paolo Sarpi 111  - Torino (To) Tel: 011/6198399</small></li>
                        <li><b>Cinema Piccolo Valdocco</b><br/><small>Via Salerno 12 - Torino (To) Tel: 011/5224279</small></li>
                    </ol>
                </div>
                <div class=clearfix></div>
            </div>
           
        </div>
        <div class="footer"><p>Sito realizzato da Carlo Cumino<br/>Copytright 2020</p></div> 
        
        
    </body>

</html>