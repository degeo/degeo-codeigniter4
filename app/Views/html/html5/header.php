<?php
isset( $document ) || $document = new stdObject();
isset( $metatags ) || $metatags = [];
isset( $layout ) || $layout     = new stdObject();
?>
<!doctype html>
<html>
	<head>
		<title><?php echo $document->title(); ?></title>
		<?php echo view( 'html/metatags', [ 'metatags' => $metatags ] ); ?>
	</head>
	<body class="<?php #echo $layout->body_classes(); ?>">