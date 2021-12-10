<?Php

/**
 * ---------------------------------------------------------------- *
 * CLASSE BASE PER CONNETTERSI AL DATABASE DI TIPO SQL
 * SI OCCUPA DI STABILIRE LA CONNESSIONE, INVIARE QUERY
 * RECUPERARE LA RISPOSTA DAL DATABASE E CHIUDERE LA CONNESSIONE
 * ---------------------------------------------------------------- *
 * COME SI USA:
 *
 * 1. inizializzo la classe (istanza); creo una copia della classe stessa.
 *		L'istanza la posso conservare all'interno di qualunque variabile.
 *		Di prassi è sempre meglio metterla all'interno di una variabile normale
 *		e non all'interno di _POST, _GET, _SESSION
 *
 *			$serverDB = new database();
 * 
 * 2. se necessario imposto i parametri di connessione
 *    diversi da quelli di default già impostati
 *
 *			$serverDB->server = "127.0.0.1";
 *			$serverDB->port = 3308;
 *			$serverDB->username = "root";
 *			$serverDB->password = "usbw";
 * 
 * 3. stabilisco la connessione con il database
 *
 *			$isConnesso = $serverDB->connetti();
 *
 * 4. inviare la query al database e ricevere la risposta
 *
 *			$risposta = $serverDB->esegui("SELECT * FROM allievi; ");
 *
 * 5. chiudere la connessione al server SQL
 *
 *			$serverDB->disconnetti();
 *
 * ---------------------------------------------------------------- *
 */
class database {
	/**
	 * CREAZIONE DELLE "PROPRITA' DELLA CLASSE"
	 * SONO COME LE VARIABILI, INFATTI LE CREO NELLA
	 * STESSA MANIERA (mettendo il $ davanti al nome),
	 * PER INVECE UTILIZZARLE, USERO' $this->(nome della variabile)
	 * MA SOLAMENTE ALL'INTERNO DELLA CLASSE STESSA,
	 * --
	 * PER USARLE ALL'ESTERNO DELLA CLASSE BASTERA' ->(nome della variabile)
	 * SOLO SE DAVANTI HANNO LA DICITURA 'PUBLIC', SE INVECE HANNO LA
	 * DICITURA 'PRIVATE' NON POTRO' USARLE ALL'ESTERNO, MA SOLO ALL'INTERNO
	 * DELLA CLASSE STESSA
	 */
	# (string) indirizzo del server SQL
	public $server = "localhost";
	# (int) porta del server SQL
	public $port = 3308;
	# (string) username di autentificazione al server SQL
	public $username = "root";
	# (string) password di autentificazione al server SQL
	public $password = "";
	# (string) nome del database da usare all'interno del server SQL
	public $name_db = "cinema";
	
	# (object) contenitore della connessione al server SQL
	private $connessione = null;
	
	/**
	 * CONNESSIONE CON SERVER DB
	 * stabilisce una comunicazione con il server SQL
	 * secondo i parametri sopra riportati
	 */
	public function connetti(){
		# verifico se la porta è stata specificata,
		# in tal caso la unisco all'indirizzo del server
		# aggiungendo prima i due punti (:)
		$server=$this->server;
		if($this->port){$server.=":".$this->port;}
		
		# stabiliamo la nosta connessione al nostro host mySQL
		$com = mysqli_connect(
			$server,			// indirizzo del server + porta
			$this->username,	// username di autentificazione
			$this->password,	// password di autenficazione
			$this->name_db		// nome del database
		);		
		
		# nel caso si siano verificati errori durante la connessione
		# segnalo l'errore ed interrompo l'esecuzione
		if($com->connect_errno){
			die("ERRORE CONNESSIONE! '".$com->connect_error."'");
		}
		
		# salvo l'oggetto connessione all'interno
		# del attributo connssione
		$this->connessione=$com;
		return true;
	}
	
	/**
	 * DISCONNESSIONE DAL SERVER DB
	 * chiude la comunicazione e di conseguenza la connessione
	 * al server SQL
	 */
	public function disconnetti(){
		$this->connessione->close();
	}
	
	/**
	 * ESECUZIONE DELLE QUERY
	 * invio delle richieste al server SQL e ne recupera la
	 * risposta dove possibile
	 *
	 * @query (string)	: query da eseguire
	 * @return (mix)		: dipende dalla query che ho utilizzato
	 */
	private function _esegui(string $query){		
		
		# invio e ricezione della query
		$risultati = $this->connessione->query( $query );			
		
		if($this->connessione->errno){
			die('<strong>Errore SQL N: '.$this->connessione->errno.'</strong><br>'.
					'<p>'.$this->connessione->error.'</p>'.
					'<p style="color:#900">"'.$query.'"</p>'
				 );
		}
		
		# se lo troviamo alla prima posizione
		# vuol dire che la query è di tipo select
		# quindi mi aspetto un array
		if(strpos($query,"SELECT")===0){
			$out=[];
			while($linea = $risultati->fetch_assoc()){$out[]=$linea;}
			# puliamo la memoria
			$risultati->free();
			return $out;
			
		# nel caso di "INSERT" ritorniamo il nuovo ID creato
		}else if(strpos($query,"INSERT")===0){	
			$result = $this->connessione->insert_id;
			return $result;
			
		}else{
			$result = ($this->connessione->errno?false:true);
			return $result;
		}
		
		# NON CI SONO ERRORI QUINDI LA QUERY
		# SARA' MOLTO PROBABILMENTE DI TIPO delete, insert, update,.... 
		if( !$this->connessione->errno ){			
			return true;
		}else{
			die('<strong>Errore SQL N: '.$this->connessione->errno.'</strong><br>'.
					'<p>'.$this->connessione->error.'</p>'.
					'<p style="color:#900">"'.$query.'"</p>'
				 );
		}
		
		return false;
	}
	
	/**
	 * ESECUZIONE DELLE QUERY
	 * invio delle richieste al server SQL e ne recupera la
	 * risposta dove possibile
	 *
	 * @query (string)	: query da eseguire
	 * @return (mix)		: dipende dalla query che ho utilizzato
	 */
	public function esegui(string $query){
		# protezione da codici malevoi		
		if(strpos($query,'DROP')!==false){return false;}		
		
		/**
		 * richiamo "la funzione" meglio detta METODO privato che esegue la query
		 * per poterla richiamare utilizzo, all' interno della classe 
		 * self::(nome del metodo), oppure $this->(nome del metodo)
		 */
		return self::_esegui($query);		
	}
	
}



/**
 * ---------------------------------------------------------------- *
 * CLASSE CHE ESPANDE LA CLASSE 'database' COSI' COME FA' CAPIRE IL
 * TERMINE 'extends' SEGUITO DAL NOME DELLA CLASSE CHE VA' AD IMPLEMENTARE
 * 
 * TALE CLASSE EREDITA TUTTI I METODI E PROPRIETA' DELLA CLASSE BASE
 * AL SUO INTERNO POSSIAMO SCRIVERE NUOVI METODI E NUOVE PROPRIETA'
 * E SE VOGLIAMO POSSIAMO ANCHE MODIFICARE QUELLE EREDITA 
 *
 * ESSENDO UNA ESPANSIONE DELLA CLASSE 'database' VIENE USATA NELLA
 * STESSA MANIERA
 *
 * ---------------------------------------------------------------- *
 */

class superDatabase extends database{
	
	public function esegui(string $query){		
		return self::_esegui($query);		
	}
	
}

?>





