const express = require('express');

const app = express();

const server = app.listen(3001, function() {
    console.log('server running on port 3001');
});

const io = require('socket.io')(server);


io.on('connection', function(socket) {
    var game = socket.handshake.query.game_id;
  
    socket.join(game);
    console.log('Spectator joined game: ' + game);
    var total = io.nsps['/'].adapter.rooms[game] !== undefined ? io.nsps['/'].adapter.rooms[game].length : 0;

    // emit number of connections to game 'room' on new connection
    io.to(game).emit('CONNECTIONS', total);
    // console.log('Number of clients connected to game ' + game + ': ' + total)
  
    socket.on('disconnect', function() {
      socket.leave(game)
      var total = io.nsps['/'].adapter.rooms[game] !== undefined ? io.nsps['/'].adapter.rooms[game].length : 0;
      console.log('Spectator disconnected from game: ' + game);
      io.to(game).emit('CONNECTIONS', total);
    });
  
    socket.on('SEND_MESSAGE', function(msg) {
      io.to(game).emit('MESSAGE', msg);
    });
});
