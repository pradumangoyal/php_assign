<?php  
session_start();
if(isset($_SESSION["admin_name"]))
{
  header("location:home.php");
}
include('connect.php');
$sql = "Use first_year_db";
if ($conn->query($sql) === TRUE) {
}
if(isset($_POST["login"]))   
{
  if(!empty($_POST["member_name"]) && !empty($_POST["member_password"]))
  {
    $name = mysqli_real_escape_string($conn, $_POST["member_name"]);
    $namehash=crypt($name,'$5$rounds=5000$sociobook_2018$');
    $password = crypt(mysqli_real_escape_string($conn, $_POST["member_password"]),'$5$rounds=5000$sociobook_2018$');
    $sql = "Insert into sociobook_hashes values('".$namehash."','".$name."');";
    $result = mysqli_query($conn,$sql);
    $sql = "Select * from sociobook_passkeys where username = '" . $name . "' and passkey = '" . $password . "'";  
    $result = mysqli_query($conn,$sql);  
    $user = mysqli_fetch_array($result);  
    if($user)   
    {  
      $_SESSION["admin_name"] = $name;
      if(!empty($_POST["remember"]))   
      {  
        setcookie ("member_login",$namehash,time()+ (10 * 365 * 24 * 60 * 60));  
        setcookie ("member_password",$password,time()+ (10 * 365 * 24 * 60 * 60));
        $_SESSION["admin_name"] = $name;
      }  
      else  
      {  
        if(isset($_COOKIE["member_login"]))   
        {  
          setcookie ("member_login","");  
        }  
        if(isset($_COOKIE["member_password"]))   
        {  
          setcookie ("member_password","");  
        }  
      }  
      header("location:home.php"); 
    }  
    else  
    {
      $message = "Invalid Login";  
    }
  }
  else
  {
    $message = "Both are Required Fields";
  }
}
?>
<html>
<head>
<title>SOCIOBOOK</title>  
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<style>  
body  
{  
margin:0;  
padding:0;  
        background-color:#f1f1f1;  
}  
.box  
{  
width:700px;  
padding:20px;  
        background-color:#fff;  
}
h2{
  font-family: "Comic Sans MS";
}
</style>  
</head>  
<body>  
<div class="container box">  
<form action="" method="post" id="frmLogin"> 
<h3 align="center">Login Details</h3><br />
<div><?php if(isset($message)) { echo $message; } ?></div>  
<div>  
<label for="login">Username</label>  
<input name="member_name" type="text" value="<?php if(isset($_COOKIE["member_login"])) { 
$sql = "SELECT name FROM sociobook_hashes where hash='".$_COOKIE["member_login"]."'";
$result = $conn->query($sql);
if($row = $result->fetch_assoc()) { }
 echo $row['name'];} ?>" />  
</div>  
<div>  
<label for="password">Password</label>  
<input name="member_password" type="password" value="<?php if(isset($_COOKIE["member_password"])) { echo $_COOKIE["member_password"]; } ?>" />   
</div>  
<div>  
<input type="checkbox" name="remember" <?php if(isset($_COOKIE["member_login"])) { ?> checked <?php } ?> id="rem"/>  
<label for="rem">Remember me</label>  
</div>  
<div>  
<div><input type="submit" name="login" value="Login" ></span></div>  
</div>   
</form>  
<br />  
<h2>Don't Have an account? <a href="signup.php">Create</a> one here because its free</h2>
</div>  
</body>  
</html>
