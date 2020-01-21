var express = require('express');
var router = express.Router();

var mongoose = require('mongoose');
mongoose.connect('mongodb://localhost/cinema', {useNewUrlParser: true}, (err) => {
if (err)
  console.log("MongoDB connection error: " + err);
});

var login = mongoose.model('login');

router.post('/', function(req, res, next) {
  login.find({'UserID': req.body.username, 'PW': req.body.password}, (err, result) => {
    if (err)
      res.render('message', {title:'Verify Login', status: 'Query Error! Please try again later.', location: 'index', size: 'h1'});
    else {
      if (result.length == 0)
        res.render('message', {title:'Verify Login', status: 'Invalid login, please login again.', location: 'index', size: 'h1'});
      else {
        req.session.login = req.body.username;
        console.log(req.session.login + ' logined');
        res.redirect('main');
      }
    }
  });
});

module.exports = router;
