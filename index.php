<?php
// Pass in an opperation variable to determine what we are doing

//Variable: op [fetch, create, update, delete]

//Example:
//   http://example.org/lab9.php?op=fetch


//set DB connection info below:
$host="104.197.193.136"; #The host address of the DB 
$dbuser="root"; #The DB username
$dbpass="1839bdr"; #The DB password
$db="ndettmer"; #The DataBase to use
$table="tbl_users"; #name of table to use
//NOTE THAT THE HOST PROVIDED IN THE DEMO ABOVE IS NOT OPEN TO ALL IPS	
 
// Create the DB Connection and put in mysqli variable ...
$mysqli = new mysqli($host, $dbuser, $dbpass, $db);

//echo ("DB connected!"); #DEBUG

// Check for connection error
if ($mysqli->connect_errno) {
	// The connection failed. Tell the user what happened
	echo "Sorry, this website is experiencing problems.";
	// Give details about the failure (ONLY FOR NON-PUBLIC FACING SITES)
	echo "Error: Failed to make a MySQL connection, here is why: \n";
	echo "Errno: " . $mysqli->connect_errno . "\n";
	echo "Error: " . $mysqli->connect_error . "\n";
	exit;
}


//$mysqli->query("DROP DATABASE $db");
if (!$mysqli->select_db($db))
{
  $sql = "CREATE DATABASE $db";
  //$sql = "SHOW DATABASES";

  if ($mysqli->query($sql) === TRUE) {
	echo "Database created successfully";
  } else {
	echo "Error creating database: " . $mysqli->error;
	exit;
  }
}
else {
	//echo("DB Exists!"); #DEBUG
}

$mysqli->close();


$mysqli = new mysqli($host, $dbuser, $dbpass, $db);

if ( !$mysqli->query("SHOW TABLES LIKE '".$table."'")->num_rows ==1 ) 
{
    $sql = "CREATE TABLE `tbl_users` (`iduser` int(11) NOT NULL AUTO_INCREMENT, `firstname` varchar(150) DEFAULT NULL, `lastname` varchar(150) DEFAULT NULL, `age` int(11) DEFAULT NULL, `email` varchar(250) DEFAULT NULL, `zip` varchar(25) DEFAULT NULL, UNIQUE KEY `iduser_UNIQUE` (`iduser`))";
    //echo($sql."<br/>"); #DEBUG
    $mysqli->query($sql);
    echo $mysqli->error; 
}
else {
	//echo("Table Exists!"); #DEBUG
}

$method = $_SERVER['REQUEST_METHOD'];
if (isset($_SERVER['PATH_INFO'])) {
  $request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
    echo("Request array: ".json_encode($request)); #debug
  switch ($method) {
    case 'GET':
      get($request, $mysqli, $table); 
        break;
    case 'PUT':
        if(count($request) == 6){
            put($request, $mysqli, $table); 
        }
        else {
            echo("The Put request requires providing Username, FirstName, LastName, Age, Email and Zipcode");
            exit;
        }
        break;
    case 'POST':
      if(count($request) == 6){
            post($request, $mysqli, $table); 
        }
        else {
            echo("The POST request requires providing Username, FirstName, LastName, Age, Email and Zipcode");
            exit;
        } 
        break;
    case 'DELETE':
      deleter($request, $mysqli, $table); 
        break;
  }
}
else {
  echo "Nothing here, use http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].'/username/ to use the API';
}


function get($request, $mysqli, $table) {
  $username=$request[0];
  if ($username=='allusers') {
    $sql="SELECT * from $table";
    //echo($sql); #DEBUG
    $result = $mysqli->query($sql); echo $mysqli->error;
    $rows = array();
    while($r = $result->fetch_array(MYSQLI_ASSOC)) {
          $rows[] = $r;
    }
    echo json_encode($rows);
  }
  else {
    $sql="SELECT * from ".$table." where username = '$username'";
    
    //echo($sql); #DEBUG
    $result = $mysqli->query($sql); echo $mysqli->error;
    if ($result->num_rows > 0) {
            $rows = array();
            while($r = $result->fetch_array(MYSQLI_ASSOC)) {
                    $rows[] = $r;
            }
            echo json_encode($rows);
    }
    else {
            echo("Contact with provided username not found!");
            exit;

    }
  }
}

function put($request, $mysqli, $table) {
  if (isset($request[0])) {
    $username=$request[0];
    $fname=$request[1];
    $lname=$request[2];
    $age=$request[3];
    $email=$request[4];
    $zip=$request[5];
    $sql="SELECT idcontact from ".$table." where username='$username'";
    //echo($sql); #DEBUG
    $result = $mysqli->query($sql); echo $mysqli->error;
    if ($result->num_rows > 0) {
        $sql = "Update ".$table." Set username = '$username'";
        if ($fname != "x"){ 
        $sql .= ", firstname = '$fname'";
        }
        if ($lname != "x"){ 
        $sql .= ", lastname = '$lname'";
        }
        if ($age != "x"){ 
        $sql .= ", age = '$age'";
        }
        if ($email != "x"){ 
        $sql .= ", email = '$email'";
        }
        if ($zip != "x"){ 
        $sql .= ", zip = '$zip'";
        }
        $sql .= " WHERE username = '$username'";

        echo("Update query: ".$sql."<br/>"); #DEBUG
        if ($mysqli->query($sql) === TRUE) {
                echo json_encode(array("UserName"=>$username,"action" => "updated"));
        }
        else { 
         echo $mysqli->error;
         exit;
        }
    }
    else { 
            echo "Contact not found!";
            exit;
        }
    }
    
}



function post($request, $mysqli, $table) {
    
    $username=$request[0];
    $fname=$request[1];
    $lname=$request[2];
    $age=$request[3];
    $email=$request[4];
    $zip=$request[5];
    
    $sql="SELECT idcontact from ".$table." where username='$username'";
    $result = $mysqli->query($sql); echo $mysqli->error;
    if ($result->num_rows > 0) {
            echo "Contact already exists!";
            exit;
    }
    else {
        $sql = "INSERT into ".$table." (username, firstname, lastname, age, email, zip) VALUES ('$username', '$fname', '$lname', '$age', '$email', '$zip')";
        //echo($sql); #DEBUG
        if ($mysqli->query($sql) === TRUE) {
                echo json_encode(array("UserName"=>$username, "action" => "created"));
        }
        else { 
        echo $mysqli->error; 
        exit;
        }
    }
  
}



function deleter($request, $mysqli, $table) {
  if (isset($request[0])) {
    $username=$request[0];  
  
    $sql="SELECT idcontact from $table where username='$username'";
    //echo($sql."<br/>"); #DEBUG
    $result = $mysqli->query($sql); echo $mysqli->error;
    if ($result->num_rows > 0) {
          $sql = "DELETE from $table where email='$email'";
          //echo($sql."<br/>"); #DEBUG
          $mysqli->query($sql); echo $mysqli->error;
          echo json_encode(array("Contact with email"=>$email, "action"=>"deleted"));
    }
    else {
          echo "Contact doesn't exist!";
          exit;
    }
  }
  else
  {
      echo "Username is required for the DELETE command!";
      exit;
  }
}


?>