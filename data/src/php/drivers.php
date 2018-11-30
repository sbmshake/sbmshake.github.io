<!DOCTYPE html>
<html>
  <title> Formula DRIFT 2018</title>
  <head>
  <link rel="stylesheet" type="text/css" href="http://localhost/SCRSK/data/src/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="http://localhost/SCRSK/data/src/css/main-style.css">
  <link rel="stylesheet" type="text/css" href="http://localhost/SCRSK/data/src/css/navbar-style.css">
  <link rel="stylesheet" type="text/css" href="http://localhost/SCRSK/data/src/css/drivers-style.css">
  <script src="http://localhost/SCRSK/data/src/js/jquery-3.3.1.min.js"></script>
  <script src="http://localhost/SCRSK/data/src/js/bootstrap.min.js"></script>
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
  function tableOfDrivers() {
    $order = "id, lastName, firstName";
    if(!isset($_SESSION["asc-desc"])) {
      $_SESSION["lastSort"] = "sortById";
      $_SESSION["asc-desc"] = "ASC";
    }
    
    if(isset($_GET["sortById"])) {
      checkPriority("sortById");
      $order = "id, lastName, firstName";
      $_SESSION["lastSort"] = "sortById";
    }
    if(isset($_GET["sortByFName"])) {
      checkPriority("sortByFirstName");
      $order = "firstName, lastName, age";
      $_SESSION["lastSort"] = "sortByFirstName";
    }
    if(isset($_GET["sortByLName"])) {
      checkPriority("sortByLastName");
      $order = "lastName, firstName, age";
      $_SESSION["lastSort"] = "sortByLastName";
    }
    if(isset($_GET["sortByAge"])) {
      checkPriority("sortByAge");
      $order = "age, lastName, firstName";
      $_SESSION["lastSort"] = "sortByAge";
    }
    $sql = 'SELECT firstName, lastName, picture, age, car FROM drivers ORDER BY '.$order.' '.$_SESSION["asc-desc"].';';
    $result = connectToDb($sql);
    
    if ($result->num_rows > 0) {
      echo "<table cellspacing=\"10\" border=\"0px\" width=\"712px\" align=\"center\"><tr>";
      // output data of each row
      $row_counter = 0;
      while($row = $result->fetch_assoc()) {
        if($row_counter > 3) {
          $row_counter = 0;
          echo "</tr><tr>";
        }
        echo "<td cellpadding=\"10px\" width=\"25%\" align=\"center\">";
        echo '<a style="text-decoration:none" href="javascript:void(0)" data-toggle="popover" title="'.$row["firstName"].' '.$row["lastName"].', '.$row["age"].'" data-content="'.$row["car"].'">';
        if($row["picture"] == NULL or $row["picture"] == "") {
          echo '<img src="http://localhost/SCRSK/data/img/profile_pic.png" style="width:128px; height:128px;">';
        } else {
          echo "<img src=\"data:image;base64,".base64_encode($row["picture"])."\" style=\"width:128px;\">";
        }
        echo "<br /><p class=\"drivers\">".$row["firstName"]."<br>";
        echo "<span>".strtoupper($row["lastName"])."</span></p>";
        echo '</a>';
        echo "</td>";
        $row_counter += 1;
      }
      echo "</tr></table>";
    } else {
      echo "0 results";
    }
  }

  function checkPriority($sprtType) {
    if($_SESSION["lastSort"] == $sprtType and $_SESSION["asc-desc"] == "ASC") { $_SESSION["asc-desc"] = "DESC";} else { $_SESSION["asc-desc"] = "ASC";}
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
      <a class="navbarBtn" href="javascript:void(0)" onclick="window.location.reload()">CONTESTANTS</a>
      <a class="navbarBtn" href="http://localhost/SCRSK/data/src/php/races.php">RACES</a>
      <?php navbarUser(); ?>
    </div>
    <div id="maincontent">
      <br>
      <section id="contestants" style="margin-top: 100px;">
        <h1><span>FORMULA DRIFT CONTESTANTS</span></h1>
        <hr>
        <table class="sortArea" align="center">
          <tr>
            <td width="20%">Sort by:</td>
            <td width="20%"><a class="sort" href="http://localhost/SCRSK/data/src/php/drivers.php?sortByFName">Name</a></td>
            <td width="20%"><a class="sort" href="http://localhost/SCRSK/data/src/php/drivers.php?sortByLName">Surname</a></td>
            <td width="20%"><a class="sort" href="http://localhost/SCRSK/data/src/php/drivers.php?sortByAge">Age</a></td>
            <td width="20%"><a class="sort" href="http://localhost/SCRSK/data/src/php/drivers.php?sortById">Join date</a></td>
          </tr>
        </table>
        <hr>
        <?php tableOfDrivers(); ?>
      </section>
      <hr>
    </div>
</p>
    <!--
    <script src="sketch.js"></script>
    -->

<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover({trigger: "hover"});   
});
</script>

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