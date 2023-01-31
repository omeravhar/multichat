<?php
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Chat</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
    </head>
    <body>
        <div id="chat-messages" style="overflow-y: scroll; height: 100px; "></div>        
        <input type="text" class="message">
        
        
        <h3>user name:</h3>
        <input type="text" class="username">
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>    
    <!--<script src="https://localhost:2022/socket.io/socket.io.js"></script>-->  
    <script src="https://cdn.socket.io/socket.io-1.4.5.js"></script>
    <script>
            var socket = io.connect("ws://127.0.0.1:2022");
            let userSocketId = "";
            $('.username').on('change', function(){
                socket.emit('add user', $(this).val());
                
            });
            
            
            

            $('.message').on('change', function(){
//                console.log(socket.socketId);
                let messageData = {
                    socket:userSocketId,
                    message:$(this).val()
                    };
                socket.emit('send message', messageData);
                $(this).val('');
            });




            socket.on('new message', function(data){
                console.log("data");
                $('#chat-messages').append('<p>' + data.message +'</p>');
            });
            
            
            
            
            socket.on('return user socket id', function(data){
//                console.log(data);
                userSocketId = data;
            });
    </script>
</html>