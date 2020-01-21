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
    res.render('message', {title:'Cart', status: 'You have not logged in', location: 'index', size: 'h1'});
  else
    film.find({'BroadCastID': req.query.BroadCastID}, (err, result) => {
      if (err || result.length == 0)
        res.render('message', {title:'Cart', status: 'Query Error! Please try again later.', location: 'buywelcome', size: 'h1'});
      else
        res.render('buyticket', {film: result[0], seats: req.query.chkBox});
    });
});

module.exports = router;
