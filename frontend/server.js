const express = require('express')
const axios = require('axios')
const app = express()
const port = 3000

var router = express.Router();

app.get('/', (req, res) => res.send('Hello World!'))

app.get('/aaa', (req, res) => res.send('eee World!'))

/* Get a list of all players */
app.get('/players', function (req, res, next) {
    axios.get('http://webserver:8888/players')
        .then(response => {
            var players = response.data;
            players = _.sortBy(players, (player) => player.name)
            res.render('player/players', { players: players });
        }).catch(error => {
            //debug('Error when getting players: ' + error);
            //next(error);
            console.log('error');
        });
});

module.exports = app;

app.listen(port, () => console.log(`Example app listening on port ${port}!`))