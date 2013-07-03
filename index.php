<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>

	<!-- Basic Page Needs
  ================================================== -->
	<meta charset="utf-8">
	<title>Watchers</title>
	<meta name="description" content="">
	<meta name="author" content="">

	<!-- Mobile Specific Metas
  ================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- CSS
  ================================================== -->
	<link rel="stylesheet" href="css/base.css">
	<link rel="stylesheet" href="css/skeleton.css">
	<link rel="stylesheet" href="css/layout.css">

	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- Favicons
	================================================== -->
	<link rel="shortcut icon" href="images/favicon.ico">
	<link rel="apple-touch-icon" href="images/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">

</head>
<body class="noOverflow">

<!-- DATA REPORT -->

<?php

	// set up content arrays
	$statics = NULL;  // PNGs
	$gifs = NULL;		  //
	$links = NULL;	  // URLs
	$dates = NULL;
	$titles = NULL;
	$subheads = NULL;
	$completes = NULL; // true if tweet has all necessary pieces

	$root_path = ('./content/');

	$root = scandir($root_path);

	// fill content arrays
	foreach ($root as $a) {
		if(preg_match('/article(..|.)/', $a)) {

			$missing = '';
				
			if(glob($root_path.$a.'/*.{png}', GLOB_BRACE))
				$statics[$a] = glob($root_path.$a.'/*.{png}', GLOB_BRACE);
			else $missing .= 'PNGs ';

			if(glob($root_path.$a.'/*.{gif}', GLOB_BRACE))
				$gifs[$a] = glob($root_path.$a.'/*.gif', GLOB_BRACE);
			else $missing .= 'GIF ';

			if(file_get_contents($root_path.$a.'/data.txt')) {
				$file = file_get_contents($root_path.$a.'/data.txt');	
				$pieces = explode(PHP_EOL, $file);
				$links[$a]    = $pieces[0];
				$dates[$a]    = date('F j, Y', strtotime($pieces[1]));
				$titles[$a]   = $pieces[2];
				$subheads[$a] = $pieces[3];

			} else $missing .= 'textfile ';

			// Data Report
			if($statics[$a] && $gifs[$a] && $links[$a]) {
				$completes[$a] = true;
				echo '<!-- '.$a.' complete -->';
			} else {
				echo '<!-- '.$a.' missing: '.$missing.' -->'; // make this into a nice debugging message
			}
		}
	}

	// process position data file into array
	if(file_get_contents($root_path.'/blocks.txt')) {
		$file = file_get_contents($root_path.'/blocks.txt');

		$rows = explode(PHP_EOL, $file);
		// $rows = array_reverse($rows); // DOM order

		foreach($rows as $row) {
			$temp = explode(' ', $row);
			foreach($temp as $t) {
				$order[] = $t;
			}
		}
	}

	// get about text
	if(file_get_contents($root_path.'/about.txt')) {
		$about = file_get_contents($root_path.'/about.txt');
	}

?>

<div id="container">

	<div id="tweets">

		<?php 

			foreach($order as $o) {
				$item = 'article'.$o;

				if($completes[$item]){

					echo '<a target="_BLANK" href="'.$links[$item].'">';
					echo '<div class="tweet '.$item.'">';
					echo '<h2>'.$titles[$item].'</h2>';
					echo '<h4>'.$dates[$item].'</h4>';
					echo '<h3>'.$subheads[$item].'</h3>';
					echo '</div></a>';

				}
			}

		?>

	</div> <!-- /#tweets -->

	<div id="watchers">
		<?php 

			echo '<div class="parts row">';

			$column = 0;


			foreach($order as $o) {
				$item = 'article'.$o;
							
				// columnization (overrides completeness)
				$column++;

				if($column>3) { // new row
					$column = 1;
					echo '</div><div class="parts row">';
				}

				if($completes[$item]) {

					echo '<div class="block" id="'.$item.'">';
						// foreach($statics[$item] as $img) echo '<img class="png" src="'.$img.'">';
						echo '<img class="png" src="'.$statics[$item][0].'">';
						echo '<img class="gif" src="'.$gifs[$item][0].'">';
						echo '<div class="border"></div>';
					echo '</div>';

				} else {

					echo
						'<div class="block blank">'.
						'BLANK';
						echo '</div>';

				}
			}


			echo '</div>';


		?>

	</div> <!-- /#watchers -->

	<div id="cloud"><div class="image"></div></div>

	<div id="about">
		<div class="hr"></div>
		<p> <?= $about ?> </p>
	</div> <!-- /#about -->


	<div id="sigils">
		<a target="_BLANK" href="http://www.adamzabunyan.ca" title="Concept and Graphics: Adam Zabunyan">
			<div class="sigil az"></div>
		</a>
		<a target="_BLANK" href="http://www.iainmc.ca" title="Development: Iain M Campbell">
			<div class="sigil ic"></div>
		</a>
		<a target="_BLANK" href="http://www.cbaldesarra.com/" title="Concept and Graphics: Chris Baldesarra">
			<div class="sigil cb"></div>
		</a>
	</div>

	<div id="info">
		<div class="info-button">info</div>
	</div>

</div>

	<!-- container -->


<!-- End Document
================================================== -->



<script src="js/lib/jquery-1.10.1.min.js"></script>
<script src="js/framerate.js"></script>
<script src="js/watchers.js"></script>

</body>
</html>