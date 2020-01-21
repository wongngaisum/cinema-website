var express = require('express');
var router = express.Router();

var mongoose = require('mongoose');
mongoose.connect('mongodb://localhost/cinema', {useNewUrlParser: true}, (err) => {
if (err)
  console.log("MongoDB connection error: " + err);
});

var login = mongoose.model('login');

router.post('/', function(req, res, next) {
  login.find({'UserID': req.body.username}, (err, result) => {
    if (err)
      res.render('message', {title:'Create Account', status: 'Query Error! Please try again later.', location: 'createaccount', size: 'h1'});
    else {
      if (result.length == 0) {
        var newRecord = new login({
          'UserID': req.body.username,
          'PW': req.body.password
        });
        newRecord.save((err, result) => {
          if (err) {
            res.render('message', {title:'Create Account', status: 'Query Error! Please try again later.', location: 'createaccount', size: 'h1'});
          } else {
            res.render('message', {title:'Create Account', status: 'Account created! Welcome', location: 'index', size: 'h1'});
          }
        });
      } else
        res.render('message', {title:'Create Account', status: 'Account already existed', location: 'createaccount', size: 'h1'});
    }
  });
});

module.exports = router;
