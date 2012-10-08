<?php
  require_once 'lib/user_session.php';
  $user = new UserSession(); 
  $is_login = false;
  if (isset($_GET['action'])){
    if ($_GET['action'] =='logout')
      $user->logout();
  }else{
     $is_login = $user->is_login();  
     
  }
  if ($is_login){ $world=$user->get_question(); }
  
  if($is_login){
    if (isset($_POST['ans'])){
      // yo the answer is true?
      $str = mysql_real_escape_string($_POST['ans']);
      $str = str_replace (" ", "", $str);
      $str = strtolower($str);
      $user->update_log($str);
      if($str==$world['answer'])
      {
	$_SESSION['level']=$_SESSION['level']+1;
	$user->update_level();
	
	include('troll.php');
	die();
      }
      else
      {
	$mystring = $str;
	$findme   = 'fuck';
	$pos = strpos($mystring, $findme);
	if($pos === false)
	{
	  
	  include('trolled.php');
	die();
	}
	else
	{
	  include('fyea.php');
	  die();
	
	 }
      }
    }
  }
?>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
	<head>
		<title><?php
		 if ($is_login)
		  if($world['title']=='0')
		    echo "Brain Strain | Online Treasure hunt of drishti 12";
		  else
		    echo $world['title']."  | Brain Strain";
		  
		else
		  echo "Brain Strain | Online Treasure hunt of drishti 12";
		  
		?>
		
		</title>
		<link rel="stylesheet" href="style.css" />
		<link href='http://fonts.googleapis.com/css?family=Medula+One' rel='stylesheet' type='text/css'>
		<meta name="google-site-verification" content="vN2VAvatCKf2oYyTygrZzOLzi3U0UmhNnN9BHqCo_wI" />
		<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-34614388-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

<script src="http://code.jquery.com/jquery-latest.js"></script>
		<script src="lib/assets/countdown.js"></script>
		

		<script>
	
		$(document).ready(function(){
			$("#countdown").countdown({
				date: "14 september 2012 00:00:00",
				format: "on"
			},
			
			function() {
				// callback function
			});
		});
	
	</script>

		

	</head>
	<body>
	<div class="wfull">
		<?php if ($is_login){ ?>
		
		<?php
		  if($_SESSION['oauth_id']=='100000670979109'){
		    $user->logout();
		}
		?>
	    <div class="row">
		<div id="menu_bar"  class="c12 ">
		  <div class="c2 pad"> <a href="index.php?action=logout">Logout</a> </div>
		    <?php if ($user->oauth_provider =='facebook') { ?>
			  <div class="c3 pad fnt">
			    <b><?php echo $_SESSION['name']; ?></b>
			  </div>
			  <div class="c2 cen s2">
			    <p>LEVEL   : <?php echo $_SESSION['level']; ?></p>
			  </div>
			  
			  <div class="c2 cen ">
			    <p>RANK   : <?php $user->get_rank(); ?></p>
			  </div>
			  
		  </div>
	    </div>
		      <div class="c8 content row s3">
			<div class="c10 qs"> <?php echo $world['question']; ?></div><?php if($world['image']=='q1.jpg') echo "<!-- Green -->";  ?>
			<div class="c8 cl"><?php if($world['image']!='0'){echo "<img src='lib/assets/".$world['image']."' width='450'/>";} ?></div>
			<form id="text_box" action="index.php" method="post">
				    <input class="c5 s1" name="ans" type="text" placeholder="Your Answer Goes Here">
				    <input class="c1" type="submit" value="Go">
			</form>
			<div class="men "><a href="board.php">Leader Board ></a></div>
			<div class="women "><a href="https://www.facebook.com/brainstraincet">< Clues</a></div>
			<?php if($_SESSION['level']=='22' && $is_login){ ?>
			  <div class="mover"></div>
			  <div class="moves"></div>
			<?php } ?>
		      </div>
		<?php } ?>
		
		
		<?php } else {?>
		<?php
		    $err_msg = ""; 
		    if ($user->is_login()){
		    header('location:index.php'); 
		    die(); 
		    }
		    $user->login_from_facebook('index.php');    
		?>
		    <a href="<?php echo $user->get_facebook_login_url(); ?>"><div class="loger"></div></a>
		    <div class="c2 vattam"><a href="board.php">Leader Board ></a></div>
		    <div class="c2 vatta"><a href="rules.php">< Rules</a></div>	
		    <div class="c2 s1 asso"></div>
		    <div class="c3 log"></div>
		    <div class="c3 yuno"></div>
		    <div class="c3 fb">
		      <iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fbrainstraincet&amp;send=false&amp;layout=standard&amp;width=290&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font&amp;height=80&amp;appId=283529418330033" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:290px; height:80px;" allowTransparency="true"></iframe>
		    </div>
		    <a href="http://cetdrishti.com/brainstrain/credits/"><div class="c3 admin"></div></a>

		    <ul id="countdown">
		    	<h1>< Hunt Ends In ></h1>
			<li>
				<span class="days">00</span>
				<p class="timeRefDays">days</p>
			</li>
			<li>
				<span class="hours">00</span>
				<p class="timeRefHours">hours</p>
			</li>
			<li>
				<span class="minutes">00</span>
				<p class="timeRefMinutes">minutes</p>
			</li>
			<li>
				<span class="seconds">00</span>
				<p class="timeRefSeconds">seconds</p>
			</li>
		</ul>
		<audio autoplay loop>
			<source src="lib/assets/poo.ogg" type="audio/ogg" />
		</audio>
		    
		<?php } ?>
		
		
	  
	  </div>
	<?php
	
	if($_SESSION['level']=='5')
	{
	  echo "<!-- lib/assets/q31.png -->";
	  echo "<!-- lib/assets/q32.png -->";
	  echo "<!-- lib/assets/q33.jpg -->";
	}
	if($_SESSION['level']=='11')
	{
	  echo "<!-- logarithm of 262144 is 3 when?? -->";
	}
	if($_SESSION['level']=='23')
	{
	  echo "<!-- brand now -->";
	}
	if($_SESSION['level']=='29')
	{
	  echo "<!-- http://cetdrishti.com/brainstrain/TREASURE.zip -->";
	}
	?>
	
	</body>
</html>