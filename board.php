 <?php require_once 'lib/user_session.php'; 
  $user = new UserSession(); ?>
<!DOCTYPE html>

<html>
<head>
    <title>Leader Board | Brain Strain</title>
    <link rel="stylesheet" href="style.css" />
    <link href='http://fonts.googleapis.com/css?family=Medula+One' rel='stylesheet' type='text/css'>
</head>

<body>
<div class="w960 s2">
     <table class="row">
		<thead>
			<tr class="c12">
				<th scope="col" class="c2">Rank</th>
                                <th scope="col" class="c2 s2">Username</th>
                                <th scope="col" class="c2 s4">Level</th>
				
			</tr>
		</thead>
                
		<tfoot>
	        <tr>
	              <td></td>
	        </tr>
		</tfoot>
	
	<!-- Table body -->
	
		<tbody class="c12">
                    <?php $user->get_board(); ?>
 
		</tbody>

</table>
    <a class="c2 s4 gio" href="index.php">< HOME</a>
</div>
</body>
</html>
