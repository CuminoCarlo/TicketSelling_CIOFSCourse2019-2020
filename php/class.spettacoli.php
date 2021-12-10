<?php
#GESTIONE DATABASE
require_once("class.mysql.php");

/** 
 * ---------------------------
 * Classe per la gestione delle 
 * pellicole per il software di 
 * project work A.S.2019/20 
 * ---------------------------
*/

class spettacoli {
    private $DB_TABLE = "spettacoli";
	
	private $cod_spettacolo = 0;
	private $titolo 				= "";
	private $autore					= "";
	private $regista				= "";
	private $prezzo					= 0;
	private $cod_cinema			= 0;
    /**
     * inserico una variabile per poter vedere
     * le date degli spettacoli
     */
    private $elencoSpettacoli	= array();

    public function __construct(){
		# controlliamo se esiste un id (COD_SPETTACOLO) spettacolo selezionato
		if(isset($_GET['idSpet'])){
			if(is_numeric($_GET['idSpet'])&&$_GET['idSpet']>0){
				# se è selezionato cerco i valori all'interno del database 
				# e mi autoconfiguro se trovo il cod_spettacolo
				self::select($_GET['idSpet']);
			}
		}
	}

    # Non dovendo andare poi a modificare la tabella, 
    # il metodo set non è obbligatorio.
    public function __GET($a){
		if($a=='id'){return $this->cod_spettacolo;}
		elseif($a=='titolo'){return $this->titolo;}
		elseif($a=='prezzo'){return $this->prezzo;}
		elseif($a=='isSpettacoloSelected'){return $this->cod_spettacolo>0?true:false;}
	}
     
    /**
     * imposta il codice del cinema utilizzato per estrapolare le informazioni
     * dei film (spettacoli) del cinema selezionato 
     */
    public function set_cinema($cod_cinema){$this->cod_cinema = $cod_cinema;}

    
    public function costruisci_elenco(){
		# recupero tutti gli spettacoli
		self::get_all_spettacoli();
		
		# costruisco la tabella dei cinema
		$html = '<table>';
		# INTESTAZIONE TABELLA ELENCO CINEMA
		$html.= '<thead>';
		$html.= '<tr><td colspan="5">Seleziona spettacolo</td></tr>';
		$html.= '<tr><td>Titolo</td><td>Autore</td><td>Regista</td><td>Prezzo</td><td></td></tr>';
		$html.= '</thead>';
		# CORPO DELLA TABELLA ELENCO CINEMA
		$html.= '<tbody>';
		$numeroSpettacoli = 0;
		$nomeCinema = (isset($this->elencoSpettacoli[0]['NOME'])?$this->elencoSpettacoli[0]['NOME']:'');
		
		foreach($this->elencoSpettacoli as $linea){
			$html.= '<tr>';
			$html.= '<td>'.$linea['TITOLO'].'</td>';
			$html.= '<td>'.$linea['AUTORE'].'</td>';
			$html.= '<td>'.$linea['REGISTA'].'</td>';
			$html.= '<td>'.$linea['PREZZO'].' € </td>';
			$html.= '<td><a href="?idCinema='.$this->cod_cinema.
									'&idSpet='.$linea['COD_SPETTACOLO'].'">Seleziona</a></td>';
			$html.= '</tr>';
			$numeroSpettacoli++;
		}
		$html.= '</tbody>';	
		$html.= '<tfoot><tr><td colspan="5">'.
						'Numero spettacoli: '.$numeroSpettacoli.
						' - Cinema: '.$nomeCinema.
						'</td></tr></tfoot>';	
		$html.= '</table>';
		return $html;
	}
    
    public function select($cod_spettacolo){
		$q = "SELECT * FROM `$this->DB_TABLE` WHERE COD_SPETTACOLO = ".$cod_spettacolo;	
		$dati = self::sql($q);
		# se non trova nulla
		if(!$dati){
			# svuoto tutte le mie variabili
			$this->cod_spettacolo = 0;
			$this->titolo				= "";
			$this->autore  			= "";
			$this->regista			= "";
			$this->prezzo				= 0;
			$this->cod_cinema 	= 0;
			return false;
		}
		# altrimenti ho trovati i dati
		# e li inserisco all'interno delle variabili
		$this->cod_spettacolo = (int)$dati[0]['COD_SPETTACOLO'];
		$this->titolo				= $dati[0]['TITOLO'];
		$this->autore  			= $dati[0]['AUTORE'];
		$this->regista			= $dati[0]['REGISTA'];
		$this->prezzo				= $dati[0]['PREZZO'];
		$this->cod_cinema 	= (int)$dati[0]['COD_CINEMA'];
		return true;
    }

    public function get_all_spettacoli(){
		$q = "SELECT * FROM `$this->DB_TABLE` AS spt ".
				 "LEFT JOIN `cinema` AS cnm ON cnm.COD_CINEMA = spt.COD_CINEMA ".
				 "WHERE spt.COD_CINEMA = $this->cod_cinema";
		$dati = self::sql($q);
		if(!$dati){
			$this->elencoSpettacoli = array();
			return false;
		}else{
			$this->elencoSpettacoli = $dati;
			return true;
		}		
	}
     
    private function sql(string $query){
		$db = new database();
		if(!$db->connetti()){return false;}
		
		$dati = $db->esegui($query);
		$db->disconnetti();
		return $dati;
	}



} 

?>