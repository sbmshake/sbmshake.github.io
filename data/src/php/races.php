<!DOCTYPE html>
<html>
  <title> Formula DRIFT 2018</title>
  <head>
  <link rel="stylesheet" type="text/css" href="http://localhost/SCRSK/data/src/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="http://localhost/SCRSK/data/src/css/main-style.css">
  <link rel="stylesheet" type="text/css" href="http://localhost/SCRSK/data/src/css/navbar-style.css">
  <link rel="stylesheet" type="text/css" href="http://localhost/SCRSK/data/src/css/races-style.css">
      <meta name="viewport" width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0>
      <style> body {padding: 0; margin: 0;} </style>
  </head>
  <body>
  <?php
  function connectToDb($query) {
    $servername = "localhost";
    $username = "formuladrift";
    $password = "147896325";
    $dbname = "formula_drift";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    return $conn->query($query);
    $conn->close();
  }
  function getUserData() {
    if($_SESSION["userType"] == "admin") {
      $_SESSION["id"] = -1;
      $_SESSION["firstName"] = "Admin";
      $_SESSION["lastName"] = "";
      $_SESSION["age"] = "";
      $_SESSION["carDesc"] = "";
    } else {
      $sql = 'SELECT * FROM drivers WHERE email="'.$_SESSION["email"].'"';
      $result = connectToDb($sql);
      if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION["userType"] = "user";
        $_SESSION["id"] = $row["id"];
        $_SESSION["firstName"] = $row["firstName"];
        $_SESSION["lastName"] = $row["lastName"];
        $_SESSION["age"] = 2018 - $row["age"];
        $_SESSION["carDesc"] = $row["car"];
      }
    }
  }
  function tableOfRaces() {
    $sql = 'SELECT * FROM races ORDER BY day, time DESC';
    $result = connectToDb($sql);
    
    if ($result->num_rows > 0) {
      echo "<table cellspacing=\"10\" style=\"border:solid 10px white;\" width=\"800px\" align=\"center\">";
      while($row = $result->fetch_assoc()) {
        $r = connectToDb('SELECT firstName, lastName FROM drivers WHERE id='.$row["con_1"].';');
        $con1 = $r->fetch_assoc();
        $r = connectToDb('SELECT firstName, lastName FROM drivers WHERE id='.$row["con_2"].';');
        $con2 = $r->fetch_assoc();
        if(!empty($con1) and !empty($con2)) {
          $raceId = 'row_'.$row["id"];
          echo '<tr id="'.$raceId.'" style="background-color:black" height="100%">';
          echo "<td class=\"races\" cellpadding=\"10px\" width=\"300px\" align=\"center\">";
          echo "<p class=\"races name\">".$con1["firstName"]." <b>".$con1["lastName"]."</b><br></td>";
          echo "<td class=\"races\" cellpadding=\"10px\" width=\"200px\" align=\"center\">";
          echo "<p class=\"races vs\">VS</p>";
          echo "<p class=\"races date-time\">".$row["day"];
          echo "<br />".substr($row["time"], 0, 5)."</p></td>";
          echo "<td class=\"races\" cellpadding=\"10px\" width=\"300px\" align=\"center\">";
          echo "<p class=\"races name\">".$con2["firstName"]." <b>".$con2["lastName"]."</b><br></td>";
          echo '</tr>';
        }
      }
      echo "</table>";
    } else {
      echo "0 results";
    }
  }

  function navbarUser() {
    if($_SESSION["userType"] != "guest") {
      echo "<a class=\"navbarBtn right\" style=\"background-color: rgb(230, 0, 100);\" href=\"http://localhost/SCRSK/data/src/php/editinfo.php\">ACC: ".strtoupper($_SESSION["firstName"])."</a>";
      echo "<a class=\"navbarBtn right\" href=\"http://localhost/SCRSK/index.php?logout\">LOG OUT</a>";
    }
  }

  session_start();
  getUserData();
  ?>
    <div id="header">
    </div>
    <div id="navbar">
      <a class="navbarBtn" style="margin-left: 50px;" href="http://localhost/SCRSK/index.php">HOME</a>
      <a class="navbarBtn" href="http://localhost/SCRSK/data/src/php/drivers.php">CONTESTANTS</a>
      <a class="navbarBtn" href="javascript:void(0)" onclick="window.location.reload()">RACES</a>
      <?php navbarUser(); ?>
    </div>
    <div id="maincontent">
      <br>
      <section id="contestants" style="margin-top: 100px;">
        <h1><span>FORMULA DRIFT RACES</span></h1>
        <hr>
        <?php tableOfRaces(); ?>
      </section>

      <hr>
    </div>
</p>
    <!--
    <script src="sketch.js"></script>
    -->

<script> /* NAVBAR */
        window.onscroll = function() {navbarExpand()};
        var navbarBtns = document.getElementsByClassName("navbarBtn");
        var navbar = document.getElementById("navbar");
        var startScrollPosition = window.pageYOffset;
        function navbarExpand() {
          var padding = "";
          var videoHeight = 200;
          var color = "";
          if(window.pageYOffset == startScrollPosition) {
            padding = "20px";
          }
          else {
              padding = "10px";
          }
          if(window.pageYOffset - startScrollPosition < videoHeight){
              color = "rgba(0, 0, 0, " + (0.5 + (window.pageYOffset - startScrollPosition) / (2 * videoHeight)).toString() + ")";
              padding = (20 - (window.pageYOffset - startScrollPosition) / (0.1 * videoHeight)).toString() + "px";
          }
          else {
              color = "rgb(0, 0, 0)";
              padding = "10px";
          }
          navbar.style.backgroundColor = color;
          for(var i = 0; i < navbarBtns.length; i++) {
            navbarBtns[i].style.paddingBottom = padding;
            navbarBtns[i].style.paddingTop= padding;
          }
        }
      </script>

  </body>
</html>