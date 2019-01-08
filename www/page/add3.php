<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Yufei Movie Arena">
    <meta name="author" content="Yufei Hu">
    <link rel="icon" href="../image/icon.png">

    <title>Add</title>
    <script type="text/javascript" src="../js/jquery.min.js"></script>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/album.css" rel="stylesheet">
    <link href="../css/cover.css" rel="stylesheet">
    <link href="../css/bootstrap-multiselect.css" rel="stylesheet" type="text/css"/>

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
        font-weight: bold;
        margin-top: 0px;
        margin-bottom: 50px;
        z-index: 5;
        width: 1110px;
        background-color: #FF4500;
        font-size: 1em;
        text-shadow: none;
      }

      .DropItem {
        font-weight: bold;
        font-size: 1em;
      }

      .Title {
        margin-bottom: 50px;
        color: #FF4500;
      }

      .Button {
        margin-top: 20px;
        margin-left: 39.2%;
        margin-right: 39.2%;
        width: 200px;
        background-color:#DF013A;
        border-color: #DF013A;
      }

      .Result {
        margin-top: 40px;
      }

      .MultiMenu {
        text-shadow: none;
        background-color: #FF4500;
      }
    </style>
  </head>

  <body>

    <img src="../image/cover2.jpg" alt="" id="background" />

     <div id="maincontent" class="site-wrapper">
      <div class="site-wrapper-inner">
        <div class="container">

          <ul class="masthead clearfix nav nav-tabs nav-fill cover-container", id="TopBlock">
            <li class="nav-item">
              <a class="nav-link" href="../index.php">Home</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle active" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Add</a>
              <div class="dropdown-menu DropItem">
                <a class="dropdown-item" href="add0.php">Add Actor/Director</a>
                <a class="dropdown-item" href="add1.php">Add Movie Info</a>
                <a class="dropdown-item" href="add2.php">Add Movie/Actor Relation</a>
                <a class="dropdown-item" href="">Add Movie/Director Relation</a>
                <a class="dropdown-item" href="add4.php">Add Movie Comment</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Browse</a>
              <div class="dropdown-menu DropItem">
                <a class="dropdown-item" href="browse0.php">Browse Actor Info</a>
                <a class="dropdown-item" href="browse1.php">Browse Movie Info</a>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="search0.php">Search</a>
            </li>
          </ul>

          <section class="jumbotron text-center">
            <div class="container">
              <h1 class="Title">Add Movie/Director Relation</h1>
              <form action="" method="POST" class="text-left">

                <datalist id="movieTitleList">
                  <?php
                    $db = new mysqli('localhost', 'cs143', '', 'CS143');
                    if($db->connect_errno > 0){
                      die('Unable to connect to database [' . $db->connect_error . ']');
                    }
                    $query = "SELECT title, year FROM Movie;";
                    if (!($rs = $db->query($query))) {
                      $errmsg = $db->error;
                      print "Query failed: $errmsg <br/>";
                      $db->close();
                      exit(1);
                    }
                    $result = "";
                    while($row = $rs->fetch_assoc()) {
                      $result .= "<option value=\"";
                      $result .= $row["title"]." (";
                      $result .= $row["year"].")";
                      $result .= "\">";
                    }
                    echo $result;
                  ?>
                </datalist>
                <div class="form-group">
                  <label for="movieTitle">Movie Title</label>
                  <input type="text" class="form-control" id="movieTitle" name="movieTitle" list="movieTitleList">
                </div>

                <datalist id="directorList">
                  <?php
                    $db = new mysqli('localhost', 'cs143', '', 'CS143');
                    if($db->connect_errno > 0){
                      die('Unable to connect to database [' . $db->connect_error . ']');
                    }
                    $query = "SELECT last, first, dob FROM Director;";
                    if (!($rs = $db->query($query))) {
                      $errmsg = $db->error;
                      print "Query failed: $errmsg <br/>";
                      $db->close();
                      exit(1);
                    }
                    $result = "";
                    while($row = $rs->fetch_assoc()) {
                      $result .= "<option value=\"";
                      $result .= $row["first"]." ";
                      $result .= $row["last"]." (";
                      $result .= $row["dob"].")";
                      $result .= "\">";
                    }
                    echo $result;
                  ?>
                </datalist>
                <div class="form-group">
                  <label for="director">Director</label>
                  <input type="text" class="form-control" id="director" name="director" list="directorList">
                </div>

                <input class="btn btn-primary Button" type="submit" value="Submit" name="submit">
              </form>

              <div class="Result">
                <?php
                  if(isset($_POST["submit"]) AND isset($_POST["movieTitle"]) AND $_POST["director"]){
                    $db = new mysqli('localhost', 'cs143', '', 'CS143');
                    if($db->connect_errno > 0){
                      die('Unable to connect to database [' . $db->connect_error . ']');
                    }

                    // read all inputs
                    $movieTitle_str = $_POST["movieTitle"];
                    $director_str = $_POST["director"];

                    // protect database from sensitive words
                    $movieTitle_str = str_replace("'", "\'", $movieTitle_str);
                    $movieTitle_str = str_replace("\"", "\"", $movieTitle_str);
                    $director_str = str_replace("'", "\'", $director_str);
                    $director_str = str_replace("\"", "\"", $director_str);

                    // validation check
                    if(strlen($movieTitle_str) == 0 OR $movieTitle_str[strlen($movieTitle_str) - 1] != ')'){
                      echo "Invalid Movie Title. Please try again.";
                      $db->close();
                      exit(1);
                    }

                    if(strlen($director_str) == 0 OR $director_str[strlen($director_str) - 1] != ')'){
                      echo "Invalid Director. Please try again.";
                      $db->close();
                      exit(1);
                    }

                    $movieTitle = "";
                    $movieYear = "";
                    $i = strlen($movieTitle_str) - 2;
                    $flag_year = true;
                    while($i >= 0) {
                      if($movieTitle_str[$i] == '('){
                        $flag_year = false;
                        $i--;
                        $i--;
                      }

                      if($i >= 0){
                        if($flag_year == true){
                          $movieYear = $movieTitle_str[$i].$movieYear;
                        } else{
                          $movieTitle = $movieTitle_str[$i].$movieTitle;
                        }
                        $i--;
                      }
                    }
                    if(strlen($movieTitle) == 0){
                      echo "Invalid Movie Title. Please try again.";
                      $db->close();
                      exit(1);
                    }

                    $directorFirst = "";
                    $directorLast = "";
                    $directorDoB = "";
                    $i = strlen($director_str) - 2;
                    $flag_fld = 2;
                    while($i >= 0) {
                      if($director_str[$i] == '('){
                        $flag_fld = 1;
                        $i--;
                        $i--;
                      } elseif($director_str[$i] == ' '){
                        $flag_fld = 0;
                        $i--;
                      }

                      if($i >= 0){
                        if($flag_fld == 2){
                          $directorDoB = $director_str[$i].$directorDoB;
                        } elseif($flag_fld == 1){
                          $directorLast = $director_str[$i].$directorLast;
                        } else{
                          $directorFirst = $director_str[$i].$directorFirst;
                        }
                        $i--;
                      }
                    }
                    if(strlen($directorLast) == 0 OR strlen($directorFirst) == 0){
                      echo "Invalid Director. Please try again.";
                      $db->close();
                      exit(1);
                    }

                    // derive mid and did
                    $query = "SELECT id FROM Movie WHERE title='".$movieTitle."' AND year=".$movieYear.";";
                    if (!($rs1 = $db->query($query))) {
                      $errmsg = $db->error;
                      print "Query failed: $errmsg <br/>";
                      $db->close();
                      exit(1);
                    }

                    $flag_mid = false;
                    while($row = $rs1->fetch_assoc()) {
                      foreach($row as $val){
                        $mid = $val;
                        $flag_mid = true;
                      }
                    }

                    if($flag_mid == false){
                      echo "Movie Title Not Found. Please try again.";
                      $db->close();
                      exit(1);
                    }

                    $query = "SELECT id FROM Director WHERE last='".$directorLast."' AND first='".$directorFirst."' AND dob='".$directorDoB."';";
                    if (!($rs2 = $db->query($query))) {
                      $errmsg = $db->error;
                      print "Query failed: $errmsg <br/>";
                      $db->close();
                      exit(1);
                    }

                    $flag_did = false;
                    while($row = $rs2->fetch_assoc()) {
                      foreach($row as $val){
                        $did = $val;
                        $flag_did = true;
                      }
                    }

                    if($flag_did == false){
                      echo "Director Not Found. Please try again.";
                      $db->close();
                      exit(1);
                    }

                    // start query
                    $query = "INSERT INTO MovieDirector VALUES (".$mid.", ".$did.");";
                    if (!($rs3 = $db->query($query))) {
                      $errmsg = $db->error;
                      print "Query failed: $errmsg <br/>";
                      $db->close();
                      exit(1);
                    }

                    echo "Successfully Added to MovieDirector: ".$movieTitle_str.", ".$director_str;
                    $db->close();

                  } else{
                    echo "Incomplete input";
                  }
                ?>
              </div>

            </div>
          </section>

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
    <script src="../js/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../js/jquery.min.js"><\/script>')</script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/holder.min.js"></script>
    <script src="../js/bootstrap-multiselect.js" type="text/javascript"></script>
    <script src="../js/jquery.fullbg.min.js" type="text/javascript"></script>
    <script src="../js/ie10-viewport-bug-workaround.js"></script>

    <script type="text/javascript">
      $(document).ready(function() {
        $('.multiselect').multiselect();
      });
    </script>

  </body>
</html>