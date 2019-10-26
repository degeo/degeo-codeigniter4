<?php
isset( $document ) || $document = new stdObject();
isset( $metatags ) || $metatags = [];
isset( $layout ) || $layout     = new stdObject();
?>
<!doctype html>
<html>
	<head>
		<title><?php echo $document->title(); ?></title>
		<?php $metatags->render(); ?>
		<?php $resources->render(); ?>
	</head>
	<body class="<?php #echo $layout->body_classes(); - @TODO ?>">