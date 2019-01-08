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
        background-color: #022412;
        font-size: 1em;
        text-shadow: none;
      }

      .DropItem {
        font-weight: bold;
        font-size: 1em;
      }

      .Title {
        margin-bottom: 70px;
        color: #229457;
      }

      .Button {
        margin-top: 50px;
        margin-bottom: 20px;
        margin-left: 39.2%;
        margin-right: 39.2%;
        width: 200px;
        background-color:#1B7244;
        border-color: #1B7244;
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

    <img src="../image/cover4.png" alt="" id="background" />

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
              <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Browse</a>
              <div class="dropdown-menu DropItem">
                <a class="dropdown-item" href="browse0.php">Browse Actor Info</a>
                <a class="dropdown-item" href="browse1.php">Browse Movie Info</a>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="">Search</a>
            </li>
          </ul>


          <section class="jumbotron text-center">
            <div class="container">
              <h1 class="Title">Search Info</h1>
              <form action="" method="POST" class="text-left">
                
                <div class="form-group">
                  <input type="text" class="form-control" id="searchInfo" name="searchInfo" placeholder="Movie Name / Actor Name">
                </div>

                <input class="btn btn-primary Button" type="submit" value="Search" name="submit">
              </form>


              <div class="Result">
                <?php
                  if(isset($_POST["submit"]) AND isset($_POST["searchInfo"])){
                    $db = new mysqli('localhost', 'cs143', '', 'CS143');
                    if($db->connect_errno > 0){
                      die('Unable to connect to database [' . $db->connect_error . ']');
                    }


                    // read all inputs
                    $searchInfo = $_POST["searchInfo"];
                    if(!$searchInfo){
                      echo "Please type in something.";
                      $db->close();
                      exit(1);
                    }


                    // protect database from sensitive words
                    $searchInfo = str_replace("'", "\'", $searchInfo);
                    $searchInfo = str_replace("\"", "\"", $searchInfo);


                    // split the info into an array
                    $searchInfos = preg_split('/ +/', $searchInfo);
                    

                    // search for movies
                    $flag_first = true;
                    $query = "SELECT id, title, year FROM Movie WHERE";
                    foreach($searchInfos as $info){
                      if($flag_first == true){
                        $query .= " title LIKE '%".$info."%'";
                        $flag_first = false;
                      }else{
                        $query .= " and title LIKE '%".$info."%'";
                      }
                    }
                    $query .= ";";

                    if (!($rs = $db->query($query))) {
                      $errmsg = $db->error;
                      print "Query failed: $errmsg <br/>";
                      $db->close();
                      exit(1);
                    }

                    $flag_first = false;
                    $result = "<table class=\"table\"><thead><tr><th scope=\"col\">Title</th><th scope=\"col\">Year</th></tr></thead>";
                    $result .= "<tbody>";
                    while($row = $rs->fetch_assoc()) {
                      $result .= "<tr>";
                      $result .= "<td><a href=\"browse1.php?id=".$row['id']."\">".$row['title']."</a></td>";
                      $result .= "<td>".$row['year']."</td>";
                      $result .= "</tr>";
                      $flag_first = true;
                    }
                    $result .= "</tbody></table><br/><br/>";

                    if($flag_first == false){
                      echo "No movies are found.";
                    }else{
                      echo "<h2>Matched Movies</h2>";
                      echo $result;
                    }


                    // search for actors
                    if(sizeof($searchInfos) > 2){
                      echo "No actors are found.";
                      $db->close();
                      exit(1);
                    }


                    $query = "SELECT id, last, first, dob FROM Actor WHERE";
                    if(sizeof($searchInfos) == 1){
                      $query .= " last LIKE '%".$searchInfos[0]."%' OR first LIKE '%".$searchInfos[0]."%';";
                    }elseif(sizeof($searchInfos) == 2){
                      $query .= " (last LIKE '%".$searchInfos[0]."%' AND first LIKE '%".$searchInfos[1]."%') OR (last LIKE '%".$searchInfos[1]."%' AND first LIKE '%".$searchInfos[0]."%');";
                    }else{
                      echo "No actors are found.";
                      $db->close();
                      exit(1);
                    }

                    if (!($rs = $db->query($query))) {
                      $errmsg = $db->error;
                      print "Query failed: $errmsg <br/>";
                      $db->close();
                      exit(1);
                    }

                    $flag_first = false;
                    $result = "<table class=\"table\"><thead><tr><th scope=\"col\">Name</th><th scope=\"col\">Date of Birth</th></tr></thead>";
                    $result .= "<tbody>";
                    while($row = $rs->fetch_assoc()) {
                      $result .= "<tr>";
                      $result .= "<td><a href=\"browse0.php?id=".$row['id']."\">".$row['first']." ".$row['last']."</a></td>";
                      $result .= "<td>".$row['dob']."</td>";
                      $result .= "</tr>";
                      $flag_first = true;
                    }
                    $result .= "</tbody></table><br/><br/>";

                    if($flag_first == false){
                      echo "No actors are found.";
                    }else{
                      echo "<h2>Matched Actors</h2>";
                      echo $result;
                    }

                    $db->close();
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