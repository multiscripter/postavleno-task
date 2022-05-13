<?
class WebException {
	private $statuses = [
		500 => 'Internal Server Error'
	];

	public function __construct($status, $message) {
		$str = "HTTP/1.1 $status ";
		$str .= $this->statuses[$status];
		header($str);
		$response = [
			'status' => false,
			'code' => $status,
			'data' => [ 
				'message' => $message
			]
		];
		die(json_encode($response));
	}
}