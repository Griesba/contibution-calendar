<?php

function connect($host,$dbname,$charset,$user,$password) { 
    try
    {
        $db = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset", "$user", "$password", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        //$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    }
    catch (Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }

    if (!$db) {   
        echo 'Erreur lors de la connexion à la base de données';
    }
    return $db; 
}
$db = connect('localhost','contribution_calendar','utf8','root',''); 




class ConnectDb {
    // Hold the class instance.
    private static $instance = null;
    private $conn;
    
/*     private $host = 'sqlprive-dn717-001.privatesql';//'localhost';
    private $user = 'captcha21';//'root';
    private $pass = 'NyR594zC85SrFGGr5g3Ua86pgFrjB3';
    private $name = 'captcha21_db';//'contribution_calendar'; */
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $name = 'contribution_calendar';
     
    // The db connection is established in the private constructor.
    private function __construct()
    {
      $this->conn = new PDO("mysql:host={$this->host};
      dbname={$this->name}", $this->user,$this->pass,
      array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    }
    
    public static function getInstance()
    {
      if(!self::$instance)
      {
        self::$instance = new ConnectDb();
      }
     
      return self::$instance;
    }
    
    public function getConnection()
    {
      return $this->conn;
    }
  }
