<?php
	set_time_limit(0);

	use Ratchet\MessageComponentInterface;
	use Ratchet\ConnectionInterface;
	use Ratchet\Server\IoServer;
	use Ratchet\Http\HttpServer;
	use Ratchet\WebSocket\WsServer;
	require_once '../vendor/autoload.php';

	class Chat implements MessageComponentInterface {
		protected $clients;
		// protected $users;

		public function __construct() {
			$this->clients = new \SplObjectStorage;
		}

		public function onOpen(ConnectionInterface $conn) {
			$this->clients->attach($conn);
			// $this->users[$conn->resourceId] = $conn;
		}

		public function onClose(ConnectionInterface $conn) {
			$this->clients->detach($conn);
			// unset($this->users[$conn->resourceId]);
		}

		public function onMessage(ConnectionInterface $from,  $data) {
			$from_id = $from->resourceId;
			$data = json_decode($data);
			$type = $data->type;
			switch ($type) {
				case 'message':
					$user_name = $data->user_name;
					$msg = $data->msg;
					$response_from = "<small style='color: #1B6CA8;'>".$user_name.": ".$msg."</small><br>"; // change users name color
					$response_to = "<small>".$user_name.": ".$msg."</small><br>";
					// Output
					$from->send(json_encode(array("type"=>$type,"msg"=>$response_from)));
					foreach($this->clients as $client)
					{
						if($from!=$client)
						{
							$client->send(json_encode(array("type"=>$type,"msg"=>$response_to)));
						}
					}
					break;
			}
		}

		public function onError(ConnectionInterface $conn, \Exception $e) {
			$conn->close();
		}

	};

	$server = IoServer::factory(
		new HttpServer(new WsServer(new Chat())),
		8080
	);
	$server->run();

?>
