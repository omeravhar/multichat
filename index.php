<?php 
require __DIR__ . '/vendor/autoload.php';

use Workerman\Worker;
use PHPSocketIO\SocketIO;

// listen port 2021 for socket.io client
$io = new SocketIO(2022);
$io->on('connection', function($socket)use($io){
//    print_r($io->of("/")->sockets);
//    print_r($io->rooms);

//    print_r($io->id);
     $io->socketId = $socket->id;
    
    
    
    
    
    
//    $socket->on('send message', function($msg)use($io){
//        $io->emit('new message', $msg);
//        echo "on emit";
//    });
    
    
     $socket->on('send message', function($msg )use($io){
         
        $io->to($msg["socket"])->emit('new message', $msg["message"]);
        print_r($msg);
        
    });
    
    
    // When the client emits 'add user', this listens and executes
    $socket->on('add user', function ($username) use ($io) {
        global $usernames, $numUsers ;

        // We store the username in the socket session for this client
        $io->username = $username;
        
        // Add the client's username to the global list
        $usernames[] = [
            "name"=>$username,
            "socketId"=>$io->socketId
                    
        ];
        ++$numUsers;

        $io->addedUser = true;
        $io->emit('login', array( 
            'numUsers' => $numUsers,
            "users"   => $usernames,
            
        ));
        $io->emit('return user socket id',$io->socketId);

        // echo globally (all clients) that a person has connected
//        $io->broadcast->emit('user joined', array(
//            'username' => $io->username,
//            'numUsers' => $numUsers
//        ));
        
        
    });
    
    
    
    // When admin user connect , this listens and executes
    $socket->on('add admin user', function ($adminUserName) use ($io) {
        global $adminusernames, $numAdminUsers ;

        // We store the username in the socket session for this client
        $io->username = $adminUserName;
        
        // Add the client's username to the global list
        $adminusernames[$adminUserName] = $io->socketId;
        ++$numAdminUsers;

        $io->addedUser = true;
        $io->emit('admin login', array( 
            'numUsers' => $numAdminUsers,
            "users"   => $adminusernames,
        ));        
    });
    
    
    // When the client emits 'typing', we broadcast it to others
    $socket->on('typing', function () use ($io) {
        echo "on typing";
        $io->broadcast->emit('typing', array(
            'username' => $io->username
        ));
    });
    
    
    
});





Worker::runAll();