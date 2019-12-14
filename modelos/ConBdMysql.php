<?php


abstract class ConBdMySql {

    private $servidor;
    private $base;
    protected $conexion;

    public function __construct($servidor, $base, $loginBD, $passwordBD) {
        $this->servidor = $servidor;
        $this->base = $base;

        try {
            $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES  \'UTF8\''); //formato de codificación de caracteres

            $dsn = "mysql:dbname=" . $this->base . ";host=" . $this->servidor;

            $this->conexion = new PDO($dsn, $loginBD, $passwordBD, $options);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch (Exception $ex) {
            echo "Error de conexión" . $ex->getMessage();
        }
    }

    public function cierreBd() {
        $this->conexion = null;
    }
}
?>

