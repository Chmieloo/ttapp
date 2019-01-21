require('marko/node-require').install();
require('marko/express'); //enable res.marko

var express = require('express');

// Configure lasso to control how JS/CSS/etc. is delivered to the browser
require('lasso').configure({
    plugins: [
        'lasso-marko', // Allow Marko templates to be compiled and transported to the browser
        'lasso-less'
    ],
    outputDir: __dirname + '/static', // Place all generated JS/CSS/etc. files into the "static" dir
    bundlingEnabled: isProduction, // Only enable bundling in production
    minify: true, // Only minify JS and CSS code in production
    fingerprintsEnabled: isProduction, // Only add fingerprints to URLs in production
});

var app = express();

app.use(require('lasso/middleware').serveStatic());

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