require('marko/node-require').install();
require('marko/express'); //enable res.marko

var express = require('express');

var isProduction = process.env.NODE_ENV === 'production';

// Configure lasso to control how JS/CSS/etc. is delivered to the browser
require('lasso').configure({
    plugins: [
        'lasso-marko', // Allow Marko templates to be compiled and transported to the browser
        'lasso-less'
    ],
    outputDir: __dirname + '/static', // Place all generated JS/CSS/etc. files into the "static" dir
    bundlingEnabled: isProduction, // Only enable bundling in production
    minify: isProduction, // Only minify JS and CSS code in production
    fingerprintsEnabled: isProduction, // Only add fingerprints to URLs in production
});

var path = require('path');
var bodyParser = require('body-parser');
var lessMiddleware = require('less-middleware');

var app = express();

app.use(require('lasso/middleware').serveStatic());

// Make sure we get correct user IP when running behind a reverse proxy
app.enable('trust proxy');

var socket_io = require("socket.io");
var io = socket_io();
app.io = io;

// Set application variables
app.locals.ttapp = {};
app.locals.ttapp.api = 'http://localhost:8001';

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: false }));
app.use(lessMiddleware(path.join(__dirname, 'public')));
app.use(express.static(path.join(__dirname, 'public')));

// Create all routes
var index = require('./src/routes/index');

app.use('/', index);

// Not Found (404) Handler
app.use(function (req, res, next) {
    var err = new Error('Not Found');
    err.status = 404;
    next(err);
});

// Error Handler
app.use(function (err, req, res, next) {
    // set locals, only providing error in development
    res.locals.message = err.message;
    res.locals.error = req.app.get('env') === 'development' ? err : {};

    // render the error page
    res.status(err.status || 500);

    if (err.status == 404) {
        // respond with html page
        if (req.accepts('html')) {
            res.render('404', { url: req.url });
            return;
        }

        // respond with json
        if (req.accepts('json')) {
            res.send({ error: 'Not found' });
            return;
        }

        // default to plain-text. send()
        res.type('txt').send('Not found');
    } else {
        if (err.response !== undefined) {
            debug("Axios error message: " + err.response.data.trim());
        }
        res.render('error');
    }
});

module.exports = app;