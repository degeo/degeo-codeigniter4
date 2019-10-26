<?php
isset( $metatags ) || $metatags = [];
foreach($metatags as $metatag):
	echo $metatag['metatag'] . "\r\n";
endforeach;
