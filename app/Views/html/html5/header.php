<?php
isset( $document ) || die( 'Missing Document Variable in ' . __FILE__ );
isset( $metatags ) || $metatags = [];
isset( $layout ) || die( 'Missing Layout Variable in ' . __FILE__ );
?>
<!doctype html>
<html>
	<head>
		<title><?php echo $document->title(); ?></title>
		<?php $metatags->render(); ?>
		<?php $resources->render(); ?>
	</head>
	<body class="<?php #echo $layout->body_classes(); - @TODO ?>">