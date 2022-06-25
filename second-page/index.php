<!DOCTYPE html>
<html>
  <body>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
		userId:<br>
		<input type="number" min="1" name="userId">
		<br>
		groupId:<br>
		<input type="number" min="1" name="groupuserId">
		<br>
		<input type="submit" name="save" value="submit">
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
$userId = $_POST['userId'];
$groupId=  $_POST['groupuserId'];

$sql = "SELECT userId, groupmessage FROM groupmessages WHERE groupuserId=$groupId ORDER BY groupmessageId DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0 ) {
  echo "<table><tr><th>Messages From Latest to Newest</th></tr>";
  // output data of each row
  while($row = $result->fetch_assoc()) {
    if ($row["userId"]==$userId){
    echo "<tr><td>".$row["groupmessage"];
  }}
  echo "</table>";
  echo "<tr><td> user verified";
} else {
  echo "0 results";
}
$conn->close();
	}
	?>
  </body>
</html>
