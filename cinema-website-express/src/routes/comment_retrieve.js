var express = require('express');
var router = express.Router();

var mongoose = require('mongoose');
mongoose.connect('mongodb://localhost/cinema', {useNewUrlParser: true}, (err) => {
if (err)
  console.log("MongoDB connection error: " + err);
});

var film = mongoose.model('film');
var comment = mongoose.model('comment');

router.get('/', function(req, res, next) {
  if (req.session.login != undefined)
    film.find({'FilmName': req.query.name}).limit(1).exec((err, result) => {
      if (!err)
        comment.find({'FilmID': result[0].FilmID}, (err2, result2) => {
          if (!err2) {
            res.setHeader('Content-Type', 'application/json');
            res.send(JSON.stringify(result2));
          };
        });
    });
});

module.exports = router;
