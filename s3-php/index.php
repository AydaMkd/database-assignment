<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PHP File Upload to AWS S3 Bucket</title>
    <style> 
     input {
     width: 100%;
     }
</style>
</head>
<body>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data">
    
        <h2>PHP Upload File</h2>
        <label for="userId">Enter your User Id</label>
    <input type="number" min="1" name="userId" id="userId">
    <label for="groupId">Enter your group Id</label>
    <input type="number" min="1" name="groupId" id="groupId">
        <label for="file_name">Filename:</label>
        <input type="file" name="anyfile" id="anyfile">
        <input type="submit" name="submit" value="Upload">
    </form>
   
    <?php
  
   use Aws\S3\S3Client;
   require 'vendor/autoload.php';
     
   if($_SERVER["REQUEST_METHOD"] == "POST"){
    $servername = "aca-db-1.chyesmv0fupq.us-east-2.rds.amazonaws.com";
$username = "admin";
$password = "password";
$database= "project1";
// Create connection
$conn = new mysqli($servername, $username, $password, $database);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
$userId=$_POST["userId"];
$groupId=$_POST["groupId"];
echo $groupId;
   // Instantiate an Amazon S3 client.
   $s3Client = new S3Client([
       'version' => 'latest',
       'region'  => '',
       'credentials' => [
        'key'=>'',
        'secret'=>''
       ]
   
   ]);
   // Check if the form was submitted
   if($_SERVER["REQUEST_METHOD"] == "POST"){
       // Check if file was uploaded without errors
       if(isset($_FILES["anyfile"]) && $_FILES["anyfile"]["error"] == 0){
        //    $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
           $filename = $_FILES["anyfile"]["name"];
           $filetype = $_FILES["anyfile"]["type"];
           $filesize = $_FILES["anyfile"]["size"];
       
           // Validate file extension
           $ext = pathinfo($filename, PATHINFO_EXTENSION);
           if(!$ext) die("Error: Please select a valid file format.");
       
           // Validate file size - 10MB maximum
           $maxsize = 10 * 1024 * 1024;
           if($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");
             
        
           // Validate type of the file
        //    if(in_array($filetype, $allowed)){
               // Check whether file exists before uploading it
               
               if(file_exists("upload/" . $filename)){
                   echo $filename . " is already exists.";
               } else{
                   if(move_uploaded_file($_FILES["anyfile"]["tmp_name"], "files/" .$userId."/" . $filename)){
                       $bucket = '';
                       $file_Path = "files/" .$userId. "/" . $filename;
                       $key = "group-file/" .$groupId. "/" .$filename;
                       
                       try {
                           $result = $s3Client->putObject([
                               'Bucket' => $bucket,
                               'Key'    => $key,
                               'Body'   => fopen($file_Path, 'r'),
                               'ACL'    => 'public-read', // make file 'public'
                           ]);
                           $text="File uploaded successfully by user with the Id number:" .$userId. "download it at:" .$result->get('ObjectURL');
       echo $text;
       $sql = "INSERT INTO groupmessages (groupmessage, userId, groupuserId) VALUES ('$text', $userId, $groupId)";
    
   
       $rs = mysqli_query($conn, $sql);
    
    if($rs)
    {
      echo "<br>message Records Inserted";
    }else {
        echo "Error: " . $sql . "
    " . mysqli_error($conn);
     }
                           echo "<br>File uploaded successfully by user with the Id number:" .$userId. "<br>download it at:<br>" .$result->get('ObjectURL');
                       } catch (Aws\S3\Exception\S3Exception $e) {
                           echo "There was an error uploading the file.\n";
                           echo $e->getMessage();
                       }
                    //    echo "Your file was uploaded successfully.";
                   }else{
                      echo "File is not uploaded";
                   }
                   
               } 
           } else{
               echo "Error: There was a problem uploading your file. Please try again."; 
           }
       } else{
           echo "Error: " . $_FILES["anyfile"]["error"];
       }
       


   
   }
   ?>
     
   
</body>
</html>