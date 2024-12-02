<?php

return [

	'requirements' => [
		'openssl',
		'pdo',
		'mbstring',
		'tokenizer',
		'curl',
		'imagick'
	],

	'permissions' => [
		'storage/app/' => '775',
		'storage/framework/' => '775',
		'storage/logs/' => '775',
		'bootstrap/cache/' => '775',
	],
];
