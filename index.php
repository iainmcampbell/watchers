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
	<link rel="stylesheet" href="stylesheets/base.css">
	<link rel="stylesheet" href="stylesheets/skeleton.css">
	<link rel="stylesheet" href="stylesheets/layout.css">

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
<body>

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

			if(glob($root_path.$a.'/*.gif', GLOB_BRACE))
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

	function echo_empty() {
		
	}



?>



<div class="container">
	
	
	<div id="watchers">

		<!-- header -->
		<div class="header row">
			<a target="_BLANK" href="http://www.adamzabunyan.ca" title="Concept and Graphics: Adam Zabunyan">
				<img class="imgcastle" src="images/scribe2.png">
			</a>
			<a target="_BLANK" href="http://www.iainmc.ca" title="Development: Iain M Campbell">
				<img class="imgcastle" src="images/i.png">
			</a>
			<a target="_BLANK" href="http://www.cbaldesarra.com/" title="Concept and Graphics: Chris Baldesarra">
				<img class="imgcastle" src="images/cb_avatar.png">
			</a>
		</div>

		<!-- gifs -->
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

					echo
						'<a class="'.$item.'" href="'.$links[$item].'" target="_blank">'.
							'<div class="imgcastle '.$item.'">';
								print_r($statics[$item]);
								foreach($statics[$item] as $img) {
									echo $img;
									echo '<img src="'.$img.'" class="hide">';
								}
								// '<img class="frame" src="'.'">'.
							echo '</div>'.
						'</a>';

				} else {

					echo
						'<a href="" class="empty">'.
							'<div class="imgcastle">'.
							'BLANK';
							echo '</div>'.
						'</a>';

				}
			}


			echo '</div>';


		?>

	</div> <!-- /#watchers -->

	<div id="tweets">
		

	</div>

</div>

	

	<!-- container -->


<!-- End Document
================================================== -->



<script src="js/jquery-1.10.1.min.js"></script>
<script src="js/builder.js"></script>

</body>
</html>