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
                <a class="dropdown-item" href="">Add Movie Info</a>
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
              <a class="nav-link" href="search0.php">Search</a>
            </li>
          </ul>

          <section class="jumbotron text-center">
            <div class="container">
              <h1 class="Title">Add Movie</h1>
              <form action="" method="POST" class="text-left">

                <div class="form-group">
                  <label for="genre">Genre</label><br/>
                  <select class="multiselect" multiple="multiple" id="genre" name="genre[]">
                    <option class="MultiMenu">Action</option>
                    <option class="MultiMenu">Adult</option>
                    <option class="MultiMenu">Adventure</option>
                    <option class="MultiMenu">Animation</option>
                    <option class="MultiMenu">Comedy</option>
                    <option class="MultiMenu">Crime</option>
                    <option class="MultiMenu">Documentary</option>
                    <option class="MultiMenu">Drama</option>
                    <option class="MultiMenu">Family</option>
                    <option class="MultiMenu">Fantasy</option>
                    <option class="MultiMenu">Horror</option>
                    <option class="MultiMenu">Musical</option>
                    <option class="MultiMenu">Mystery</option>
                    <option class="MultiMenu">Romance</option>
                    <option class="MultiMenu">Sci-Fi</option>
                    <option class="MultiMenu">Short</option>
                    <option class="MultiMenu">Thriller</option>
                    <option class="MultiMenu">War</option>
                    <option class="MultiMenu">Western</option>
                  </select>
                </div>
              
                <div class="form-group">
                  <label for="rating">MPAA Rating</label>
                  <select class="form-control" id="rating" name="rating">
                    <option>G</option>
                    <option>NC-17</option>
                    <option>PG</option>
                    <option>PG-13</option>
                    <option>R</option>
                    <option>surrendere</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="title">Title</label>
                  <input type="text" class="form-control" id="title" name="title">
                </div>

                <div class="form-group">
                  <label for="company">Company</label>
                  <input type="text" class="form-control" id="company" name="company">
                </div>

                <div class="form-group">
                  <label for="year">Year</label>
                  <input type="text" class="form-control" id="year" placeholder="1994" name="year">
                </div>

                <input class="btn btn-primary Button" type="submit" value="Submit" name="submit">
              </form>

              <div class="Result">
                <?php
                  if(isset($_POST["submit"]) AND isset($_POST["genre"]) AND $_POST["rating"] AND $_POST["title"] AND $_POST["company"] AND $_POST["year"]){
                    $db = new mysqli('localhost', 'cs143', '', 'CS143');
                    if($db->connect_errno > 0){
                      die('Unable to connect to database [' . $db->connect_error . ']');
                    }

                    // read all inputs
                    $genres = $_POST["genre"];
                    $genre_str = implode(", ", $genres);
                    $rating = $_POST["rating"];
                    $title = $_POST["title"];
                    $company = $_POST["company"];
                    $year = $_POST["year"];

                    // protect database from sensitive words
                    $title = str_replace("'", "\'", $title);
                    $title = str_replace("\"", "\"", $title);
                    $company = str_replace("'", "\'", $company);
                    $company = str_replace("\"", "\"", $company);

                    // validation check
                    if(!preg_match('/^[0-9]{4}$/', $year)){
                      echo "Invalid Year. Please try again.";
                    } else {

                      // derive the max ID
                      $query = "SELECT * FROM MaxMovieID;";
                      if (!($rs1 = $db->query($query))) {
                        $errmsg = $db->error;
                        print "Query failed: $errmsg <br/>";
                        $db->close();
                        exit(1);
                      }

                      $maxID;
                      while($row = $rs1->fetch_assoc()) {
                        foreach($row as $val){
                          $maxID = $val;
                        }
                      }

                      // start the query
                      $query = "INSERT INTO Movie VALUES (".$maxID.", '".$title."', ".$year.", '".$rating."', '".$company."');";
                      if (!($rs2 = $db->query($query))) {
                        $errmsg = $db->error;
                        print "Query failed: $errmsg <br/>";
                        $db->close();
                        exit(1);
                      }

                      foreach($genres as $genre){
                        $query = "INSERT INTO MovieGenre VALUES (".$maxID.", '".$genre."');";
                        if (!($rs3 = $db->query($query))) {
                          $errmsg = $db->error;
                          print "Query failed: $errmsg <br/>";
                          $db->close();
                          exit(1);
                        }
                      }

                      echo "Successfully Added to Movie and Movie Genre: ".$maxID.", ".$genre_str.", ".$rating.", ".$title.", ".$company.", ".$year;

                      // update the max ID
                      $maxID++;
                      $query = "UPDATE MaxMovieID SET id=".$maxID.";";
                      if (!($rs3 = $db->query($query))) {
                        $errmsg = $db->error;
                        print "Query failed: $errmsg <br/>";
                        $db->close();
                        exit(1);
                      }
                      $db->close();
                    }
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