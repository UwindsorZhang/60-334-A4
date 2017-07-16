<?php // sectiona.php

  require_once 'login.php';
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die($conn->connect_error);


  if (isset($_POST['fname'])   &&
      isset($_POST['lname'])    &&
      isset($_POST['usertype']) &&
      isset($_POST['email'])     &&
      isset($_POST['password']))
  {
    $fname   = get_post($conn, 'fname');
    $lname    = get_post($conn, 'lname');
    $usertype = get_post($conn, 'usertype');

    $query  = "SELECT * FROM user_codes";
    $result = $conn->query($query);
    if (!$result) die ("Database access failed: " . $conn->error);
    $rows = $result->num_rows;
    for ($j = 0 ; $j < $rows ; ++$j)
    {
    $result->data_seek($j);
    $row = $result->fetch_array(MYSQLI_NUM);
      if($row[1] == $usertype){
         $usertype = $row[0];

      } 
    }

    date_default_timezone_set('Canada/Windsor');
    $time = date("Y-m-d  H:i:s",time());
    $email     = get_post($conn, 'email');
    $password     = get_post($conn, 'password');
    
    $stmt= $conn->prepare('INSERT INTO user_profiles VALUES (?,?,?,?,?,?)');
    $stmt->bind_param("ssssss",$fname,$lname,$usertype,$time,$email,$password); 
    if($stmt->execute()){

    echo "successfully add data to database: ".$fname. " " . $lname. " " .$usertype. " " .$time. " " .$email. " " .$password;

    }

  }
  echo <<<_END
  <form action="sectiona.php" method="post"><pre>
  First Name<input type="text" name="fname">
  Last Name<input type="text" name="lname"></pre>
_END;

  $query  = "SELECT * FROM user_codes";
  $result = $conn->query($query);
  if (!$result) die ("Database access failed: " . $conn->error);

  $rows = $result->num_rows;
  
  echo <<<_END
  <pre>
  User Type <select name="usertype">
_END;

  for ($j = 0 ; $j < $rows ; ++$j)
  {
    $result->data_seek($j);
    $row = $result->fetch_array(MYSQLI_NUM);

  echo <<<_END
  <option>$row[1]</option>
_END;
  }
  echo <<<_END
	</select></pre>
_END;

  echo <<<_END
  <pre>
  E-Mail<input type="email" name="email">
  Password<input type="password" name="password">
  <input type="submit" value="Submit">
  </pre></form>
_END;



  $result->close();
  $conn->close();
  
  function get_post($conn, $var)
  {
    return $conn->real_escape_string($_POST[$var]);
  }
?>