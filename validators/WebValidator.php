<?
include ROOT.'/exceptions/WebException.php';
include ROOT.'/validators/IValidator.php';

class WebValidator implements IValidator {
	public function validate($params = []) {
		if (isset($_SERVER['HTTP_X_AJAX'])) {
			if (count($params) < 2 
					|| count($params) > 3 
					|| $params[0] != 'api' 
					|| $params[1] != 'redis')
				new WebException(500, 'Endpoint not exists');
			if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'DELETE'])) {
				header('Access-Control-Allow-Methods: GET, DELETE');
				new WebException(500, 'Method not allowed');
			}
			if ($_SERVER['REQUEST_METHOD'] == 'DELETE' && count($params) != 3)
				new WebException(500, 'Key not sended');
		} else {
			if ($_SERVER['REQUEST_URI'] != '/') {
				new WebException(500, 'Endpoint not exists');
			}
			if ($_SERVER['REQUEST_METHOD'] != 'GET') {
				header('Access-Control-Allow-Methods: GET');
				new WebException(500, 'Method not allowed');
			}
		}
	}
}