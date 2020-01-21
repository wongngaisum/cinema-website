var express = require('express');
var router = express.Router();

router.get('/', function(req, res, next) {
  req.session.destroy((err) => {
    if (err)
      console.log("Cannot access session");
    else
      res.render('message', {title:'Logging out', status: 'Logging out', location: 'index', size: 'h2'});
    });
});

module.exports = router;
