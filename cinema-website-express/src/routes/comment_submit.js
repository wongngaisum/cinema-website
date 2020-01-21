var express = require('express');
var router = express.Router();

var mongoose = require('mongoose');
mongoose.connect('mongodb://localhost/cinema', {useNewUrlParser: true}, (err) => {
if (err)
  console.log("MongoDB connection error: " + err);
});

var film = mongoose.model('film');
var comment = mongoose.model('comment');

router.post('/', function(req, res, next) {
  if (req.session.login == undefined)
    res.render('message', {title:'Movie Comments', status: 'You have not logged in', location: 'index', size: 'h1'});
  else
    film.find({'FilmName': req.body.filmName}).limit(1).exec((err, result) => {
      if (err)
        res.render('message', {title:'Movie Comments', status: 'Query Error! Please try again later.', location: 'comment', size: 'h1'});
      else {
        var newRecord = new comment({
          'UserID': req.session.login,
          'FilmID': result[0].FilmID,
          'Comment': req.body.comments
        });
        newRecord.save((err2, result2) => {
          if (err2)
            res.render('message', {title:'Movie Comments', status: 'Query Error! Please try again later.', location: 'comment', size: 'h1'});
          else
            res.render('message', {title:'Movie Comments', status: 'Your comment has been submitted.', location: 'comment', size: 'h1'});
        });
      }
    });
});

module.exports = router;
