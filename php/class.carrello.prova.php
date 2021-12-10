<?php 

#classe che consente la visione dei biglietti acquistati per utente
class carrello{

    private $DB_TABLE					= "biglietti";
	
	private $cliente 				  = null;
	private $elencoBiglietti	= array();
	private $errori						= "";

    # Funzione che mi permette di decodificare i pagamenti
    public function decode_pagamenti($code){
		switch($code){
			case '1': return 'Contanti';
			case '2': return 'Carta di Credito';
			case '3': return 'Bonifico';
		}
	} 

    # Funzione che mi permette di creare l'elenco dei biglietti
    public function get_all_biglietti(){		
		$sql = "SELECT biglietti.QUANTITA AS numero, biglietti.TIPO_PAGAMENTO AS pagamento, ". 
                "repliche.DATA_REPLICA AS giorno, ".
                "spettacoli.TITOLO AS film, spettacoli.REGISTA as regista, spettacoli.PREZZO as prezzo, ". 
                "CINEMA.NOME AS cinema, cinema.INDIRIZZO indirizzo, cinema.CITTA as citta ".
                "FROM biglietti ".
                "LEFT JOIN repliche ON repliche.COD_REPLICA = biglietti.COD_REPLICA ".
                "LEFT JOIN spettacoli ON spettacoli.COD_SPETTACOLO = repliche.COD_SPETTACOLO ".
                "LEFT JOIN cinema ON cinema.COD_CINEMA = spettacoli.COD_CINEMA ".
                "WHERE biglietti.COD_CLIENTE= ".$_SESSION['login']['COD_CLIENTE']." ";
		$dati = self::sql($sql);
		if(!$dati){
			$this->elencoBiglietti = array();
			return false;
		}else{
			$this->elencoBiglietti = $dati;
			return true;
		}		
	}

    #funzione per creare la tabella
    public function costruisci_tabella(){
        # recupero tutti gli acquisti
        self::get_all_biglietti();
        # Intestazione tabella
        $html="<table><thead>";
        $html.="<tr><td colspan='5'>Le tue prenotazioni</td></tr>";
        $html.="<tr><td>Titolo Film</td>";
        $html.="<td>Cinema</td>";
        $html.="<td>Posti</td>";
        $html.="<td>Pagamento</td>";
        $html.="</tr>";
        $html.="</thead>";
        # Corpo tabella
        $html.="<tbody>";
        $numeroPreonotazioni = 0;
		foreach($this->elencoBiglietti as $linea){
            $html.="<tr>";
            $html.="<td>".$linea['film']."<br/><small>(Regia di ".$linea['regista'].")</small></td>";
            $html.="<td>".$linea['cinema']."<br/><small>(".$linea['indirizzo']."-".$linea['citta'].")</small></td>";
            $html.="<td>".$linea['numero']."</td>";
            $html.="<td>".self::decode_pagamenti($linea['pagamento'])."</td>";
            $html.="</tr>";
            $numeroPreonotazioni++;
        }
        $html.="</tbody>";
        # Footer della tabella
        $html.="<tfoot>";
        $html.="<tr><td colspan='4'>Numero prenotazioni:".$numeroPreonotazioni."</td></tr>";
        $html.="</tfoot>";
        $html.="</table>";

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