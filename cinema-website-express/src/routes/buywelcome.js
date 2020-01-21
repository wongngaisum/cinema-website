var express = require('express');
var router = express.Router();

var mongoose = require('mongoose');
mongoose.connect('mongodb://localhost/cinema', {useNewUrlParser: true}, (err) => {
if (err)
  console.log("MongoDB connection error: " + err);
});

var film = mongoose.model('film');

router.get('/', function(req, res, next) {
  if (req.session.login == undefined)
    res.render('message', {title:'Buy A Ticket', status: 'You have not logged in', location: 'index', size: 'h1'});
  else {
    film.find().sort({'FilmID': 1}).exec((err, result) => {
      if (err)
        res.render('message', {title:'Buy A Ticket', status: 'Query Error! Please try again later.', location: 'main', size: 'h1'});
      else
        res.render('buywelcome', {film: result});
    });
  }
});

module.exports = router;
