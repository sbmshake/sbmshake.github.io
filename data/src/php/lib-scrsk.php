<?php
echo "hello";
function connect() {
  $servername = "localhost";
  $username = "sliwinskimatthew";
  $password = "147896325";
  
  // Create connection
  $conn = new mysqli($servername, $username, $password);
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
} 

function draw2() {
  echo "hello";
}

?>