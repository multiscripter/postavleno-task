<?
include ROOT.'/controllers/AbstractController.php';
include ROOT.'/dao/'.STORAGE.'DAO.php';
include ROOT.'/validators/CliValidator.php';
$daoConf = include ROOT.'/conf/'.STORAGE.'Conf.php';

class CliController extends AbstractController {
	private $dao;
	private $validator;

	public function __construct($daoConf) {
		$this->dao = new DAO($daoConf);
		$this->validator = new CliValidator();
	}

	public function run() {
		array_shift($_SERVER['argv']);
		$this->validator->validate($_SERVER['argv']);
		array_shift($_SERVER['argv']);
		$params = $this->getActionParams($_SERVER['argv']);
		$data = $this->dao->action($params['action'], $params['key']);
		$this->sendResponse($data);
	}

	protected function getActionParams($params) {
		return [
			'action' => $params[0],
			'key'    => array_slice($params, 1)
		];
	}

	protected function sendResponse($data) {
		echo json_encode($data);
		echo "\n";
	}
}
(new CliController($daoConf))->run();