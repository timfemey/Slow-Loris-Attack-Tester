<?php

class Details{
    public function __construct($host, $port, $no_of_sockets, $rate, $method, $path)
    {
        $this->host = $host;
        $this->port = $port ? $port : 3001;
        $this->no_of_sockets = $no_of_sockets;
        $this->rate = $rate;
        $this->method = $method ? $method: 'GET';
        $this->path=$path ? $path : '/';
    }

}

?>