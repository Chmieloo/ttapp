var express = require('express');
var router = express.Router();

var axios = require('axios');
var _ = require('underscore');

/* Get a list of all players */
router.get('/', function (req, res, next) {
    axios.get(req.app.locals.kcapp.api + '/players')
        .then(response => {
            var players = response.data;
            players = _.sortBy(players, (player) => player.name)
            res.render('player/players', { players: players });
        }).catch(error => {
            debug('Error when getting players: ' + error);
            next(error);
        });
});