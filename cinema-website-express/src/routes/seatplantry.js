var express = require('express');
var router = express.Router();

var mongoose = require('mongoose');
mongoose.connect('mongodb://localhost/cinema', {useNewUrlParser: true}, (err) => {
if (err)
  console.log("MongoDB connection error: " + err);
});

var film = mongoose.model('film');
var house = mongoose.model('house');
var ticket = mongoose.model('ticket');

router.get('/', function(req, res, next) {
  if (req.session.login == undefined)
    res.render('message', {title:'Ticketing', status: 'You have not logged in', location: 'index', size: 'h1'});
  else
    film.find({'BroadCastID': req.query.option}).exec((err, result) => {
      if (err || result.length == 0)
        res.render('message', {title:'Ticketing', status: 'Query Error! Please try again later.', location: 'buywelcome', size: 'h1'});
      else
        house.find({'HouseID': result[0].HouseID}).exec((err2, result2) => {
          if (err2 || result2.length == 0)
            res.render('message', {title:'Ticketing', status: 'Query Error! Please try again later.', location: 'buywelcome', size: 'h1'});
          else
            ticket.find({'BroadCastID': req.query.option}).sort({'SeatNo': 1}).exec((err3, result3) => {
              if (err3 || result3.length == 0)
                res.render('message', {title:'Ticketing', status: 'Query Error! Please try again later.', location: 'buywelcome', size: 'h1'});
              else
                res.render('seatplantry', {film: result[0], house: result2[0], ticket: result3});
            });
        });
    });
});

module.exports = router;
