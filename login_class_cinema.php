<?php
session_start();
require_once('php/class.mysql.php');

class login{
	#metodo per generare il form
		public function HTML_form_login(){
		$form="<h2>Accedi ai servizi di Cineclick</h2>";
		$form.="<table><form method='post'>";
		$form.="<tr><td><label>Codice Cliente</label></td></tr>";
		$form.="<tr><td><input type='text' placeholder='Codice cliente' name='user' value=''></td></tr>";
		$form.="<tr><td><label>Il tuo nome</label></td></tr>";
		$form.="<tr><td><input type='password' placeholder='Nome' name='pwd' value=''></td></tr>";
		$form.="<tr><td><button type='submit' name='login' value='Login'>Accedi</button></td></tr></form>";
		$form.="<tr><td><p>Sei un nuovo utente?<br><br><a href='page_register.php'>Registrati</a>";
		$form.=" oppure <a href='index.php'>Torna alla Home</a></p></td></tr></table>";
			return $form;
}
	#metodo che controlla i dati e fa partire la funzione di verifica dei dati dentro il data base
	public function __construct(){
		if(isset($_POST['user']) && isset ($_POST['pwd']) && isset($_POST['login'])){self::check_login();
		}//else{echo('<p>devi inserire tutti i campi</p>');}
	}
	#metodo per la verifica dei dati dentro il database e l'imposta della sessione.
	private function check_login(){
		$query="SELECT * FROM `clienti` WHERE COD_CLIENTE='".$_POST['user']."' AND NOME='".$_POST['pwd']."';";
			
		$connect = new database();
		$connect->connetti();
		$dati=$connect->esegui($query);		
		$connect->disconnetti();

		if(is_array($dati)){
			#decido io di chiamare $_SESSION['login'] la varibile che contine i dati della sessione
			#potevo anche chiamarlo $_SESSION['pippo']
			if(!isset($_SESSION['login'])){
			$_SESSION['login']=$dati[0];
			header("location:index.php");}
			
		}else{
			
			echo('<p>codice cliente o password sbagliato</p>');
		}
}
	}

#costruisco la classe per la registrazione
class registrazione{
	private $cognome='';
	private $nome='';
	private $tel='';
	private $email='';
	
	public function HTML_form_registrazione(){
	$form="<h2>Registrazione al Portale di Cineclick</h2>";
	$form.="<table><form method='post'>";
	$form.="<tr><td><label>Cognome</label></tr></td>";
	$form.="<tr><td><input type='text' name='cognome' value=''></tr></td>";
	$form.="<tr><td><label>Nome</label></tr></td>";
	$form.="<tr><td><input type='text' name='nome' value=''></tr></td>";
	$form.="<tr><td><label>Email</label></tr></td>";
	$form.="<tr><td><input type='text' name='email' value=''></tr></td>";
	$form.="<tr><td><label>Telefono</label></tr></td>";
	$form.="<tr><td><input type='text' name='tel' value=''></tr></td>";
	$form.="<tr><td><button type='submit' name='registrati' value='registrati'>Registrati</button></tr></td>";
	$form.="</form>";
	$form.="<tr><td><p>Sei già registrato?<a href='page_login.php'>Accedi</a> oppure <a href='index.php'>";
	$form.="Torna alla Home</a></tr></td></table>";
		return $form;
	}
	#metodi per ricuperare i post del form di registrazione.
	public function set_cognome($cognome){$this->cognome=$cognome;}
	public function set_nome($nome){$this->nome=$nome;}
	public function set_tel($tel){$this->tel=$tel;}
	public function set_email($email){$this->email=$email;}

	public function __construct(){
		
		if(isset($_POST['cognome']) && isset ($_POST['nome']) && isset($_POST['tel']) && isset($_POST['email']) && isset($_POST['registrati'])){
			self::set_cognome($_POST['cognome']);
			self::set_nome($_POST['nome']);
			self::set_tel($_POST['tel']);
			self::set_email($_POST['email']);
			self::registra();
		
		}
	}
	
	private function registra(){
		#query e insertione dentro il data base
		$query="INSERT INTO `clienti`";
		$query.=" (COGNOME, NOME, TELEFONO, EMAIL)";
		$query.=" VALUES ('$this->cognome', '$this->nome', '$this->tel', '$this->email')";
			
		$connect = new database();
		$connect->connetti();
		$dati=$connect->esegui($query);		
		$connect->disconnetti();
		#il nuovo id dei dati inseriti va salvato dentro $dato. questo è possibile grazie alla #built-in function 'insert_id' nel file class.mysql.php.
		#se recupero l'id, salvo i dati associati all'id dentro la variabile $_SESSION['login']
		if($dati && !isset($_SESSION['login'])){$_SESSION['login']=['COD_CLIENTE'=>$dati,'COGNOME'=>$this->cognome, 'NOME'=>$this->nome, 'TELEFONO'=>$this->tel, 'EMAIL'=>$this->email];}
		
		header("location:profile.php");
	}
}
?>