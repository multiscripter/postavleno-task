<?
include ROOT.'/controllers/IController.php';

abstract class AbstractController implements IController {
	
	abstract public function run();

	abstract protected function getActionParams($params);

	abstract protected function sendResponse($data);
}