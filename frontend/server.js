const express = require('express');
const axios = require('axios');
const app = express();
const port = 3000;

var router = express.Router();
var _ = require('underscore');

var router = express.Router();

app.set('view engine', 'pug');

/* Get a list of all players */
app.get('/players', function (req, res, next) {
    axios.get('http://localhost:8888/players')
        .then(response => {
            var players = response.data;
            players = _.sortBy(players, (player) => player.name)
            res.render('player/players', { players: players });
        }).catch(error => {
            console.log(error);
        });
});

module.exports = app;

app.listen(port, () => console.log(`Example app listening on port ${port}!`))