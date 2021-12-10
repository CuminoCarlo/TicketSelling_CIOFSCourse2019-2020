<?php
require_once('class.mysql.php');

class biglietti {
/** Necessito di creare due tabelle, una delle quali per salvare i dati 
     *  dei biglietti venduti per spetacolo 
    */
    private $DB_TABLE_1= "biglietti";
    private $DB_TABLE_2= "biglietti_venduti_spettacolo";

    //creo classe per settare il massimo dei biglietti acquistabili
    private $BigliettiMassimi=4;

    //dati per la prima tabella
	private $cod_operazione = 0;
	private $cod_cliente		= 0;
	private $cod_replica		= 0;
    private $data_ora="";
    private $tipo_pagamento=0;
    private $quantita=0;

    //dati per la seconda tabella
    private $cod_cinema=0;
    private $posti_cinema_max=0;
    private $cod_spettacolo=0;
    private $data_replica="";

    //funzioni per recuperare le variabili da altre classi
	public function set_cinema($cod_cinema){$this->cod_cinema = $cod_cinema;}
	public function set_cinema_posti($posti_max){$this->posti_cinema_max = $posti_max;}
	public function set_spettacolo($cod_spettacolo){$this->cod_spettacolo = $cod_spettacolo;}
	public function set_replica($cod_replica){$this->cod_replica = $cod_replica;}
    public function set_data_replica($data_replica){$this->data_replica=$data_replica;} 

    public function convertDate($date,$pattern='d/m/Y'){
		return date($pattern,strtotime($date));
	}

    public function costruisci_form(){
        $q = "SELECT ".
				 "cnm.NOME AS CINEMA, rpt.DATA_REPLICA AS DATA, spt.TITOLO, spt.AUTORE, ".
				 "cnm.POSTI AS TOTALI, bgt.BIGLIETTI_venduti AS VENDUTI ".
					// informazioni sullo spettacolo
				 "FROM `spettacoli` AS spt ".
					// informazioni sul cinema
				 "LEFT JOIN `cinema` AS cnm ON cnm.COD_CINEMA = spt.COD_CINEMA ".
					// informazioni sulla replica
				 "LEFT JOIN `repliche` AS rpt ON rpt.COD_SPETTACOLO = spt.COD_SPETTACOLO ".
					// recupero le informazioni sui posti disponibili
				 "LEFT JOIN `biglietti_venduti_spettacolo` AS bgt ON ".
						"bgt.COD_CINEMA = cnm.COD_CINEMA AND bgt.COD_REPLICA = rpt.COD_REPLICA ";
        $info = self::sql($q);
        # calcolo dei posti disponibili
		$postiMax = $this->BigliettiMassimi;
		if($info){
			$postiMax = min($this->BigliettiMassimi,abs($info[0]['TOTALI'] - $info[0]['VENDUTI']));
        } 
        
		$html = '<form method="POST">';		
		$html.= '<table>';
		
		$html.= '<thead><tr><td colspan="2">Acquista i biglietti</td></tr></thead>';
		# costruzione del compo input per l'inserimento del numero di biglietti
		$html.= '<tr>'.
						'<td>Numero biglietti</td>'.
						'<td><input type="number" name="biglietti" '.
								  'step="1" min="1" max="'.$postiMax.'" value="1"/></td>'.
						'</tr>';
		# costruzione del compo input per l'inserimento del numero di biglietti
		$html.= '<tr>'.
						'<td>Tipo di pagamento</td>'.
						'<td><select name="pagamento">'.
							'<option value="1">Contanti</option>'.
							'<option value="2">Carta di Credito</option>'.
							'<option value="3">Bonifico</option>'.
						'</select></td>'.
						'</tr>';
		
		$html.= '<tr><td>Spettacolo</td><td>'.
						($info?$info[0]['TITOLO'].' <small>di '.$info[0]['AUTORE'].'</small>':'--').
						'</td></tr>';
		$html.= '<tr><td>Data replica</td><td>'.
						($info?self::convertDate($info[0]['DATA']):'--').
						'</td></tr>';
		$html.= '<tr><td>Cinema</td><td>'.
						($info?$info[0]['CINEMA']:'--').
						'</td></tr>';
            #FOOTER DELLA TABELLA
	        $html.='<tr><td colspan="2"><a class="button-annulla" href="index.php">Annulla </a></td></tr>';
			$html.='<tr><td colspan="3"><input class="button-prenota" type="submit" name="Paga" value="Prenota"></td>';
			$html.='</tr></table>';
			$html.= '</form>';
				return $html;
    }

    public function acquista(){
        if(isset($_POST['Paga'])){
            if($_POST['pagamento']>0&&$_POST['biglietti']>0) {
                $esito =self::acquista_biglietti($_POST['pagamento'],$_POST['biglietti']);
                if(!$esito){
                    return self::costruisci_form();
                }else{
                    return '<p class="errorMsg">Prenotazione avvenuta con esito positivo!<br>'.
                                 '<a href="?">Torna all\'elenco dei cinema</a>'.
                                 '</p>';
                }
            }
        }
    return self::costruisci_form();
    } 

    private function acquista_biglietti(){
        $date =	date ('Y-m-d H:i:s');
		$query="INSERT INTO `$this->DB_TABLE_1` ".
				"(COD_CLIENTE, COD_REPLICA, DATA_ORA, TIPO_PAGAMENTO,QUANTITA) ".
				"VALUES ( ".$_SESSION['login']['COD_CLIENTE'].", ".$_GET['idRep'];
		$query.=", '$date', ". $_POST['pagamento']."," .$_POST['biglietti'].")";
		
		$dati=$this->sql($query);
		
		if (is_numeric ($dati)){$this->cod_operazione=$dati;}
		return $this->cod_operazione;
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