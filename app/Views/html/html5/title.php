<?php if(! empty( $title )): ?>
<h1 class="title">
	<a class="title-link" href="<?php echo site_url(); ?>" title="<?php echo $title; ?>">
		<?php echo $title; ?>
	</a>
</h1>
<?php else: ?>
<!-- No Title -->
<?php endif; ?>
<?php if(! empty( $subtitle )): ?>
<h2 class="subtitle"><?php echo $subtitle; ?></h1>
<?php else: ?>
<!-- No Sub Title -->
<?php endif; ?>