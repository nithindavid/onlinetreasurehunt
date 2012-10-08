 <!DOCTYPE html>

<html>
	<head>
		<title>Brain Strain</title>
		<link rel="stylesheet" href="style.css" />
		<link href='http://fonts.googleapis.com/css?family=Medula+One' rel='stylesheet' type='text/css'>
	</head>
<body>
<div class="row">
		<div id="menu_bar"  class="c12 ">
		  <div class="c1 s12 end pad"> <a href="index.php?action=logout">Logout</a> </div>
		    <?php if ($user->oauth_provider =='facebook') { ?>
			  <div class="c2 s6 end pad">
			    <?php echo $_SESSION['name']; ?>
			  </div>
			  <div class="c2">
			    <p>Level   : <?php echo $_SESSION['level']; ?></p>
			  </div>
                    <?php } ?>
		  </div>
	    </div>
<?php
  $err_msg = ""; 
  if (!$user->is_login()){
    header('location:login.php'); 
    die(); 
  }
  
  $ran=rand(10,18);
     
?>
<div class="c5 s4 oops"><img src='lib/assets/image<?php echo $ran ?>.gif' /></div>
<div class="row pad "><div class="c4 s6 oops"><a href="index.php">try again</a></div></div>

</body>
</html>
