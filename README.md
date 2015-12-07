# *WARNING: UNMAINTAINED!*

About
=====
Provides a minimalistic dependency injection container.

Example
-------
```php
use DCP\Di\Container;
use DCP\Di\Service\Reference;

interface MailerInterface {}

class Mailer implements MailerInterface
{
    protected $transport;

    public function __construct(MailerTransportInterface $transport)
    {
        $this->transport = $transport;
    }
}

interface MailerTransportInterface {}

class MailerTransport implements MailerTransportInterface
{
    protected $host;
    protected $port;

    public function __construct($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
    }
}

$di = new Container();

$di
    ->register(MailerInterface::class, 'mailer')
    ->toClass(Mailer::class)
;

$di
    ->register(MailerTransportInterface::class)
    ->toInstance(new Reference(MailerTransport::class))
;

$di
    ->register(MailerTransport::class)
    ->addArguments([
        'host' => 'localhost',
        'port' => 25
    ])
;

var_dump($di->get('mailer'));
```
