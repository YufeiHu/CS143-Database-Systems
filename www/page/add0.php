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
                <a class="dropdown-item" href="">Add Actor/Director</a>
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
              <a class="nav-link" href="search0.php">Search</a>
            </li>
          </ul>


          <section class="jumbotron text-center">
            <div class="container">

              <h1 class="Title">Add Actor/Director</h1>

              <form action="" method="GET" class="text-left">
                <div class="form-group">
                  <label for="actorOrDirector">Actor or Director</label>
                  <select class="form-control" id="actorOrDirector" name="actorOrDirector">
                    <option>Actor</option>
                    <option>Director</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="maleOrFemale">Male or Female</label>
                  <select class="form-control" id="maleOrFemale" name="maleOrFemale">
                    <option>Male</option>
                    <option>Female</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="firstName">First Name</label>
                  <input type="text" class="form-control" id="firstName" name="firstName">
                </div>

                <div class="form-group">
                  <label for="lastName">Last Name</label>
                  <input type="text" class="form-control" id="lastName" name="lastName">
                </div>

                <div class="form-group">
                  <label for="DoB">Date of Birth</label>
                  <input type="text" class="form-control" id="DoB" placeholder="1994-08-02" name="DoB">
                </div>

                <div class="form-group">
                  <label for="DoD">Date of Death</label>
                  <input type="text" class="form-control" id="DoD" placeholder="2100-01-01 (Leave Blank if Alive Now)" name="DoD">
                </div>

                <input class="btn btn-primary Button" type="submit" value="Submit" name="submit">

              </form>


              <div class="Result">
                <?php
                  if(isset($_GET["submit"]) AND $_GET["actorOrDirector"] AND $_GET["maleOrFemale"] AND $_GET["firstName"] AND $_GET["lastName"] AND $_GET["DoB"]){
                    $db = new mysqli('localhost', 'cs143', '', 'CS143');
                    if($db->connect_errno > 0){
                      die('Unable to connect to database [' . $db->connect_error . ']');
                    }


                    // read all inputs
                    $actorOrDirector = $_GET["actorOrDirector"];
                    $maleOrFemale = $_GET["maleOrFemale"];
                    $firstName = $_GET["firstName"];
                    $lastName = $_GET["lastName"];
                    $DoB = $_GET["DoB"];
                    $DoD = $_GET["DoD"];


                    // validation check
                    if(!preg_match('/^[A-Za-z\'\-\.]+$/', $firstName)){
                      echo "Invalid First Name. Please try again.";
                      $db->close();
                      exit(1);
                    }

                    if(!preg_match('/^[A-Za-z\'\-\.]+$/', $lastName)){
                      echo "Invalid Last Name. Please try again.";
                      $db->close();
                      exit(1);
                    }

                    if(!preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $DoB)){
                      echo "Invalid Date of Birth. Please try again.";
                      $db->close();
                      exit(1);
                    }

                    if($DoD AND (!preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $DoD))){
                      echo "Invalid Date of Death. Please try again.";
                      $db->close();
                      exit(1);
                    }


                    // protect database from sensitive words
                    $firstName = str_replace("'", "\'", $firstName);
                    $lastName = str_replace("'", "\'", $lastName);


                    // derive the max ID
                    $query = "SELECT * FROM MaxPersonID;";
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
                    if($actorOrDirector == "Actor" AND !$DoD){
                      $query = "INSERT INTO Actor VALUES (".$maxID.", '".$lastName."', '".$firstName."', '".$maleOrFemale."', '".$DoB."', NULL);";
                    } elseif ($actorOrDirector == "Actor" AND $DoD){
                      $query = "INSERT INTO Actor VALUES (".$maxID.", '".$lastName."', '".$firstName."', '".$maleOrFemale."', '".$DoB."', '".$DoD."');";
                    } elseif ($actorOrDirector == "Director" AND !$DoD){
                      $query = "INSERT INTO Director VALUES (".$maxID.", '".$lastName."', '".$firstName."', '".$DoB."', NULL);";
                    } else{
                      $query = "INSERT INTO Director VALUES (".$maxID.", '".$lastName."', '".$firstName."', '".$DoB."', '".$DoD."');";
                    }

                    if (!($rs2 = $db->query($query))) {
                      $errmsg = $db->error;
                      print "Query failed: $errmsg <br/>";
                      $db->close();
                      exit(1);
                    }

                    if($actorOrDirector == "Actor" AND !$DoD){
                      echo "Successfully Added to Actor: ".$maxID.", ".$lastName.", ".$firstName.", ".$maleOrFemale.", ".$DoB.", Alive";
                    } elseif ($actorOrDirector == "Actor" AND $DoD){
                      echo "Successfully Added to Actor: ".$maxID.", ".$lastName.", ".$firstName.", ".$maleOrFemale.", ".$DoB.", ".$DoD;
                    } elseif ($actorOrDirector == "Director" AND !$DoD){
                      echo "Successfully Added to Director: ".$maxID.", ".$lastName.", ".$firstName.", ".$DoB.", Alive";
                    } else{
                      echo "Successfully Added to Director: ".$maxID.", ".$lastName.", ".$firstName.", ".$DoB.", ".$DoD;
                    }


                    // update the max ID
                    $maxID++;
                    $query = "UPDATE MaxPersonID SET id=".$maxID.";";
                    if (!($rs3 = $db->query($query))) {
                      $errmsg = $db->error;
                      print "Query failed: $errmsg <br/>";
                      $db->close();
                      exit(1);
                    }

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
    <script type="text/javascript" src="../js/jquery.fullbg.min.js"></script>
    <script src="../js/ie10-viewport-bug-workaround.js"></script>


	</body>
</html>