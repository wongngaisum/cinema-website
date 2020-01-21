var express = require('express');
var router = express.Router();

var mongoose = require('mongoose');
mongoose.connect('mongodb://localhost/cinema', {useNewUrlParser: true}, (err) => {
if (err)
  console.log("MongoDB connection error: " + err);
});

var film = mongoose.model('film');
var ticket = mongoose.model('ticket');

router.get('/', function(req, res, next) {
  if (req.session.login == undefined)
    res.render('message', {title:'Purchase History', status: 'You have not logged in', location: 'index', size: 'h1'});
  else
    ticket.find({'UserID': req.session.login}, (err, result) => {
      if (err)
        res.render('message', {title:'Purchase History', status: 'Query Error! Please try again later.', location: 'main', size: 'h1'});
      else
        film.find((err2, result2) => {
          if (err2)
            res.render('message', {title:'Purchase History', status: 'Query Error! Please try again later.', location: 'main', size: 'h1'});
          else
            res.render('history', {username: req.session.login, tickets: result, films: result2});
        });
    });
});

module.exports = router;
