<?
include ROOT.'/controllers/AbstractController.php';
include ROOT.'/dao/'.STORAGE.'DAO.php';
include ROOT.'/validators/WebValidator.php';
$daoConf = include ROOT.'/conf/'.STORAGE.'Conf.php';

class WebController extends AbstractController {
	private $dao;
	private $isAjax;
	private $validator;

	public function __construct($daoConf) {
		$this->dao = new DAO($daoConf);
		$this->isAjax = isset($_SERVER['HTTP_X_AJAX']);
		$this->validator = new WebValidator();
	}

	public function run() {
		if ($this->isAjax)
			header('Content-Type: application/json; charset=utf-8');
		$uri = trim($_SERVER['REQUEST_URI'], '/');
		$uri = explode('/', $uri);
		$this->validator->validate($uri);
		$params = $this->getActionParams($uri);
		$data = $this->dao->action($params['action'], [$params['key']]);
		$this->sendResponse($data);
	}

	protected function getActionParams($params) {
		return [
			'action' => strtolower($_SERVER['REQUEST_METHOD']),
			'key'    => !empty($params[2]) ? $params[2] : null
		];
	}

	protected function sendResponse($data) {
		header('HTTP/1.1 200 OK');
		if ($this->isAjax) {
			$response = [
				'status' => true,
				'code' => 200,
				'data' => $data
			];
			die(json_encode($response));
		}
		ob_start();
		include ROOT.'/templates/index.php';
		echo ob_get_clean();
	}

}
(new WebController($daoConf))->run();