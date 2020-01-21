var express = require('express');
var router = express.Router();

router.get('/', function(req, res, next) {
    if (req.session.login == undefined)
      res.render('message', {title:'Main Page', status: 'You have not logged in', location: 'index', size: 'h1'});
    else
      res.render('main');
});

module.exports = router;
