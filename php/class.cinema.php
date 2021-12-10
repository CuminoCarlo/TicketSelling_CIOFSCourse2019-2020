<?php
#GESTIONE DATABASE
require_once("class.mysql.php");

/** 
 * ---------------------------
 * Classe per la gestione delle 
 * sale per il software di 
 * project work A.S.2019/20 
 * ---------------------------
*/

class cinema{
    private $dbTABLE = "cinema";
    private $cod = 0;
    private $nome = "";
    private $indirizzo = "";
    private $citta = "";
    private $provincia="";
    private $telefono = "";
    private $posti = 0;
   
    /**
     * inserico una variabile per poter 
     * creare l'elenco da cui selezionare i cinema
     */
    private $listacinema= array(); 

    
    
    public function __construct(){
		# controlliamo se esiste un id (COD_CINEMA) spettacolo selezionato
		if(isset($_GET['idCinema'])){
            if(is_numeric($_GET['idCinema'])&&$_GET['idCinema']>0){
				self::load_by_cod($_GET['idCinema']);
			    }
	    }
    }

    public function __GET($a){
        if ($a=='id'){return $this->cod;}
        elseif ($a=='nome'){return $this->nome;}
        elseif ($a=='posti'){return $this->posti;}
        #creo una variabile dove leggere tutti i dati dell'indirizzo
        elseif ($a=='indirizzo'){return $this->indirizzo."$this->citta ($this->provincia)";}
        elseif ($a=='telefono'){return $this->telefono;}
        elseif ($a=='isCinemaSelected'){return $this->cod>0?true:false;}
    }    

    #  poiché devo poter visualizzare i cinema in memoria 
    #  creo la funzione "load_by_cod"
    public function load_by_cod(int $cod){
        $query="SELECT * FROM `$this->dbTABLE` WHERE COD_CINEMA =".$cod;
        $result =self::esegui_sql($query);
        #se non trovo nulla
        if (!$result){
        #svuoto le variabili
        $this->cod=0;
        $this->nome="";
        $this->indirizzo="";
        $this->citta="";
        $this->provincia="";
        $this->telefono="";
        $this->posti=0;
        return false;
        }
        # Altrimenti le inserisco nella variabile result
            $this->cod = $result[0]['COD_CINEMA'];
            $this->nome= $result[0]['NOME'];
            $this->indirizzo= $result[0]['INDIRIZZO'];
            $this->citta= $result[0]['CITTA'];
            $this->provincia=$result[0]['PROVINCIA'];
            $this->telefono=$result[0]['TELEFONO'];
            $this->posti=$result[0]['POSTI'];
            return true; 
    } 

     /** funzione per recuperare i dati film 
    * da inserire nell'elenco per la selezione
    */
    public function get_all_cinema() {
        $query="SELECT * FROM `$this->dbTABLE`";
        $result=$this->esegui_sql($query);
        if(!$result){
           $this->listacinema = array();
           return false; 
        }else {
            $this->listacinema =$result;
            return true; 
        }
    } 

    # Tabella per la home
    public function costruisci_lista(){
        self::get_all_cinema();
        $html="<ul>";
        foreach($this->listacinema as $linea){
        $html.="<li>".$linea['NOME']."<br/><small>".$linea['INDIRIZZO']." ".$linea['CITTA']." 
                    (".$linea['PROVINCIA'].")<br/> Tel: ".$linea['TELEFONO']."</small></li>";
        }
        $html.="</ul>";
        return $html;
    }
    
    /**
    * costruisco la tabella per la selezione del cinema
    */
    
    public function costruisci_elenco(){
        self::get_all_cinema();
        $html='<table>';
        $html.='<thead>';
        $html.='<tr><td colspan="5">Seleziona Cinema</td></tr>';
        $html.='<tr><td>Cinema</td><td>Indirizzo</td>';
        $html.='<td>Telefono</td><td>Posti</td><td></td></tr>';
        $html.='</thead>';
        $numeroCinema=0;
        foreach($this->listacinema as $linea){
            $html.='<tr>';
            $html.='<td>'.$linea['NOME'].'</td>';
            $html.='<td>'.$linea['INDIRIZZO'].' '.$linea['CITTA'].' ('.$linea['PROVINCIA'].')</td>';
            $html.='<td>'.$linea['TELEFONO'].'</td>';
            $html.='<td>'.$linea['POSTI'].'</td>';
            $html.='<td><a class="btn" href="?idCinema='.$linea['COD_CINEMA'].'">Seleziona</a></td>';
            $html.='</tr>';
            $numeroCinema++;
        }
        $html.='</tbody>';
		$html.= '<tfoot><tr><td colspan="5">Numero cinema: '.$numeroCinema.'</td></tr></tfoot>';		
        $html.='</table>';
        return $html;
    }
        
    /**
    * A differenza di altre classici non necessito di poter modificare
    * la classe cinema, per cui non avrò bisogno di una funzione save
    * che comprenda i comandi "INSERT" , "UPDATE" e "DELETLE".
    */
    private function esegui_sql(string $query){
        $connessione = new database();
        $connessione->name_db = 'cinema';
        $connessione->connetti();
        $result=$connessione->esegui($query);
        $connessione->disconnetti();
        return $result;
    } 
}


