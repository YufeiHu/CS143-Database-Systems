<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Yufei Movie Arena">
    <meta name="author" content="Yufei Hu">
    <link rel="icon" href="image/icon.png">

    <title>Yufei Movie Arena</title>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.fullbg.min.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/cover.css" rel="stylesheet">

    <style type="text/css">
      .fullBg {
        position: fixed;
        top: 0;
        left: 0;
        overflow: hidden;
      }

      #maincontent {
        position: absolute;
        top: 0;
        left: 0;
        z-index: 50;
      }

      #TopBlock {
        margin-top: 50px;
        margin-bottom: 0px;
        z-index: 5;
        font-size: 0.8em;
        text-shadow: none;
      }

      .DropItem {
        font-size: 1em;
      }

      .cover-heading {
        margin-top: 20px;
      }

      .lead {
        margin-top: 20px;
        margin-bottom: 20px;
      }

      #introBlock {
        height: 80px;
        width: 600px;
        margin: 0 15% 0 17%;
      }

      #introduction {
        font-size: 0.8em;
      }

      #mainGround {
        margin-top: 10px;
      }
    </style>
  </head>


  <body>

    <img src="image/cover1.jpg" alt="" id="background" />

    <div id="maincontent" class="site-wrapper">
      <div class="site-wrapper-inner">
        <div class="cover-container">


          <ul class="masthead clearfix nav nav-tabs nav-fill", id="TopBlock">
            <li class="nav-item">
              <a class="nav-link active" href="">Home</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="" role="button" aria-haspopup="true" aria-expanded="false">Add</a>
              <div class="dropdown-menu DropItem">
                <a class="dropdown-item" href="page/add0.php">Add Actor/Director</a>
                <a class="dropdown-item" href="page/add1.php">Add Movie Info</a>
                <a class="dropdown-item" href="page/add2.php">Add Movie/Actor Relation</a>
                <a class="dropdown-item" href="page/add3.php">Add Movie/Director Relation</a>
                <a class="dropdown-item" href="page/add4.php">Add Movie Comment</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Browse</a>
              <div class="dropdown-menu DropItem">
                <a class="dropdown-item" href="page/browse0.php">Browse Actor Info</a>
                <a class="dropdown-item" href="page/browse1.php">Browse Movie Info</a>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="page/search0.php">Search</a>
            </li>
          </ul>


          <div class="inner cover" id="mainGround">
            <h1 class="cover-heading">Yufei Movie Arena</h1>
            <p class="lead" id="introBlock"><span id="introduction">Welcome to the Yufei Movie Arena. You will be playing with the exciting movie databse system!</span></p>
          </div>

        </div>
      </div>
    </div>


    <script type="text/javascript">
      $(window).load(function(){
        $("#background").fullBg();
      });
    </script>


    <!-- Bootstrap core JavaScript -->
    <!-- ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>')</script>
    <script type="text/javascript" src="js/jquery.fullbg.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/ie10-viewport-bug-workaround.js"></script>
    
  </body>
</html>
