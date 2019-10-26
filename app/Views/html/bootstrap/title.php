<div class="row">

	<div class="col-12">

		<?php if(! empty( $document->title() )): ?>
		<h1 class="title"><?php echo $document->title(); ?></h1>
		<?php endif; ?>
		<?php if(! empty( $document->subtitle() )): ?>
		<h2 class="subtitle"><?php echo $document->subtitle(); ?></h1>
		<?php endif; ?>

	</div>

</div>