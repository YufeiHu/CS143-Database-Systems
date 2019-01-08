<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Yufei Movie Arena">
    <meta name="author" content="Yufei Hu">
    <link rel="icon" href="../image/icon.png">

    <title>Browse</title>
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
        margin-top: 0px;
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
        background-color: #030C28;
        font-size: 1em;
        text-shadow: none;
      }

      .DropItem {
        font-weight: bold;
        font-size: 1em;
      }

      .Title {
        margin-bottom: 70px;
        color: #5876D9;
      }

      .Button {
        margin-top: 50px;
        margin-bottom: 20px;
        margin-left: 39.2%;
        margin-right: 39.2%;
        width: 200px;
        background-color:#2F53C6;
        border-color: #2F53C6;
      }

      .Result {
        margin-top: 40px;
      }

      .MultiMenu {
        text-shadow: none;
        background-color: #FF4500;
      }

      .table th {
        text-align: center;   
      }

      .table {
        border-top: 2px solid;
        border-bottom: 2px solid;
      }
    </style>
  </head>

  <body>

    <img src="../image/cover3.jpeg" alt="" id="background" />

     <div id="maincontent" class="site-wrapper">
      <div class="site-wrapper-inner">
        <div class="container">

          <ul class="masthead clearfix nav nav-tabs nav-fill cover-container", id="TopBlock">
            <li class="nav-item">
              <a class="nav-link" href="../index.php">Home</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Add</a>
              <div class="dropdown-menu DropItem">
                <a class="dropdown-item" href="add0.php">Add Actor/Director</a>
                <a class="dropdown-item" href="add1.php">Add Movie Info</a>
                <a class="dropdown-item" href="add2.php">Add Movie/Actor Relation</a>
                <a class="dropdown-item" href="add3.php">Add Movie/Director Relation</a>
                <a class="dropdown-item" href="add4.php">Add Movie Comment</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle active" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Browse</a>
              <div class="dropdown-menu DropItem">
                <a class="dropdown-item" href="browse0.php">Browse Actor Info</a>
                <a class="dropdown-item" href="">Browse Movie Info</a>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="search0.php">Search</a>
            </li>
          </ul>

          <section class="jumbotron text-center">
            <div class="container">
              <h1 class="Title">Browse Movie Info</h1>
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
                  <input type="text" class="form-control" id="movieTitle" name="movieTitle" list="movieTitleList" placeholder="Movie Name">
                </div>

                <input class="btn btn-primary Button" type="submit" value="Browse" name="submit">
              </form>

              <div class="Result">
                <?php
                  if(isset($_POST["submit"]) AND isset($_POST["movieTitle"])){
                    $db = new mysqli('localhost', 'cs143', '', 'CS143');
                    if($db->connect_errno > 0){
                      die('Unable to connect to database [' . $db->connect_error . ']');
                    }

                    // read all inputs
                    $movieTitle_str = $_POST["movieTitle"];

                    // protect database from sensitive words
                    $movieTitle_str = str_replace("'", "\'", $movieTitle_str);
                    $movieTitle_str = str_replace("\"", "\"", $movieTitle_str);

                    // validation check
                    if(strlen($movieTitle_str) == 0 OR $movieTitle_str[strlen($movieTitle_str) - 1] != ')'){
                      echo "Invalid Movie Title. Please try again.";
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

                    // derive mid
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

                    // fetch basic information
                    $query = "SELECT title, year, rating, company FROM Movie WHERE id=".$mid.";";
                    if (!($rs = $db->query($query))) {
                      $errmsg = $db->error;
                      print "Query failed: $errmsg <br/>";
                      $db->close();
                      exit(1);
                    }

                    while($row = $rs->fetch_assoc()) {
                      $title = $row['title'];
                      $year = $row['year'];
                      $rating = $row['rating'];
                      $company = $row['company'];
                    }

                    $query = "SELECT genre FROM MovieGenre WHERE mid=".$mid.";";
                    if (!($rs = $db->query($query))) {
                      $errmsg = $db->error;
                      print "Query failed: $errmsg <br/>";
                      $db->close();
                      exit(1);
                    }

                    $genre = "";
                    $flag_first = 1;
                    while($row = $rs->fetch_assoc()) {
                      if($flag_first == 1){
                        $genre .= $row['genre'];
                        $flag_first = 0;
                      } else{
                        $genre .= ", ".$row['genre'];
                      }
                    }

                    $query = "SELECT did FROM MovieDirector WHERE mid=".$mid.";";
                    if (!($rs = $db->query($query))) {
                      $errmsg = $db->error;
                      print "Query failed: $errmsg <br/>";
                      $db->close();
                      exit(1);
                    }

                    $director_id = array();
                    while($row = $rs->fetch_assoc()) {
                      $director_id[] = $row['did'];
                    }

                    $director = "";
                    $flag_first = 1;
                    foreach($director_id as $did_cur){
                      $query = "SELECT last, first, dob FROM Director WHERE id=".$did_cur.";";
                      if (!($rs = $db->query($query))) {
                        $errmsg = $db->error;
                        print "Query failed: $errmsg <br/>";
                        $db->close();
                        exit(1);
                      }
                      while($row = $rs->fetch_assoc()) {
                        if($flag_first == 1){
                          $director .= $row['first']." ".$row['last']." (".$row['dob'].")";
                          $flag_first = 0;
                        } else{
                          $director[] = ", ".$row['first']." ".$row['last']." (".$row['dob'].")";
                        }
                      }
                    }

                    echo "<h1>".$title."</h1><br/><br/>";
                    echo "<h2>Basic Information</h2>";
                    $basicTable = "<table class=\"table\">";
                    $basicTable .= "<tbody>";
                    $basicTable .= "<tr><th scope=\"row\">Producer</th><td>".$company."</td></tr>";
                    $basicTable .= "<tr><th scope=\"row\">Year</th><td>".$year."</td></tr>";
                    $basicTable .= "<tr><th scope=\"row\">MPAA Rating</th><td>".$rating."</td></tr>";
                    $basicTable .= "<tr><th scope=\"row\">Genre</th><td>".$genre."</td></tr>";
                    $basicTable .= "<tr><th scope=\"row\">Director</th><td>".$director."</td></tr>";
                    $basicTable .= "</tbody></table>";
                    echo $basicTable."<br/><br/>";

                    // fetch Actor information
                    $query = "SELECT aid, role FROM MovieActor WHERE mid=".$mid.";";
                    if (!($rs = $db->query($query))) {
                      $errmsg = $db->error;
                      print "Query failed: $errmsg <br/>";
                      $db->close();
                      exit(1);
                    }

                    echo "<h2>Actors in the movie</h2>";
                    $result_flag = 0;
                    $result = "<table class=\"table\"><thead><tr><th scope=\"col\">Name</th><th scope=\"col\">Role</th></tr></thead>";
                    $result .= "<tbody>";
                    while($row = $rs->fetch_assoc()) {
                      $aid_cur = $row['aid'];
                      $query = "SELECT last, first FROM Actor WHERE id=".$aid_cur.";";
                      if (!($rs2 = $db->query($query))) {
                        $errmsg = $db->error;
                        print "Query failed: $errmsg <br/>";
                        $db->close();
                        exit(1);
                      }
                      while($row2 = $rs2->fetch_assoc()) {
                        $actorName_cur = $row2['first']." ".$row2['last'];
                      }
                      $role_cur = $row['role'];

                      $result .= "<tr>";
                      $result .= "<td><a href=\"browse0.php?id=".$aid_cur."\">".$actorName_cur."</a></td>";
                      $result .= "<td>".$role_cur."</td>";
                      $result .= "</tr>";
                      $result_flag = 1;
                    }
                    $result .= "</tbody></table><br/><br/>";

                    if($result_flag == 1){
                      echo $result;
                    } else{
                      echo "No actor is found.<br/><br/><br/><br/>";
                    }

                    // fetch review
                    $query = "SELECT name, time, rating, comment FROM Review WHERE mid=".$mid.";";
                    if (!($rs = $db->query($query))) {
                      $errmsg = $db->error;
                      print "Query failed: $errmsg <br/>";
                      $db->close();
                      exit(1);
                    }

                    $reviewNum = 0;
                    $reviewName = array();
                    $reviewTime = array();
                    $reviewScore = array();
                    $reviewRating = 0;
                    $reviewComment = array();
                    while($row = $rs->fetch_assoc()) {
                      $reviewName[] = $row['name'];
                      $reviewTime[] = $row['time'];
                      $reviewScore[] = $row['rating'];
                      $reviewRating = $reviewRating + $row['rating'];
                      $reviewComment[] = $row['comment'];
                      $reviewNum++;
                    }

                    if($reviewNum == 0){
                      echo "<a href=\"add4.php?movieInfo=".$title." (".$year.")\">Be the first one to comment this movie!</a>";
                    } else{
                      $reviewRating = $reviewRating / $reviewNum;
                      echo "<h2>User Review</h2>";
                      echo "<div class=\"text-left\">";
                      echo "Average score: ".$reviewRating."/5<br/>";
                      echo "Number of comments: ".$reviewNum."<br/><br/>";


                      for($ii=0; $ii<sizeof($reviewName); $ii++){
                        echo $reviewName[$ii]." rates: ".$reviewScore[$ii]."/5 @".$reviewTime[$ii]."<br/>";
                        echo "Comment says: <br/>".$reviewComment[$ii];
                        echo "<br/><br/>";
                      }

                      echo "<a href=\"add4.php?movieInfo=".$title." (".$year.")\">Leave your comment as well!</a>";
                      echo "</div>";
                    }

                    $db->close();
                  } else{

                    if(isset($_GET['id'])){
                      $db = new mysqli('localhost', 'cs143', '', 'CS143');
                      if($db->connect_errno > 0){
                        die('Unable to connect to database [' . $db->connect_error . ']');
                      }
                      $mid = $_GET['id'];

                      // fetch basic information
                      $query = "SELECT title, year, rating, company FROM Movie WHERE id=".$mid.";";
                      if (!($rs = $db->query($query))) {
                        $errmsg = $db->error;
                        print "Query failed: $errmsg <br/>";
                        $db->close();
                        exit(1);
                      }

                      while($row = $rs->fetch_assoc()) {
                        $title = $row['title'];
                        $year = $row['year'];
                        $rating = $row['rating'];
                        $company = $row['company'];
                      }

                      $query = "SELECT genre FROM MovieGenre WHERE mid=".$mid.";";
                      if (!($rs = $db->query($query))) {
                        $errmsg = $db->error;
                        print "Query failed: $errmsg <br/>";
                        $db->close();
                        exit(1);
                      }

                      $genre = "";
                      $flag_first = 1;
                      while($row = $rs->fetch_assoc()) {
                        if($flag_first == 1){
                          $genre .= $row['genre'];
                          $flag_first = 0;
                        } else{
                          $genre .= ", ".$row['genre'];
                        }
                      }

                      $query = "SELECT did FROM MovieDirector WHERE mid=".$mid.";";
                      if (!($rs = $db->query($query))) {
                        $errmsg = $db->error;
                        print "Query failed: $errmsg <br/>";
                        $db->close();
                        exit(1);
                      }

                      $director_id = array();
                      while($row = $rs->fetch_assoc()) {
                        $director_id[] = $row['did'];
                      }

                      $director = "";
                      $flag_first = 1;
                      foreach($director_id as $did_cur){
                        $query = "SELECT last, first, dob FROM Director WHERE id=".$did_cur.";";
                        if (!($rs = $db->query($query))) {
                          $errmsg = $db->error;
                          print "Query failed: $errmsg <br/>";
                          $db->close();
                          exit(1);
                        }
                        while($row = $rs->fetch_assoc()) {
                          if($flag_first == 1){
                            $director .= $row['first']." ".$row['last']." (".$row['dob'].")";
                            $flag_first = 0;
                          } else{
                            $director[] = ", ".$row['first']." ".$row['last']." (".$row['dob'].")";
                          }
                        }
                      }

                      echo "<h1>".$title."</h1><br/><br/>";
                      echo "<h2>Basic Information</h2>";
                      // echo "<span style=\"font-size:1.2em\">Basic Information</span><br/>";
                      $basicTable = "<table class=\"table\">";
                      $basicTable .= "<tbody>";
                      $basicTable .= "<tr><th scope=\"row\">Producer</th><td>".$company."</td></tr>";
                      $basicTable .= "<tr><th scope=\"row\">Year</th><td>".$year."</td></tr>";
                      $basicTable .= "<tr><th scope=\"row\">MPAA Rating</th><td>".$rating."</td></tr>";
                      $basicTable .= "<tr><th scope=\"row\">Genre</th><td>".$genre."</td></tr>";
                      $basicTable .= "<tr><th scope=\"row\">Director</th><td>".$director."</td></tr>";
                      $basicTable .= "</tbody></table>";
                      echo $basicTable."<br/><br/>";

                      // fetch Actor information
                      $query = "SELECT aid, role FROM MovieActor WHERE mid=".$mid.";";
                      if (!($rs = $db->query($query))) {
                        $errmsg = $db->error;
                        print "Query failed: $errmsg <br/>";
                        $db->close();
                        exit(1);
                      }

                      echo "<h2>Actors in the movie</h2>";
                      $result_flag = 0;
                      $result = "<table class=\"table\"><thead><tr><th scope=\"col\">Name</th><th scope=\"col\">Role</th></tr></thead>";
                      $result .= "<tbody>";
                      while($row = $rs->fetch_assoc()) {
                        $aid_cur = $row['aid'];
                        $query = "SELECT last, first FROM Actor WHERE id=".$aid_cur.";";
                        if (!($rs2 = $db->query($query))) {
                          $errmsg = $db->error;
                          print "Query failed: $errmsg <br/>";
                          $db->close();
                          exit(1);
                        }
                        while($row2 = $rs2->fetch_assoc()) {
                          $actorName_cur = $row2['first']." ".$row2['last'];
                        }
                        $role_cur = $row['role'];

                        $result .= "<tr>";
                        $result .= "<td><a href=\"browse0.php?id=".$aid_cur."\">".$actorName_cur."</a></td>";
                        $result .= "<td>".$role_cur."</td>";
                        $result .= "</tr>";
                        $result_flag = 1;
                      }
                      $result .= "</tbody></table><br/><br/>";

                      if($result_flag == 1){
                        echo $result;
                      } else{
                        echo "No actor is found.<br/><br/><br/><br/>";
                      }

                      // fetch review
                      $query = "SELECT name, time, rating, comment FROM Review WHERE mid=".$mid.";";
                      if (!($rs = $db->query($query))) {
                        $errmsg = $db->error;
                        print "Query failed: $errmsg <br/>";
                        $db->close();
                        exit(1);
                      }

                      $reviewNum = 0;
                      $reviewName = array();
                      $reviewTime = array();
                      $reviewScore = array();
                      $reviewRating = 0;
                      $reviewComment = array();
                      while($row = $rs->fetch_assoc()) {
                        $reviewName[] = $row['name'];
                        $reviewTime[] = $row['time'];
                        $reviewScore[] = $row['rating'];
                        $reviewRating = $reviewRating + $row['rating'];
                        $reviewComment[] = $row['comment'];
                        $reviewNum++;
                      }

                      if($reviewNum == 0){
                        echo "<a href=\"add4.php?movieInfo=".$title." (".$year.")\">Be the first one to comment this movie!</a>";
                      } else{
                        $reviewRating = $reviewRating / $reviewNum;
                        echo "<h2>User Review</h2>";
                        echo "<div class=\"text-left\">";
                        echo "Average score: ".$reviewRating."/5<br/>";
                        echo "Number of comments: ".$reviewNum."<br/><br/>";

                        for($ii=0; $ii<sizeof($reviewName); $ii++){
                          echo $reviewName[$ii]." rates: ".$reviewScore[$ii]."/5 @".$reviewTime[$ii]."<br/>";
                          echo "Comment says: <br/>".$reviewComment[$ii];
                          echo "<br/><br/>";
                        }

                        echo "<a href=\"add4.php?movieInfo=".$title." (".$year.")\">Leave your comment as well!</a>";

                        echo "</div>";
                      }

                      unset($_GET['id']);
                      $db->close();
                    }
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