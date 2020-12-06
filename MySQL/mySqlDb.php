<?php


class MySqlDb
{
    private $db_connection;
    private $host_name;
    private $host_username;
    private $host_password;
    private $host_dbname;


    public function __construct()
    {
        // Read in the config file
        $config = parse_ini_file('C:\ikt446_oyvor_henrik_emilija\config.ini', true);
        $this->host_name = $config['sql_database']['servername'];
        $this->host_username = $config['sql_database']['username'];
        $this->host_password = $config['sql_database']['password'];
        $this->host_dbname = $config['sql_database']['dbname'];
        // Make connection
        $this->connect();
    }

    public function connect(){
        try {
            $this->db_connection = new PDO("mysql:host=$this->host_name;dbname=$this->host_dbname", $this->host_username, $this->host_password);
            // set the PDO error mode to exception
            $this->db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return "Connected successfully";
        } catch(PDOException $e) {
            return "Connection failed: " . $e->getMessage();
        }
    }

    /**
     * @param $sql_query - SQL Query
     * @return PDO $connection
     */
    public function query($sql_query)
    {
        $connection = $this->db_connection->prepare($sql_query);
        $connection->execute();
        return $connection;
    }

    public function __destruct()
    {
        $this->db_connection = null;
    }


    /**
     * @return mixed
     */
    public function getHostName()
    {
        return $this->host_name;
    }

    /**
     * @param mixed $host_name
     */
    public function setHostName($host_name)
    {
        $this->host_name = $host_name;
    }

    /**
     * @return mixed
     */
    public function getHostUsername()
    {
        return $this->host_username;
    }

    /**
     * @param mixed $host_username
     */
    public function setHostUsername($host_username)
    {
        $this->host_username = $host_username;
    }

    /**
     * @return mixed
     */
    public function getHostPassword()
    {
        return $this->host_password;
    }

    /**
     * @param mixed $host_password
     */
    public function setHostPassword($host_password)
    {
        $this->host_password = $host_password;
    }

    /**
     * @return mixed
     */
    public function getHostDbname()
    {
        return $this->host_dbname;
    }

    /**
     * @param mixed $host_dbname
     */
    public function setHostDbname($host_dbname)
    {
        $this->host_dbname = $host_dbname;
    }

}
