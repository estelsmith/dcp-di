About
=====
Provides a minimalistic dependency injection container.

Example
-------
	interface MailerInterface {}

	class Mailer implements MailerInterface {
		protected $_transport;

		public function __construct(MailerTransportInterface $transport) {
			$this->_transport = $transport;
		}
	}

	interface MailerTransportInterface {}

	class MailerTransport implements MailerTransportInterface {
		protected $_host;
		protected $_port;

		public function __construct($host, $port) {
			$this->_host = $host;
			$this->_port = $port;
		}
	}

	$config = array(
		'mailer.transport' => array(
			'localhost',
			25
		)
	);

	$di = new DCP\Di\Container();

	$di->register('MailerInterface')->asClass('Mailer');

	//Register MailerTransportInterface dependencies as a shared MailerTransport instance
	$di->register('MailerTransportInterface')->asClass('MailerTransport', TRUE, $config['mailer.transport']);

	//In PHP 5.5
	// $di->register(MailerTransportInterface::class)->asClass(MailerTransport::class, TRUE, $config['mailer.transport']);

	var_dump($di->get('test_mailer'));