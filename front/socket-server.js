const express = require("express");

const app = express();

const server = app.listen(3001, function () {
  console.log("server running on port 3001");
});

// const io = require("socket.io")(server);

const io = require("socket.io")(server, {
  cors: {
    //                      origin: "http://10.12.121.194:3000"
    origin: "*",
  },
});

io.on("connection", function (socket) {
  var game = socket.handshake.query.game_id;

  socket.join(game);
  var total =
    io.sockets.adapter.rooms.get(game) !== undefined
      ? io.sockets.adapter.rooms.get(game).size
      : 0;
  console.log("Spectator joined game: " + game, total);

  // emit number of connections to game 'room' on new connection
  io.to(game).emit("CONNECTIONS", total);
  // console.log('Number of clients connected to game ' + game + ': ' + total)

  socket.on("disconnect", function () {
    socket.leave(game);
    var total =
      io.sockets.adapter.rooms.get(game) !== undefined
        ? io.sockets.adapter.rooms.get(game).size
        : 0;
    console.log("Spectator disconnected from game: " + game, total);
    io.to(game).emit("CONNECTIONS", total);
  });

  socket.on("SEND_MESSAGE", function (msg) {
    io.to(game).emit("MESSAGE", msg);
  });
});
