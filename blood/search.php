<?php

session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Search</title>
   
 
  <style>
.circleaor {
  width: 65px;
height: 65px;
  border-radius: 50%;
  display: inline-block;
  margin: 21px 0 0 0;
}

.abo{
    width: 50%;
   
    margin: 10px auto;
}
.abo a{
  display: flex;
  color: black;
  text-decoration: none;

}
.name{
  margin: 13px 0 0px 35px;
    font-size: 30px;
    font-family: serif;
    text-transform: capitalize;

}
</style>
</head>
<body>



<?php
// Database connection parameters
$name="";
$row4="";
include 'config.php';
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($connection, $_GET['search']);
    $query = "SELECT * FROM user_profiles WHERE username LIKE '%$search%'";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
       
      $name = $row["username"];
  $uril = $row["uri"];

 $aad=$row['profile_img'];
 if($row['profile_img']){
  echo "<div  class='abo'  >
    
    
  <a class='nav-link active ' ' href='profile.php.?uri=$uril'>
  
  <img class='circleaor' src='profile img/$aad' alt='' > <h1 class='name'> $name</h1></a>
    
      
              
            </div>";

}else{
  echo "<div  class='abo'  >
    
    
  <a class='nav-link active ' ' href='profile.php.?uri=$uril'>
  
  <img class='circleaor' src='Untitled.jpg ' alt='img/Untitled.jpg' > <h1 class='name'> $name</h1></a>
    
      
              
            </div>";
}

        }
    } else {
        echo "<p>No results found.</p>";
    }

    mysqli_free_result($result);
}

mysqli_close($conn);
?>

</body>
</html>
