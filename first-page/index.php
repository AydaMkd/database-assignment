
<!DOCTYPE html>
<html>
<head>
<style> 
input {
  width: 100%;
}
</style>
</head>
  <body>
	
	<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<label for="userId">Enter your User Id</label>
    <input type="number" min="1" name="userId" id="userId">
    <label for="groupId">Enter your group Id</label>
    <input type="number" min="1" name="groupuserId" id="groupId">
        <label for="groupmessage">Message To Submit</label>
        <input type="text" name="groupmessage" id="groupmessage">
		<input type="submit" name="save" value="Submit">
	</form>
	<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $servername = "";
    $username = "";
    $password = "";
    $database= "";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    echo "Connected successfully";
    $message = $_POST['groupmessage'];
    $groupId=  $_POST['groupuserId'];
    $userId=  $_POST['userId'];
    date_default_timezone_set("America/Fortaleza");
    
    
    if ($_POST['groupmessage'] && $_POST['groupuserId']){
    $sql = "INSERT INTO groupmessages (groupmessage,userId,groupuserId) VALUES ('$message', $userId, $groupId)";
    
    // insert in database 
    $rs = mysqli_query($conn, $sql);
    }
    if($rs)
    {
      echo "<br>message Records Inserted";
    }else {
        echo "Error: " . $sql . "
    " . mysqli_error($conn);
     }
  }
    ?>
  </body>
</html>



