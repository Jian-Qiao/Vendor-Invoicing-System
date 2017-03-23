<?php
        session_start();
        session_unset();
?>

<html>
	<head> 
		<title>Almod Vendor Control</title>
                <style type:"text/css">
                body{
                         margin:100px;
                }
                </style>
	</head>
	<body>
		<span align="center">
		<h1>Almod Vendor Control System</h1>
		<p><img src="Almod.png" align="center"></p>
		<p>Welcome to Almod Vendor System!</P>
                <form action="Index.php" method="post">
		<p>Username:	<input type="text" placeholder="your username" name="User"></p>
		<p>Password :	<input type="password" placeholder="your password" name="Password"></p>
		<p><input type="submit" name="submit" value="Log in">
		</form>
<?php
 if (array_key_exists("submit", $_POST)) {

       $link=mysqli_connect("localhost","root","","web222-memodb");

       if (mysqli_connect_error()){
            die("There was an error connecting to the database");
       }

       $query="SELECT Password FROM vendor_list WHERE Vendor_Name = '" . $_POST["User"]."'";

       if ($result = mysqli_query($link,$query)) {
              $row=mysqli_fetch_array($result);

              if ($row[0] == $_POST["Password"] ) {
                        $_SESSION["User"]=$_POST["User"];
                        $_SESSION["Password"]=$_POST["Password"];
                        $Query="SELECT id, Priv, Address, Labor_Sheet, Stone_Sheet, SSS_Sheet, SSC_Sheet, CSS_Sheet FROM vendor_list WHERE Vendor_Name = '" . $_SESSION["User"]."'";
                        $Result=mysqli_query($link,$Query);
                                while ($row=mysqli_fetch_array($Result)) {
                                        $_SESSION["id"]= $row["id"];
                                        $_SESSION["Priv"]= $row["Priv"];
                                        $_SESSION["Address"]= $row["Address"];
                                        $_SESSION["Labor"]= $row["Labor_Sheet"];
                                        $_SESSION["Stone"]= $row["Stone_Sheet"];
                                        $_SESSION["SSS"]= $row["SSS_Sheet"];
										$_SESSION["SSC"]= $row["SSC_Sheet"];
                                        $_SESSION["CSS"]= $row["CSS_Sheet"];
                                        }
                        echo "document.getElementById('error').style.display='none'";
                        if ( $_SESSION["Priv"]==1){
                                header("Location:Admin_Home.php");
                        }else{
                                header("Location:Home.php");
                        }
                  }else{
                        echo "<p id='error'>Username/Password do not match!</p>";
                 }
                               
       }else{
                  echo "document.getElementById('error').innerHTML='User does not exist!'";
       }
}
?>
		<hr>
		<p>If you forget your Username/Password (Or you are not on the list), simply call Almod. Don't Click that buttom. It won't work!!!</p>
		</span>
	</body>
</html>
