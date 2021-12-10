<?php
#GESTIONE DATABASE
require_once("class.mysql.php");

/** 
 * ---------------------------
 * Classe per la gestione delle 
 * proiezioni per il software di 
 * project work A.S.2019/20 
 * ---------------------------
*/

class repliche {
    private $DB_TABLE = "repliche";
	
	private $cod_spettacolo = 0;
	private $cod_replica		= 0;
	private $data						= "";
	
	private $cod_cinema			= 0;
	private $elencoRepliche	= array();

    public function __construct(){
		# controlliamo se esiste un id (COD_REPLICA) spettacolo selezionato
		if(isset($_GET['idRep'])){
			if(is_numeric($_GET['idRep'])&&$_GET['idRep']>0){
				# se è selezionato cerco i valori all'interno del database 
				# e mi autoconfiguro se trovo il cod_spettacolo
				self::select($_GET['idRep']);
			}
		}
    }

    public function __GET($a){
        if ($a=='id') {return $this->cod;}
        elseif ($a=='data') {return $this->data;}
        elseif($a=='isReplicaSelected'){return $this->cod_replica>0?true:false;}
    }

    /**
     * Funzione per estrapolare le informazioni
     * del cinema selezionato 
     */
    public function set_cinema($cod_cinema){$this->cod_cinema=$cod_cinema;}
    
    /**
    * Funzione per estrapolare le informazioni
    * del film (spettacolo) selezionato 
    */
   public function set_spettacolo($cod_spettacolo){$this->cod_spettacolo=$cod_spettacolo;}

    #converto la data dal formato americano a quello italiano
    public function convertDate($date,$pattern='d/m/Y'){
        return date ($pattern,strtotime($date));
    } 

    /**
     * Funzione per caricare all'interno della 
     * 
     */

    public function select($cod_replica){
		$q = "SELECT * FROM `$this->DB_TABLE` WHERE COD_REPLICA = ".$cod_replica;	
		$dati = self::sql($q);
		# se non trova nulla
		if(!$dati){
			# svuoto tutte le mie variabili
			$this->cod_replica = 0;
			$this->data				 = "";
			return false;
		}
		# altrimenti ho trovati i dati
		# e li inserisco all'interno delle variabili
		$this->cod_replica = (int)$dati[0]['COD_REPLICA'];
		$this->data				= $dati[0]['DATA_REPLICA'];
		return true;
	}

   /**
	 * recupero di tutti gli spettacoli per uno specifico cinema
	 * @return (bool)
	 */
	public function get_all_repliche(){
		$q = "SELECT rpl.COD_SPETTACOLO, rpl.COD_REPLICA, rpl.DATA_REPLICA, ".
				 "spt.TITOLO, spt.AUTORE, spt.PREZZO, cnm.NOME AS CINEMA, ".
				 "cnm.POSTI AS TOTALI, bgt.BIGLIETTI_venduti AS VENDUTI ".
			
				 "FROM `$this->DB_TABLE` AS rpl ".
					// recupero le informazioni sullo spettacolo
				 "LEFT JOIN `spettacoli` AS spt ON spt.COD_SPETTACOLO = rpl.COD_SPETTACOLO ".
					// recupero le informazioni sul cinema
				 "LEFT JOIN `cinema` AS cnm ON cnm.COD_CINEMA = spt.COD_CINEMA ".
					// recupero le informazioni sui posti disponibili
				 "LEFT JOIN `biglietti_venduti_spettacolo` AS bgt ON ".
						"bgt.COD_CINEMA = cnm.COD_CINEMA AND bgt.COD_REPLICA = rpl.COD_REPLICA ".
			
				 "WHERE rpl.COD_SPETTACOLO = $this->cod_spettacolo";
		$dati = self::sql($q);
		
		if(!$dati){
			$this->elencoRepliche = array();
			return false;
		}else{
			$this->elencoRepliche = $dati;
			return true;
		}		
	}

   /**
	 * costruzione di tabella HTML con l'elenco degli spettacoli per cinema
	 * @return (string) HTML
	 */
	public function costruisci_elenco(){
		# recupero tutti le repliche
		self::get_all_repliche();
		
		$html = '<table>';
		# INTESTAZIONE TABELLA ELENCO CINEMA
		$html.= '<thead>';
		$html.= '<tr><td colspan="4">Seleziona data replica</td></tr>';
		$html.= '<tr><td>Spettacolo</td><td>Posti</td><td>Data</td><td></td></tr>';
		$html.= '</thead>';
		# CORPO DELLA TABELLA ELENCO CINEMA
		$html.= '<tbody>';
		$numeroRepliche = 0;
		$nomeCinema = (isset($this->elencoRepliche[0]['CINEMA'])?$this->elencoRepliche[0]['CINEMA']:'');
		
		foreach($this->elencoRepliche as $linea){
			$postiLiberi = abs($linea['TOTALI'] - $linea['VENDUTI']);
			
			$html.= '<tr>';
			$html.= '<td>'.$linea['TITOLO'].' <small>di '.$linea['AUTORE'].'</small></td>';
			$html.= '<td>'.$postiLiberi.' / '.$linea['TOTALI'].'</td>';
			$html.= '<td>'.self::convertDate($linea['DATA_REPLICA']).'</td>';
			$html.= '<td><a href="?idCinema='.$this->cod_cinema.
									'&idSpet='.$this->cod_spettacolo.
									'&idRep='.$linea['COD_REPLICA'].'">Seleziona</a></td>';
			$html.= '</tr>';
			$numeroRepliche++;
		}
		$html.= '</tbody>';
		$html.= '<tfoot><tr><td colspan="4">'.
						'Numero repliche: '.$numeroRepliche.
						' - Cinema: '.$nomeCinema.
						'</td></tr></tfoot>';
		$html.= '</table>';
		return $html;
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