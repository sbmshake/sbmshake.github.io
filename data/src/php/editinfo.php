<!DOCTYPE html>
<html>
  <title> Formula DRIFT 2018</title>
  <head>
  <link rel="stylesheet" type="text/css" href="http://localhost/SCRSK/data/src/css/main-style.css">
  <link rel="stylesheet" type="text/css" href="http://localhost/SCRSK/data/src/css/navbar-style.css">
  <link rel="stylesheet" type="text/css" href="http://localhost/SCRSK/data/src/css/drivers_table-style.css">
  <link rel="stylesheet" type="text/css" href="http://localhost/SCRSK/data/src/css/login-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <meta name="viewport" width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0>
      <style> body {padding: 0; margin: 0;} </style>
  </head>
  <body>
  <?php

  //FUNCTIONS

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

  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  function navbarUser() {
    if($_SESSION["userType"] == "admin") {
      echo "<a class=\"navbarBtn\" href=\"javascript:void(0)\">START</a>";
    }
    else {
      echo "<a class=\"navbarBtn right\" href=\"http://localhost/SCRSK/index.php?delete\">DELETE</a>";
    }
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

  function userEdit() {
    echo '<form class="editform" method="post" enctype="multipart/form-data" action="http://localhost/SCRSK/data/src/php/editinfo.php?save">';
    echo '<div class="container">';
    echo '<label for="fName"><b>First Name</b></label>';
    echo '<input type="text" placeholder="Enter your first name" name="fName" value="'.$_SESSION["firstName"].'">';
    echo '<br />';
    echo '<label for="lName"><b>Last Name</b></label>';
    echo '<input type="text" placeholder="Enter your last name" name="lName" value="'.$_SESSION["lastName"].'">';
    echo '<br />';
    echo '<label for="age"><b>Birth year</b></label>';
    echo '<input type="number" placeholder="Enter your age" name="age" min="1900" max="2000"  value="'.$_SESSION["age"].'">';
    echo '<br />';
    echo '<label for="profilePic"><b>Profile picture</b></label>';
    echo '<br />';
    echo '<input type="file" name="profilePic">';
    echo '<br />';
    echo '<label for="carDesc"><b>Car</b></label>';
    echo '<br />';
    echo '<textarea style="width:100%" rows="3" name="carDesc" maxlength="255" placeholder="Make, model, engine, year, mods, anything you want up to 255 characters">'.$_SESSION["carDesc"].'</textarea>';
    echo '<br />';
    echo '<button class="login" type="submit">SAVE</button>';
    echo '</div>';
    echo '</form>';
  }
  function adminEdit() {
    echo '<form class="editform" method="post" enctype="multipart/form-data" action="http://localhost/SCRSK/data/src/php/editinfo.php?add">';
    echo '<div class="container">';
    for ($i = 1; $i <= 2; $i++) {
      echo '<label for="con'.$i.'"><b>Contestant '.$i.'</b></label><br />';
      echo '<select name="con'.$i.'">';
      $result = connectToDb('SELECT id, firstName, lastName FROM drivers ORDER BY firstName, lastName');
      while($row = $result->fetch_assoc()) {
        echo '<option value="'.$row["id"].'">'.$row["firstName"].' '.$row["lastName"].'</option>';
      }
      echo '</select>';
      echo '<br />';
    }
    echo '</select>';
    echo '<label for="date"><b>Date of the race</b></label><br />';
    echo '<input type="date" name="date"><br />';
    echo '<label for="time"><b>Time of the race</b></label><br>';
    echo '<input style="padding:5px; width:50px;" type="text" name="time" placeholder="00:00">';
    echo '<br />';
    echo '<button class="login" type="submit">ADD RACE</button>';
    echo '</div>';
    echo '</form>';
  }

// define variables and set to empty values
$firstName = $lastName = $carDesc = "";
$age = 0;
$profilePic = NULL;
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if(isset($_GET['save'])) {
    if (empty($_POST["fName"])) {
    } else {
      $firstName = test_input($_POST["fName"]);
    }
    if (empty($_POST["lName"])) {
    } else {
      $lastName = test_input($_POST["lName"]);
    }
    if (empty($_POST["age"])) {
    } else {
      $age = $_POST["age"];
    }
    if (getimagesize($_FILES["profilePic"]["tmp_name"] == false)) {
    } else {
      $profilePic = addslashes(file_get_contents($_FILES["profilePic"]["tmp_name"]));
    }
    if (empty($_POST["carDesc"])) {
    } else {
      $carDesc = test_input($_POST["carDesc"]);
    }
    $sql = 'UPDATE drivers SET firstName="'.$firstName.'", lastName="'.$lastName.'", age="'.(2018-$age).'", picture="'.$profilePic.'", car="'.$carDesc.'" WHERE email="'.$_SESSION["email"].'"';
    $result = connectToDb($sql);
  }
  else if(isset($_GET['signup'])) {
    if (empty($_POST["email"])) {
      $emailErr = "Email is required";
    } else {
      $_SESSION["email"] = test_input($_POST["email"]);
      // check if e-mail address is well-formed
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format"; 
      }
    }
  
    if (empty($_POST["psw"])) {
      $pswErr = "Password is required";
    } else {
      $_SESSION["password"] = test_input($_POST["psw"]);
      // check if name only contains letters and whitespace
      if (!preg_match("/^[a-zA-Z ]*$/",$_SESSION["password"])) {
        $nameErr = "Only letters and white space allowed"; 
      }
    }
      $sql = 'SELECT * FROM drivers WHERE email="'.$_SESSION["email"].'"';
      $result = connectToDb($sql);
      if ($result->num_rows == 0) {
        if($_POST["psw1"] == $_SESSION["password"]) {
          $sql = 'INSERT INTO drivers (email, password) VALUES ("'.$_SESSION["email"].'", "'.$_SESSION["password"].'")';
          connectToDb($sql);
        }
      }
  } else if(isset($_GET['add'])) {
    $result = connectToDb('INSERT INTO races (day, time, con_1, con_2) VALUES ("'.$_POST["date"].'", "'.$_POST["time"].'", '.$_POST["con1"].', '.$_POST["con2"].')');
  }
}
getUserData();
?>
    <div id="header">
    </div>
    <div id="navbar">
      <a class="navbarBtn" style="margin-left: 50px;" href="http://localhost/SCRSK/index.php">BACK</a>
      <a class="navbarBtn right" style="background-color: rgb(230, 0, 100);" href="javascript:void(0)">ACC: <?php echo strtoupper($_SESSION["firstName"]); ?></a>
      <?php
      navbarUser();
      ?>
    </div>
    <div id="maincontent">
      <br>
        <section id="contestants" style="margin-top: 100px;">
          <h1><span>EDIT YOUR INFO</span></h1>
          <div style="width:60%; margin:0% 20%">
          <?php
          if($_SESSION["userType"] == "admin") adminEdit();
          else userEdit();
          ?>
          </div>
        </section>
        <hr>
      </div>
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