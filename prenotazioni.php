<?php 
    session_start();
    include('php/menu.php');
    require_once('php/class.mysql.php');

    #istanza classe carrello
    require_once('php/class.carrello.prova.php');

    #richiamo per costruire il menù
    echo costruisci_menu();
?>

<html>
    <head>
        <!-- Nella head sono inclusi elementi di conoscenze pregresse  -->
        <title>Cinema Click - Area personale</title>
        <meta charset="utf-8"/> 
        <link href="css/style.css" rel="stylesheet" type="text/css">
    </head> 

    <body>
        <div class="container">

        <img src='img/logo.png' alt="Logo col nome del sito" width="150" height="100">
<h2>Controlla le tue prenotazioni</h2>
<p>BENVENUTO!<br/><small>Questa è la tua pagina personale dove puoi tenere sotto controllo i biglietti da te prenotati! 
</small></p>

            <div class="section">
                <div id="testi">
                    <?php 
                        $prenotazione=new carrello();
                        echo $prenotazione->costruisci_tabella();
                    ?>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="clearfix"></div>

        </div>
        <div class="footer"><p>Sito realizzato da Carlo Cumino<br/>
Copytright 2020</p></div>
    </body>


</html>