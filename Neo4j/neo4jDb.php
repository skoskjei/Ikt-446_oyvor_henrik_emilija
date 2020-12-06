<?php
include_once 'vendor\autoload.php';


use Neoxygen\NeoClient\ClientBuilder;;

class Neo4jDb
{
    private $db_connection;
    private $host_username;
    private $host_password;
    private $host_scheme;
    private $host;
    private $port;


    public function __construct()
    {
        // Read in the config file
        $config = parse_ini_file('C:\ikt446_oyvor_henrik_emilija\config.ini', true);
        $this->host_scheme = $config['graph_database']['scheme'];
        $this->host= $config['graph_database']['host'];
        $this->port = $config['graph_database']['port'];
        $this->host_username = $config['graph_database']['username'];
        $this->host_password = $config['graph_database']['password'];

        // Make connection
        $this->connect();
    }

    public function connect()
    {

        try {
            $this->db_connection = ClientBuilder::create()
                ->addConnection('default', 'http', 'localhost', 7474)
                ->setAutoFormatResponse(true)
                ->build();

            //echo "Connected successfully";
        } catch (Exception $e) {
            echo "Connection failed: " . $e->getMessage();
        }

    }

    public function query($graph_query)
    {
        return $this->db_connection->sendCypherQuery($graph_query)->getResult();

    }

}


