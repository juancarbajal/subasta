<?php
/**
 * @see Zend_Queue_Adapter_AdapterAbstract
 */
require_once 'Zend/Queue/Adapter/AdapterAbstract.php';
//require_once '/Users/tavis.aitken/SourceCode/rabbitmq-test/php-amqplib/amqp.inc';

// My Zend Queue adapter to use an AMQP adapter
class Amqplib_AMQP extends Zend_Queue_Adapter_AdapterAbstract
{
    const DEFAULT_HOST   = '127.0.0.1';
    const DEFAULT_PORT   = 5672;
    const DEFAULT_VHOST  = '/';
    const DEFAULT_USER   = 'guest';
    const DEFAULT_PASS   = 'guest';

    /**
     * @var AMQPConnection  
     */
    private $_connection = null;

    /**
     * @var AMQPChannel 
     */
    private $_channel = null;

    /**
     * The exchange we are using for this queueu
     * @var string
     */
    private $_exchange = '';

    /**
     * tag for consuming 
     * @var string
     */
    private $_consumerTag = '';

    public function __construct($options, Zend_Queue $queue = null)
    {
        parent::__construct($options);

        $options = &$this->_options['driverOptions'];
        if (!array_key_exists('host', $options)) {
            $options['host'] = self::DEFAULT_HOST;
        }
        if (!array_key_exists('port', $options)) {
            $options['port'] = self::DEFAULT_PORT;
        }
        if (!array_key_exists('vhost', $options)) {
            $options['vhost'] = self::DEFAULT_VHOST;
        }
        if (!array_key_exists('user', $options)) {
            $options['user'] = self::DEFAULT_USER;
        }
        if (!array_key_exists('password', $options)) {
            $options['password'] = self::DEFAULT_USER;
        }
       $options['channel'] = array( 
            'exclusive' => false,
            'passive'   => false,
            'active'    => true,
            'write'     => true,
            'read'      => true,
        );

        $this->_connection = new AMQPConnection(
            $options['host'],
            $options['port'],
            $options['user'], 
            $options['password']
        );
        $this->_channel = $this->_connection->channel();
        $this->_channel->access_request(
            $options['vhost'],
            $options['channel']['exclusive'], 
            $options['channel']['passive'], 
            $options['channel']['active'], 
            $options['channel']['write']
        );

        $options['queue'] = array(
            'passive' => false,
            'durable' => false,
            'exclusive' => false,
            'auto_delete' => true,
            'nowait' => false,
        );

        $this->_channel->queue_declare($this->_options['name']);

        $options['exchange'] = array(
           'name'        => 'testExchange',
           'type'        => 'direct',
           'passive'     => false,
           'durable'     => false,
           'auto_delete' => false,
        );
        $this->_channel->exchange_declare(
            $options['exchange']['name'],
            $options['exchange']['type'],
            $options['exchange']['passive'],
            $options['exchange']['durable'],
            $options['exchange']['auto_delete']
        );

        $this->_exchange = $options['exchange']['name'];

        $options['binding'] = array(
            'routing_key' => 'testkey',
            'nowait'      => true,
        );

        $this->_channel->queue_bind(
            $this->_options['name'],
            $options['exchange']['name'],
            $options['binding']['routing_key'],
            $options['binding']['nowait']
        );
    }

    /**
     * Close the socket explicitly when destructed
     *
     * @return void
     */
    public function __destruct()
    {
        $this->_channel->close();
        unset($this->_channel);
        $this->_connection->close();
        unset($this->_connection);
    }   

    /**
     * Create a new queue
     *
     * @param  string  $name    queue name
     * @param  integer $timeout default visibility timeout
     * @return void
     * @throws Zend_Queue_Exception
     */
    public function create($name, $timeout=null)
    {
        $this->_channel->queue_declare($name);
    }

    /**
     * Delete a queue and all of its messages
     *
     * @param  string $name queue name
     * @return void
     * @throws Zend_Queue_Exception
     */
    public function delete($name)
    {
        $this->_channel->queue_delete($name);
    }

    /**
     * Delete a message from the queue
     *
     * Returns true if the message is deleted, false if the deletion is
     * unsuccessful.
     *
     * @param  Zend_Queue_Message $message
     * @return boolean
     */
    public function deleteMessage(Zend_Queue_Message $message)
    {
    }

    /**
     * Get an array of all available queues
     *
     * @return void
     * @throws Zend_Queue_Exception
     */
    public function getQueues()
    {
        require_once 'Zend/Queue/Exception.php';
        throw new Zend_Queue_Exception('getQueues() is not supported in this adapter');
    }

    /**
     * Return the first element in the queue
     *
     * @param  integer    $maxMessages
     * @param  integer    $timeout
     * @param  Zend_Queue $queue
     * @return Zend_Queue_Message_Iterator
     */
    public function receive($maxMessages=null, $timeout=null, Zend_Queue $queue=null)
    {
        if ($maxMessages === null) {
            $maxMessages = 1;
        }
        if ($timeout === null) {
            $timeout = self::RECEIVE_TIMEOUT_DEFAULT;
        }
        if ($queue === null) {
            $queue = $this->_queue;
        }

        /*
         *if ($maxMessages > 0) {
         *    for ($i = 0; $i < $maxMessages; $i++) {
         *        echo "Trying to get message.. " . PHP_EOL;
         *    }
         *}
         */
        $msg = $this->_channel->basic_consume(
            $queue->getName(),
            'testTag',
            false,
            false,
            false,
            array($this,'processMessage')
        );
        echo "callbacks: " . count($this->_channel->callbacks) . PHP_EOL;
        while(count($this->_channel->callbacks)) {
            echo "Trying to get message.. " . PHP_EOL;
            $this->_channel->wait();
            print_r($this->_channel->callbacks);
        }

        $options = array(
            'queue'        => $queue,
            'data'         => array(),
            'messageClass' => $queue->getMessageClass()
        );

        $classname = $queue->getMessageSetClass();

        if (!class_exists($classname)) {
            require_once 'Zend/Loader.php';
            Zend_Loader::loadClass($classname);
        }
        return new $classname($options);
    }

    public function processMessage($msg)
    {
        echo $msg;
    }



    /**
     * Push an element onto the end of the queue
     *
     * @param  string     $message message to send to the queue
     * @param  Zend_Queue $queue
     * @return Zend_Queue_Message
     */
    public function send($message, Zend_Queue $queue=null)
    {
        if ($queue === null) {
            $queue = $this->_queue;
        }
        $msg = new AMQPMessage($message, array('content_type' => 'text/plain'));
        $this->_channel->basic_publish($msg,$this->_exchange);

        $data = array(
            'message_id' => null,
            'body'       => $message,
            'md5'        => md5($message),
        );

        $options = array(
            'queue' => $queue,
            'data'  => $data,
        );

        $classname = $queue->getMessageClass();
        if (!class_exists($classname)) {
            require_once 'Zend/Loader.php';
            Zend_Loader::loadClass($classname);
        }
        return new $classname($options);
    }

    /**
     * Returns the length of the queue
     *
     * @param  Zend_Queue $queue
     * @return integer
     * @throws Zend_Queue_Exception (not supported)
     */
    public function count(Zend_Queue $queue=null)
    {
        require_once 'Zend/Queue/Exception.php';
        throw new Zend_Queue_Exception('count() is not supported in this adapter');
    }

    /**
     * Does a queue already exist?
     *
     * @param  string $name
     * @return boolean
     * @throws Zend_Queue_Exception (not supported)
     */
    public function isExists($name)
    {
        return false;
    }

    /**
     * Return a list of queue capabilities functions
     *
     * $array['function name'] = true or false
     * true is supported, false is not supported.
     *
     * @param  string $name
     * @return array
     */
    public function getCapabilities()
    {
        return array(
            'create'        => true,
            'delete'        => true,
            'send'          => true,
            'receive'       => true,
            'deleteMessage' => true,
            'getQueues'     => false,
            'count'         => false,
            'isExists'      => false,
        );
    }

}
