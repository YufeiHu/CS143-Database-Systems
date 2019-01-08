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
                <a class="dropdown-item" href="">Browse Actor Info</a>
                <a class="dropdown-item" href="browse1.php">Browse Movie Info</a>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="search0.php">Search</a>
            </li>
          </ul>


          <section class="jumbotron text-center">
            <div class="container">
              <h1 class="Title">Browse Actor Info</h1>
              <form action="" method="POST" class="text-left">

                <datalist id="actorList">
                  <?php
                    $db = new mysqli('localhost', 'cs143', '', 'CS143');
                    if($db->connect_errno > 0){
                      die('Unable to connect to database [' . $db->connect_error . ']');
                    }
                    $query = "SELECT last, first, dob FROM Actor;";
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
                  <input type="text" class="form-control" id="actor" name="actor" list="actorList" placeholder="Actor Name">
                </div>

                <input class="btn btn-primary Button" type="submit" value="Browse" name="submit">
              </form>

              <div class="Result">
                <?php
                  if(isset($_POST["submit"]) AND isset($_POST["actor"])){
                    $db = new mysqli('localhost', 'cs143', '', 'CS143');
                    if($db->connect_errno > 0){
                      die('Unable to connect to database [' . $db->connect_error . ']');
                    }

                    // read all inputs
                    $actor_str = $_POST["actor"];

                    // protect database from sensitive words
                    $actor_str = str_replace("'", "\'", $actor_str);
                    $actor_str = str_replace("\"", "\"", $actor_str);

                    // validation check
                    if(strlen($actor_str) == 0 OR $actor_str[strlen($actor_str) - 1] != ')'){
                      echo "Invalid Actor. Please try again.";
                      $db->close();
                      exit(1);
                    }

                    $actorFirst = "";
                    $actorLast = "";
                    $actorDoB = "";
                    $i = strlen($actor_str) - 2;
                    $flag_fld = 2;
                    while($i >= 0) {
                      if($actor_str[$i] == '('){
                        $flag_fld = 1;
                        $i--;
                        $i--;
                      } elseif($actor_str[$i] == ' '){
                        $flag_fld = 0;
                        $i--;
                      }

                      if($i >= 0){
                        if($flag_fld == 2){
                          $actorDoB = $actor_str[$i].$actorDoB;
                        } elseif($flag_fld == 1){
                          $actorLast = $actor_str[$i].$actorLast;
                        } else{
                          $actorFirst = $actor_str[$i].$actorFirst;
                        }
                        $i--;
                      }
                    }
                    if(strlen($actorLast) == 0 OR strlen($actorFirst) == 0){
                      echo "Invalid Actor. Please try again.";
                      $db->close();
                      exit(1);
                    }

                    // derive aid
                    $query = "SELECT id FROM Actor WHERE last='".$actorLast."' AND first='".$actorFirst."' AND dob='".$actorDoB."';";
                    if (!($rs2 = $db->query($query))) {
                      $errmsg = $db->error;
                      print "Query failed: $errmsg <br/>";
                      $db->close();
                      exit(1);
                    }

                    $flag_aid = false;
                    while($row = $rs2->fetch_assoc()) {
                      foreach($row as $val){
                        $aid = $val;
                        $flag_aid = true;
                      }
                    }

                    if($flag_aid == false){
                      echo "Actor Not Found. Please try again.";
                      $db->close();
                      exit(1);
                    }

                    // fetch basic information
                    $query = "SELECT * FROM Actor WHERE id=".$aid.";";
                    if (!($rs = $db->query($query))) {
                      $errmsg = $db->error;
                      print "Query failed: $errmsg <br/>";
                      $db->close();
                      exit(1);
                    }

                    $i_actor = 0;
                    while($row = $rs->fetch_assoc()) {
                      foreach($row as $val){
                        if($i_actor == 3){
                          $gender = $val;
                        } elseif($i_actor == 4){
                          $dob = $val;
                        } elseif($i_actor == 5){
                          $dod = $val;
                        }
                        $i_actor++;
                      }
                    }
                    echo "<h2>".$actorFirst." ".$actorLast."</h2>";
                    if($dod){
                      echo "(".$gender.", ".$dob." to ".$dod.")<br/><br/>";
                    } else{
                      echo "(".$gender.", ".$dob." to now)<br/><br/>";
                    }

                    // fetch movie information
                    $query = "SELECT * FROM MovieActor WHERE aid=".$aid.";";
                    if (!($rs = $db->query($query))) {
                      $errmsg = $db->error;
                      print "Query failed: $errmsg <br/>";
                      $db->close();
                      exit(1);
                    }

                    $result_flag = 0;
                    $result = "<table class=\"table\"><thead><tr><th scope=\"col\">Role</th><th scope=\"col\">Movie Title</th></tr></thead>";
                    $result .= "<tbody>";
                    while($row = $rs->fetch_assoc()) {
                      $mid_cur = $row['mid'];
                      $query = "SELECT title, year FROM Movie WHERE id=".$mid_cur.";";
                      if (!($rs2 = $db->query($query))) {
                        $errmsg = $db->error;
                        print "Query failed: $errmsg <br/>";
                        $db->close();
                        exit(1);
                      }
                      $moveTitle_cur = NULL;
                      $moveYear_cur = NULL;
                      while($row2 = $rs2->fetch_assoc()) {
                        $moveTitle_cur = $row2['title'];
                        $moveYear_cur = $row2['year'];
                        break;
                      }
                      $role_cur = $row['role'];

                      $result .= "<tr>";
                      $result .= "<td>".$role_cur."</td>";
                      $result .= "<td><a href=\"browse1.php?id=".$mid_cur."\">".$moveTitle_cur." (".$moveYear_cur.")</a></td>";
                      $result .= "</tr>";
                      $result_flag = 1;
                    }
                    $result .= "</tbody></table>";

                    if($result_flag == 1){
                      echo $result;
                    } else{
                      echo "No movie is found.";
                    }
                    
                    // $header_str = "Location: browse0.php?id=".$aid;
                    // echo header($header_str);
                    // die;
                    //echo '<a href="browse0.php?id='.$aid.'">Edit</a>';

                    $db->close();
                  } else{

                    if(isset($_GET['id'])){
                      $db = new mysqli('localhost', 'cs143', '', 'CS143');
                      if($db->connect_errno > 0){
                        die('Unable to connect to database [' . $db->connect_error . ']');
                      }
                      $aid = $_GET['id'];
                      
                      // fetch basic information
                      $query = "SELECT * FROM Actor WHERE id=".$aid.";";
                      if (!($rs = $db->query($query))) {
                        $errmsg = $db->error;
                        print "Query failed: $errmsg <br/>";
                        $db->close();
                        exit(1);
                      }

                      while($row = $rs->fetch_assoc()) {
                        $actorFirst = $row['first'];
                        $actorLast = $row['last'];
                        $gender = $row['sex'];
                        $dob = $row['dob'];
                        $dod = $row['dod'];
                      }
                      echo "<h2>".$actorFirst." ".$actorLast."</h2>";
                      if($dod){
                        echo "(".$gender.", ".$dob." to ".$dod.")<br/><br/>";
                      } else{
                        echo "(".$gender.", ".$dob." to now)<br/><br/>";
                      }

                      // fetch movie information
                      $query = "SELECT * FROM MovieActor WHERE aid=".$aid.";";
                      if (!($rs = $db->query($query))) {
                        $errmsg = $db->error;
                        print "Query failed: $errmsg <br/>";
                        $db->close();
                        exit(1);
                      }

                      $result_flag = 0;
                      $result = "<table class=\"table\"><thead><tr><th scope=\"col\">Role</th><th scope=\"col\">Movie Title</th></tr></thead>";
                      $result .= "<tbody>";
                      while($row = $rs->fetch_assoc()) {
                        $mid_cur = $row['mid'];
                        $query = "SELECT title, year FROM Movie WHERE id=".$mid_cur.";";
                        if (!($rs2 = $db->query($query))) {
                          $errmsg = $db->error;
                          print "Query failed: $errmsg <br/>";
                          $db->close();
                          exit(1);
                        }
                        $moveTitle_cur = NULL;
                        $moveYear_cur = NULL;
                        while($row2 = $rs2->fetch_assoc()) {
                          $moveTitle_cur = $row2['title'];
                          $moveYear_cur = $row2['year'];
                          break;
                        }
                        $role_cur = $row['role'];

                        $result .= "<tr>";
                        $result .= "<td>".$role_cur."</td>";
                        $result .= "<td><a href=\"browse1.php?id=".$mid_cur."\">".$moveTitle_cur." (".$moveYear_cur.")</a></td>";
                        $result .= "</tr>";
                        $result_flag = 1;
                      }
                      $result .= "</tbody></table>";

                      if($result_flag == 1){
                        echo $result;
                      } else{
                        echo "No movie is found.";
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