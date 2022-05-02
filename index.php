<?php

print('Started...');

class Details{
    public $host;
    public $port;
    public $no_of_sockets;
    public $rate;
    public $method;
    public $path;

    public function __construct($host, $no_of_sockets, $rate, $port=5500, $method='GET', $path='/')
    {
        $this->host = $host;
        $this->port = $port;
        $this->no_of_sockets = $no_of_sockets;
        $this->rate = $rate;
        $this->method = $method;
        $this->path=$path;
    }

    protected function setInterval($f, $milliseconds){
        $seconds=(int)$milliseconds/1000;
        while(true)
        {
            $f();
            sleep($seconds);
        }
    }

    public function start(){
        define('msg', `{$this->method} {$this->path} HTTP/1.1\n`,false);
        $socket= socket_create(AF_INET, SOCK_RAW, 0) or die("Could not create Socket\n");
        socket_connect($socket, $this->host,$this->port) or die('Couldnt Connect to Socket\n');
        socket_write($socket, msg) or die('Couldnt Write ');
        print('Socket Activated');
        socket_write($socket, `Host: {$this->host}\n`);
        $sentPacket=0;
        $this->setInterval(function(){
            global $socket;
            global $sentPacket;
            if($socket){
                socket_write($socket, `x-header-${$sentPacket}: ${$sentPacket}\n`);
                $sentPacket+=1;
            }
        }, $this->rate);
        $response= socket_read($socket,1024) or die('Could not read server response');
        print($response);
        
    }

    public function stop(){
        exit('Done Executing');
    }

}

$instance = new Details('localhost',2000,600);

for($i=0;$i<$instance->no_of_sockets;$i++){
    print('init');
    $instance->start();
}
$instance->stop();

?>