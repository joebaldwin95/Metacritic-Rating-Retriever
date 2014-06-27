<!DOCTYPE html>
<head>
<!-- Title - Needs Revision -->
<title>Metacritic Rating Fetcher</title>
<!-- Custom Styles not applied inline !!Possibly a redundant stylesheet!! -->
<link href="css/style.css" rel="stylesheet" type="text/css">
  <!-- Animation Framework -->
  <link href="css/animate.css" rel="stylesheet" type="text/css">
  <!-- Charset = UTF8 -->
   <meta charset="utf-8">
   <!-- Metadata description of site -->
   <meta name="description" content="A quick and easy way to retrieve metacritic ratings without the cluttered layout of metacritics own site.">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Viewport set to prevent user scrolling on mobile devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1, user-scalable=no">
    <!-- GoogleFont used for <p> tag -->
    <link href='http://fonts.googleapis.com/css?family=Francois+One' rel='stylesheet' type='text/css'>
    <!-- Author Meta -->
    <meta name="author" content="Joe Baldwin">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<!-- Iphone Home Screen Icon -->
    <link rel="apple-touch-icon" href="my_icons/my_icon_file_path.png">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    

</head>

<body>
 <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <!-- jQuery Mobile customized framework to enable touch input for slider -->
    <script src="js/jquery.mobile.custom.min.js"></script>
    <script src="js/jquery.mobile.custom.js"></script>
    <script src="js/knob.js"></script>

<!-- Responsive Grid Start -->

<div id="cont" class="container-fluid">

<div class="row" >

<div class="col-md-12">


<!-- Top Nav Bar !!LINK THIS!! -->
<nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="background-color: #428bca;">
  <div class="container">
    <p style="text-align: center; padding-top: 13px; text-shadow: 0px 1px 0px rgba(255,255,255,.3), 0px -1px 0px rgba(0,0,0,.7);
	font-size: 22px; font-family: 'Francois One', sans-serif;"><strong>Metacritic Rating Fetcher</strong></p>
  </div>
</nav>

<br><br>

<div class="panel-group" id="accordion" style="margin-top: -1em; margin-bottom: -1em;">
  <div class="panel panel-default">
    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" style="text-align: center;">
    <div class="panel-heading">
		
      		<h4 class="panel-title">
          	Search
      		</h4>
      	
    </div>
    </a>
    <div id="collapseOne" class="panel-collapse collapse">
      <div class="panel-body">
      	<!-- Start of Form Input -->
<form method="post" id="capture">
    <!-- Text Input Bar -->
    <input type="text" id="title" name="gtitle" style="text-align: center; margin-left: auto; margin-right: auto; width: 70%;" class="textinput form-control " placeholder="Game Title" >
  <br>
<!-- DropDown - Values passed used in API Call -->
  <div style="margin-left: auto; margin-right: auto; text-align: center;">
  <select name="platformlist" class="form-control textinput" style="margin-left: auto; margin-right: auto; text-align: center; width: 40%;">
  <option value="1">PS3</option>
  <option value="2">Xbox 360</option>
  <option value="3">PC</option>
  <option value="72496">PS4</option>
  <option value="80000">Xbox One</option>
  <option value="8">Wii</option>
  <option value="68410">Wii U</option>
</select>
<br>
<!-- Submit Button -->
<button type="submit" class="btn btn-primary" style="margin-left: auto; margin-right: auto; text-align: center;"><span class="glyphicon glyphicon-search"></span></button>
</div>
</form>
<!-- End Of Form Input -->
      </div>
    </div>
  </div>
</div>


<br>

<!-- Start of API Call and Logical PHP -->
<?php
//Begins session and references Unirest.php for API Call
session_start();
require_once '/home/joebaldwin95/vendor/mashape/unirest-php/lib/Unirest.php';

//Variables posted from form submit
$_SESSION['gametitle'] = $_POST["gtitle"];
$_SESSION['gameplatform'] = $_POST["platformlist"];

//Api call via Mashape will return json array
$_SESSION['response'] = Unirest::post(
  "https://byroredux-metacritic.p.mashape.com/search/game",
  array(
    "X-Mashape-Authorization" => "ghNSVBqVlPIxRwIa8yGghy7iYe9r89OE"
  ),
  array(
    "title" => $_SESSION['gametitle'],
    "max_pages" => 1, //Pages to retrieve results from on Metacritic
    "platform" => $_SESSION['gameplatform']
  )
);

	//Start of Search Result Statements
  	$_SESSION['printhis1'] = "{$_SESSION['response']->body->results[0]->name} {$_SESSION['response']->body->results[0]->platform}";
  	$_SESSION['printhis2'] = "{$_SESSION['response']->body->results[1]->name} {$_SESSION['response']->body->results[1]->platform}";
  	$_SESSION['printhis3'] = "{$_SESSION['response']->body->results[2]->name} {$_SESSION['response']->body->results[2]->platform}";

    //If all results come back null
    if ($_SESSION['response']->body->results[0]->score == null) {
    $_SESSION['printhis1'] = "No Results... Try a more accurate name or check spelling.";
    $_SESSION['printhis2'] = "None here either...";
    $_SESSION['printhis3'] = "Nope...";
  }
  //If last result is null
  if (!empty($_SESSION['response']->body->results[0]->score) && $_SESSION['response']->body->results[2]->score == 0) {
    $_SESSION['printhis1'] = "{$_SESSION['response']->body->results[0]->name} {$_SESSION['response']->body->results[0]->platform}";
    $_SESSION['printhis2'] = "{$_SESSION['response']->body->results[1]->name} {$_SESSION['response']->body->results[1]->platform}";
    $_SESSION['printhis3'] = "Only Two Matches Found";
  }

  //If only one result is returned
  if (!empty($_SESSION['response']->body->results[0]->score) && $_SESSION['response']->body->results[1]->score == 0 && $_SESSION['response']->body->results[2]->score == 0) {
    $_SESSION['printhis1'] = "{$_SESSION['response']->body->results[0]->name} {$_SESSION['response']->body->results[0]->platform}";
    $_SESSION['printhis2'] = "Only One Match Found";
    $_SESSION['printhis3'] = "Keep Moving Slim...";
  }


  //End of Search Result Statements
?>

<!-- Results slider displays top 3 results - hidden until search is complete -->
<div id="results" style="margin-left:auto; margin-right:auto; text-align:center; margin-top: -1em;" class="hidden">
<br>
<p style="text-align:center; margin-bottom: -0.5em;">Top 3 Results</p>
<br>
<!-- Bootstrap built in carousel -->
<div id="myCarousel" class="carousel slide" data-interval="5000" data-ride="carousel" style="margin-left:auto; margin-right:auto; text-align:center;">
        <!-- Carousel Items -->
        <div class="carousel-inner">
            <div class="active item">
                <p style="margin-left: auto; margin-right: auto; text-align: center;"><?php echo $_SESSION['printhis1'] ?></p>
                
                <input  type="text" value="<?php echo $_SESSION['response']->body->results[0]->score ?>" class="dial1" data-thickness=".2" data-linecap="round" data-readOnly="true" data-bgColor="white" data-fgColor="428bca">
           		
            </div>
            <div class="item">
                <p style="margin-left: auto; margin-right: auto; text-align: center;"><?php echo $_SESSION['printhis2'] ?></p>
                
                <input type="text" value="<?php echo $_SESSION['response']->body->results[1]->score ?>" class="dial1" data-thickness=".2" data-linecap="round" data-readOnly="true" data-bgColor="white" data-fgColor="428bca">
            	
            </div>
            <div class="item">
                <p style="margin-left: auto; margin-right: auto; text-align: center;"><?php echo $_SESSION['printhis3'] ?></p>
                
                <input type="text" value="<?php echo $_SESSION['response']->body->results[2]->score ?>" class="dial1" data-thickness=".2" data-linecap="round" data-readOnly="true" data-bgColor="white" data-fgColor="428bca">
            	
            </div>
        </div>
        <br><br><br><br>
        <!-- Carousel indicators - Circles at the bottom -->
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active" style="border: 2px solid #428bca;"></li>
            <li data-target="#myCarousel" data-slide-to="1" style="border: 2px solid #428bca;"></li>
            <li data-target="#myCarousel" data-slide-to="2" style="border: 2px solid #428bca;"></li>
        </ol>   
       
        <!-- Carousel nav - Hidden do not unhide-->
        <a class="carousel-control left hidden" href="#myCarousel" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
        </a>
        <a class="carousel-control right hidden" href="#myCarousel" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
        </a>
    </div>
</div>


	

<!-- Controls Radial Score Indicators -->
<script>
$(function() {
    $(".dial1").knob();
});
</script>



<!-- Enables Carousel Touch Support on Mobile devices !!USES JQUERY MOBILE!! -->
<script>
  $(document).ready(function() {  
  		 $("#myCarousel").swiperight(function() {  
    		  $(this).carousel('prev');  
	    		});  
		   $("#myCarousel").swipeleft(function() {  
		      $(this).carousel('next');  
	   });  
	});  
</script>


<!-- JQuery to make results visible and animated pending outcome of api call -->
<script type='text/javascript'>
var complete = <?php print $_SESSION['response']->code; ?>;
if (complete == 200) {
 	  $('#results').removeClass('hidden');
	  $('#results').addClass('animated fadeInUp');
	}
</script>





</div><!-- First Column -->
</div><!-- Row -->
</div><!-- Container -->
</body>


</html>


