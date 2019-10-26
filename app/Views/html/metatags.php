<?php
isset( $metatags ) || $metatags = [];
foreach($metatags as $metatag):
	echo $metatag . "\r\n";
endforeach;
