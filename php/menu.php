<?php
      function costruisci_menu(){
        $html="<header><nav>
               <ul id='menu'>
               <li><a href='index.php'>Home</a></li>
                <li><a href='cinema.php'>Cinema</a>
                   <ul>
                       <li><a href='cinema.php?idCinema=1'>Monterosa</a></li> 
                       <li><a  href='cinema.php?idCinema=2'>Agnelli</a></li>
                       <li><a  href='cinema.php?idCinema=3'>Piccolo Valdocco</a></li>
                    </ul></li>
                   <li><a href=#>Contatti</a></li>";
        if(isset($_SESSION['login'])&&is_array($_SESSION['login'])){
           $html.="<li class='btn_menu'><a href='function.logout.php'>Logout</a></li>";
           $html.="<li class='btn_menu'><a href='prenotazioni.php'>Prenotazioni</a></li>";
           $html.="<li class='nome_utente'>".$_SESSION['login']['NOME']." ".$_SESSION['login']['COGNOME']."</li>";
        } else{
            $html.="<li class='btn_menu'><a href=page_register.php>Registrati</a></li>";
            $html.="<li class='btn_menu'><a href=page_login.php>Accedi</a></li>";
        } 
        $html.='</ul></nav></header>';
        return $html;
     }
?>