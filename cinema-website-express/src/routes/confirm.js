var express = require('express');
var router = express.Router();

var mongoose = require('mongoose');
mongoose.connect('mongodb://localhost/cinema', {useNewUrlParser: true}, (err) => {
if (err)
  console.log("MongoDB connection error: " + err);
});

var film = mongoose.model('film');
var ticket = mongoose.model('ticket');

router.post('/', function(req, res, next) {
  if (req.session.login == undefined)
    res.render('message', {title:'Order Confirmation', status: 'You have not logged in', location: 'index', size: 'h1'});
  else
    film.find({'BroadCastID': req.body.BroadCastID}, (err, result) => {
      if (err || result.length == 0)
        res.render('message', {title:'Order Confirmation', status: 'Query Error! Please try again later.', location: 'buywelcome', size: 'h1'});
      else {
        res.render('confirm', {seats: req.body, film: result[0]});
        for (var i in req.body)
          if (i != 'BroadCastID')
            ticket.findOneAndUpdate({'BroadCastID': req.body.BroadCastID, 'SeatNo': i},
                {$set: {'Valid': false, 'UserID': req.session.login, 'TicketType': (req.body[i] == 'adult') ? 'Adult' : 'Student/Senior', 'TicketFee': (req.body[i] == 'adult') ? '75' : '50'}}, (err2) => {
                  if (err2)
                    res.render('message', {title:'Order Confirmation', status: 'Query Error! Please try again later.', location: 'buywelcome', size: 'h1'});
                });
      }
    });
});

module.exports = router;
