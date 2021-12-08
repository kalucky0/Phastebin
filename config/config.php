<?php

return [
	'settings' => [
		// set to false in production
		'displayErrorDetails' => true,
		'store_type' => 'File',
		'keygen_type' => 'Phonetic',

		// Document storage configurations
		// Note: Any file paths are relative to the public dir
		'store_file_config' => [
			'storage_dir' => '../data/'
		],

		'store_redis_config' => [
			'connection' => 'tcp://127.0.0.1:6379'
		],
	],
];
