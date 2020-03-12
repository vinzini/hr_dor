<?php


return [
	'client_id' => 'client_id',
	'grant_type' => 'grant_type',
	'client_secret' => 'client_secret',

	'grant_type_password'=>env('GRANT_TYPE','password'),
	'grant_type_refresh'=>env('GRANT_TYPE_REFRESH','refresh_token'),

	'client_id_val'=>env('CLIENT_ID'), 
	'client_secret_val'=>env('CLIENT_SECRET'),  
];