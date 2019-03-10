var express = require('express');
var router = express.Router();

var indexTemplate = require('../pages/index/index-template.marko');

router.get('/', function (req, res, next) {
    res.marko(indexTemplate);
});

module.exports = router;