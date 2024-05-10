<?php
    class PostgresqlRewrite {

        public $servidor = "192.168.10.26";
        public $porta = 5432;
        public $bancoDeDados = "whatsapp_omnichannel";
        public $usuario = "postgres";
        public $senha = "FIpyi9kz@Xg@";
        public $keyFile    = '/home/certificados/omnichannel/omnichannel.chat.comunix.tech.key';
        public $chainFile  = '/home/certificados/omnichannel/omnichannel.chat.comunix.tech.intermediate.csr';
        public $conn;

        function __construct () {
            $this->conn =  pg_connect("host=".$this->servidor." port=".$this->porta." dbname=".$this->bancoDeDados." user=".$this->usuario." password=".$this->senha);
        }

        function teste () {
            return $this->conn;
        }

        function query ($param1) {
            return pg_query($this->conn, $param1);
        }

        function busyTimeout ($param1) {
            return "busyTimeout";
        }

        function exec ($param1) {
            return pg_query($this->conn, $param1);
        }

        function fetchArray ($param1) {
            return pg_fetch_assoc($param1);
        }

        function pg_fetch_all_s ($param1){
            return pg_fetch_all($param1);
        }

        function close () {
            return pg_close($this->conn);
        }

	    function getKey () {
            return $this->keyFile;
        }

	    function getCert () {
            return $this->chainFile;
        }

    }

    $db = new PostgresqlRewrite();
?>
