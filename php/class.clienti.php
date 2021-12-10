<?php
#GESTIONE DATABASE
require_once("class.mysql.php");

/** 
 * ---------------------------
 * Classe per la gestione dell'
 * anagrafica clienti per il 
 * software di project work A.S.2019/20 
 * ---------------------------
*/
class clienti{
    private $dbTABLE = "clienti";
    private $cod_cliente = 0;
    private $cognome = "";
    private $nome = "";
    private $telefono = "";
    private $email = ""; 

    
    public function __construct(int $codCliente= 0){
        if($codCliente>0){self::load_by_cod($codCliente);}
    }

    public function __GET($a){
        if($a=='id'){return $this->cod_cliente;}
    }

    public function set_cognome(string $cognome){$this->cognome = $cognome;}
    public function get_cognome(){return $this->cognome;} 

    public function set_nome(string $nome){$this->nome = $nome;}
    public function get_nome(){return $this->nome;} 

    public function set_telefono(string $telefono){$this->telefono = $telefono;}
    public function get_telefono(){return $this->telefono;}

    public function set_email(string $email){$this->email = $email;}
    public function get_email(){return $this->email;} 

    #  poiché devo poter caricare i clienti creo la funzione
    #load_by_cod
    public function load_by_cod(int $cod){
        $query="SELECT * FROM `$this->dbTABLE` WHERE COD_CLIENTE =".$cod;
        $result = $this->esegui_sql($query);
        if ($result){
            $this->cod = $cod;
            self::set_cognome((string)$result[0]['COGNOME']);
            self::set_nome((string)$result[0]['NOME']);
            self::set_telefono((string)$result[0]['TELEFONO']);
            self::set_email((string)$result[0]['EMAIL']);
            return true;
        } else {
                $this->cod=null;
        }
        return false;
    } 

    # funzione per salvare i dati dei nuovi clienti inseriti
    # o per salvare le modifiche a vecchi clienti
    public function save(){
        if ($this->cod ==null || $this->cod<=0){
                $query= "INSERT `$this->dbTABLE`(COGNOME, NOME, TELEFONO, EMAIL) VALUES (";
                $query.="'$this->cognome', '$this->nome', '$this->telefono','$this->email')";

                $this->cod=self::esegui_sql($query);
                return $this->cod;
        }else{
                $query = "UPDATE `$this->dbTABLE` SET ";
                $query.="COGNOME = '$this->cognome' ";
                $query.=", NOME = '$this->nome' ";
                $query.=", TELEFONO = '$this->telefono' ";
                $query.=", EMAIL = '$this->email' ";
                $query.="WHERE COD_CLIENTE = ".$this->cod; 

            return self::esegui_sql($query);
        }
    }

    public function remove (){
        if($this->cod > 0) {
            $query="DELETE FROM `$this->dbTABLE` WHERE cod=".$this->cod;
            return self::esegui_sql($query);
        }
    }

    private function esegui_sql(string $query){
        $connessione = new database();
        $connessione->name_db = 'cinema';
        $connessione->connetti();
        $result=$connessione->esegui($query);
        $connessione->disconnetti();
        return $result;
    }
}
?>