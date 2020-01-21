var createError = require('http-errors');
var express = require('express');
var path = require('path');
var cookieParser = require('cookie-parser');
var logger = require('morgan');

// connect to database
var mongoose = require('mongoose');
mongoose.connect('mongodb://localhost/cinema', {useNewUrlParser: true}, (err) => {
if (err)
  console.log("MongoDB connection error: " + err);
});



// define database schema
var Schema = mongoose.Schema;
var loginSchema = new Schema({
  'UserID': String,
  'PW': String
});
var login = mongoose.model('login', loginSchema);

var filmSchema = new Schema({
  'FilmID': Number,
  'FilmName': String,
  'Duration': String,
  'Category': String,
  'Language': String,
  'Director': String,
  'Description': String,
  'PosterName': String,
  'BroadCastID': Number,
  'Dates': String,
  'Time': String,
  'HouseID': String,
  'Day': String
});
var film = mongoose.model('film', filmSchema);

var houseSchema = new Schema({
  'HouseID': String,
  'HouseRow': Number,
  'HouseCol': Number
});
var house = mongoose.model('house', houseSchema);

var ticketSchema = new Schema({
  'SeatNo': String,
  'BroadCastID': Number,
  'Valid': Boolean,
  'UserID': String,
  'TicketType': String,
  'TicketFee': Number
});
var ticket = mongoose.model('ticket', ticketSchema);

var commentSchema = new Schema({
  'UserID': String,
  'FilmID': Number,
  'Comment': String
});
var comment = mongoose.model('comment', commentSchema);




// routers
var indexRouter = require('./routes/index');
var createAccountRouter = require('./routes/createaccount');
var verifyLoginRouter = require('./routes/verifyLogin');
var mainRouter = require('./routes/main');
var createRouter = require('./routes/create');
var logoutRouter = require('./routes/logout');
var buyWelcomeRouter = require('./routes/buywelcome');
var seatPlanTryRouter = require('./routes/seatplantry');
var buyTicketRouter = require('./routes/buyticket');
var confirmRouter = require('./routes/confirm');
var historyRouter = require('./routes/history');
var commentRouter = require('./routes/comment');
var commentSubmitRouter = require('./routes/comment_submit');
var commentRetrieveRouter = require('./routes/comment_retrieve');


var app = express();


// session info
var session = require('express-session');
app.use(session({
  secret: "123456",
  resave: true,
  saveUninitialized: true
}));
app.use(express.urlencoded({extended: false}));


// view engine setup
app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'pug');

app.use(logger('dev'));
app.use(express.json());
app.use(express.urlencoded({ extended: false }));
app.use(cookieParser());
app.use(express.static(path.join(__dirname, 'public')));

app.use('/', indexRouter);
app.use('/index', indexRouter);
app.use('/createaccount', createAccountRouter);
app.use('/verifyLogin', verifyLoginRouter);
app.use('/main', mainRouter);
app.use('/create', createRouter);
app.use('/logout', logoutRouter);
app.use('/buywelcome', buyWelcomeRouter);
app.use('/seatplantry', seatPlanTryRouter);
app.use('/buyticket', buyTicketRouter);
app.use('/confirm', confirmRouter);
app.use('/history', historyRouter);
app.use('/comment', commentRouter);
app.use('/comment_submit', commentSubmitRouter);
app.use('/comment_retrieve', commentRetrieveRouter);

// catch url and forward to handler
app.use(function(req, res, next) {
  if (req.path == '/index.html')
    res.redirect('index');
  else if (req.path == '/createaccount.html')
      res.redirect('createaccount');
  else if (req.path == '/main.html')
    res.redirect('main');
  else
    next(createError(404));
});

// error handler
app.use(function(err, req, res, next) {
  // set locals, only providing error in development
  res.locals.message = err.message;
  res.locals.error = req.app.get('env') === 'development' ? err : {};

  // render the error page
  res.status(err.status || 500);
  res.render('error');
});

module.exports = app;
