<?
class DAO {
	private $conf;
	private $storage;

	public function __construct($conf) {
		$this->conf = $conf;
		$this->storage = new Redis();
	}

	public function action($action, $params) {
		switch ($action) {
			case 'add':
				$result = $this->create($params[0], $params[1]);
				break;

			case 'delete';
				$result = $this->delete($params[0]);
				break;

			case 'get';
				$result = $this->read(!empty($params[0]) ? $params[0] : null);
				break;
			
			default:
				$result = null;
				break;
		}
		return $result;
	}

	private function connect() {
		$this->storage->connect($this->conf['host'], $this->conf['port']);
		$this->storage->auth($this->conf['pass']);
	}

	private function create($key, $value) {
		$this->connect();
		return $this->storage->setEx($key, $this->conf['ttl'], $value);
	}

	private function delete($key) {
		$this->connect();
		$this->storage->del($key);
		return new stdClass();
	}

	private function read($key = null) {
		$this->connect();
		if ($key) {
			$data = $this->storage->get($key);
			return $data ? [$key => $data] : null;
		}
		$keys   = $this->storage->keys('*');
		$values = $this->storage->mGet($keys);
		$data = [];
		for ($a = 0; $a < count($keys); $a++)
			$data[$keys[$a]] = $values[$a];
		return $data;
	}
}