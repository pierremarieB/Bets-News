<!DOCTYPE html>
<html lang="fr">
<head>
	<base href="/FootballTeamTracker/" />
	<title><?php echo $parts["title"]; ?></title>
	<meta charset="UTF-8" />
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  	<link rel="stylesheet" href="skin/style.css" />
</head>
<body>
	<header>
		<nav class="navbar navbar-inverse">
  			<div class="container-fluid">
  				<?php 
		    		$query = $_SERVER['QUERY_STRING'];
		  			$reg = "/team=(\w+-?\w+)/";
					if(!preg_match($reg, $query, $res)) {
						$res = array(
							"",
							"paris");
					}
			    ?>
			    <div class="navbar-header">
			      	<a class="navbar-brand" href=<?php echo ucfirst($res[1]); ?>>Football Team Tracker</a>
			    </div>
			    <ul class="nav navbar-nav">
			    	<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><img src=<?php echo "images/teams/".strtolower($res[1]).".png"; ?> alt=<?php echo "images/teams/".$res[1].".png"; ?> class="logoMenu"> &nbsp; <?php //echo ucfirst($res[1]); ?><span class="caret"></span></a>
			        	<ul class="dropdown-menu">
			        		<?php
			        			$dirname = "images/teams/";
        						$images = glob($dirname."*.{png}", GLOB_BRACE);

        						foreach ($images as $image) {
            						echo '<li><a href="'.$this->router->getHomeURL(explode('.',basename($image))[0]).'"><img src="'.$image.'" alt="'.explode('.',basename($image))[0].'" class="logoMenu"> &nbsp; '.ucfirst(explode('.',basename($image))[0]).'</a></li>';
        						}
			        		?>
			        	</ul>
			      	</li>
			      	<li class="active"><a href="<?php echo ucfirst($res[1]); ?>">Home</a></li>
			    </ul>
			  </div>
		</nav>
	</header>
	<main>
		<h1 id='title'><?php echo $parts["title"]; ?></h1>
		<div id='content' class='container-fluid'>
			<?php echo $parts["content"]; ?>
		</div>
	</main>
</body>
</html>

