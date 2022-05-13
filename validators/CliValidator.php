<?
include ROOT.'/validators/IValidator.php';

class CliValidator implements IValidator {
	public function validate($params = []) {
		if (!$params)
			die('error: no arguments passed'."\n");

		if ($params[0] != 'redis')
			die('error: first argument must be "redis"'."\n");

		if (empty($params[1]) || !in_array($params[1], ['add', 'delete']))
			die('error: second argument must be "add" or "delete"'."\n");

		if (empty($params[2]))
			die('error: pass third argument as key in storage'."\n");

		if ($params[1] == 'add' && empty($params[3]))
			die('error: pass fourth argument as key mapped value'."\n");
	}
}