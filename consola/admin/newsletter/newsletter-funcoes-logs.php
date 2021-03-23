<?php 

class NewsLogs {

	/*** Declare instance ***/
	private static $instance = NULL;
	
	/**
	*
	* the constructor is set to private so
	* so nobody can create a new instance using new
	*
	*/
	private function __construct() {
	  /*** maybe set the db name here later ***/
	}
	
	/**
	*
	* Return DB instance or create intitial connection
	*
	* @return object (PDO)
	*
	* @access public
	*
	*/
	public static function getInstance() {
		if (!self::$instance) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	public static function logs_agendamentos($utilizador, $id, $que_fez, $nome_newsletter, $lista_nomes=''){
		$data_final=date('Y-m-d :: H:i:s'); 
		
		$erro = "";
		try {
			$insertSQL = "INSERT INTO newsletters_logs (utilizador, newsletter, newsletter_id, que_fez, data, listas) VALUES (:utilizador, :nome_newsletter, :id, :que_fez, :data_final, :lista_nomes)";
			$rsInsert = DB::getInstance()->prepare($insertSQL);
			$rsInsert->bindParam(':utilizador', $utilizador, PDO::PARAM_STR, 5);	
			$rsInsert->bindParam(':nome_newsletter', $nome_newsletter, PDO::PARAM_STR, 5);	
			$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	
			$rsInsert->bindParam(':que_fez', $que_fez, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':data_final', $data_final, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':lista_nomes', $lista_nomes, PDO::PARAM_STR, 5);	
			$rsInsert->execute();
			DB::close();
		} catch(PDOException $e){
			echo $erro = $e->getMessage();
		}
	}
	
	/**
	*
	* Like the constructor, we make __clone private
	* so nobody can clone the instance
	*
	*/
	private function __clone(){
	}

} /*** end of class ***/

$class_news_logs = NewsLogs::getInstance();

?>