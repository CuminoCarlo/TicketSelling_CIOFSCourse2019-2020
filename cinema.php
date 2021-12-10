<?php 
session_start();
include('php/menu.php');
require_once('php/class.clienti.php');
require_once('php/class.mysql.php');
require_once('php/class.cinema.php');
require_once('php/class.spettacoli.php');
require_once('php/class.repliche.php');
require_once('php/class.biglietti.php');

#istanza classe per la gestione della classe dei clienti
$cliente=new clienti();
#istanza classe per la gestione della classe dei cinema
$cinema= new cinema();
#istanza classe per la gestione della classe dei film
$spettacolo=new spettacoli();
# istanza classe per la gestione della classe repliche
$replica		= new repliche();
# istanza classe per la gestione della classe dei biglietti
$biglietto = new biglietti();

#richiamo per costruire il menù
echo costruisci_menu();

?>
<html>
    <head>
        <!-- Nella head sono inclusi elementi di conoscenze pregresse  -->
        <title>Selezione cinema</title>
        <meta charset="utf-8"/> 
        <link href="css/style.css" rel="stylesheet" type="text/css">
    </head> 

    <body>
    <div class="container">

<img src='img/logo.png' alt="Logo col nome del sito" width="150" height="100">
<h2>Compra qui il tuo biglietto</h2>
<p><small>Per iniziare l'acquisto del biglietto seleziona prima il cinema, poi il film e infine la data.
            Poi potrai procedere all'acquisto!<br/><b>RICORDA CHE DEVI ESSERE REGISTRATO!</b> 
</small></p>

    <div class="section">
        <div id="testi">
            <?php
            # STEP 1: selezione del cinema
            if(!$cinema->isCinemaSelected){ echo $cinema->costruisci_elenco();}
            else{
                # STEP 2: selezione dello spettacolo
                $spettacolo->set_cinema($cinema->id);
                if(!$spettacolo->isSpettacoloSelected){ echo $spettacolo->costruisci_elenco();}
                else{	
    
                # STEP 3: selezione della replica
                $replica->set_cinema($cinema->id);
                $replica->set_spettacolo($spettacolo->id);
                if(!$replica->isReplicaSelected){ echo $replica->costruisci_elenco();}
                else{
                    # STEP 4: effettua acquisto dei biglietti
                    $biglietto->set_cinema($cinema->id);
                    $biglietto->set_cinema_posti($cinema->posti);
                    $biglietto->set_replica($replica->id);
                    $biglietto->set_data_replica($replica->data);
                    $biglietto->set_spettacolo($spettacolo->id);

                     echo $biglietto->acquista();
                    } #Fine STEP 3
                } #Fine STEP 2
            } #Fine STEP 1
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