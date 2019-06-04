<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Common\Common;

use Common\Common\PhpAmqpLib\Channel;
use Common\Common\PhpAmqpLib\Exception;
use Common\Common\PhpAmqpLib\Connection;
use Common\Common\PhpAmqpLib\Message;

/**
 * Description of NewRabbitMQ
 *
 * @author Administrator
 */
class RabbitMQ {

    /**
     * 直接匹配，通过Exchange名称+RoutingKey来发送与接收消息。
     */
    const EXCHANGE_TYPE_DIRECT = 'direct';

    /**
     * 广播订阅，向所有消费者发布消息，但只有消费者将队列绑定到该路由才能收到消息，忽略RoutingKey。
     */
    const EXCHANGE_TYPE_FANOUT = 'fanout';

    /**
     * 主题匹配订阅，这里的主题指的是RoutingKey，RoutingKey可以采用通配符，如：*或#，RoutingKey命名采用.来分隔多个词，只有消费者将队列绑定到该路由且指定的RoutingKey符合匹配规则时才能收到消息。
     */
    const EXCHANGE_TYPE_TOPIC = 'topic';

    /**
     * 消息头订阅，消息发布前，为消息定义一个或多个键值对的消息头，然后消费者接收消息时同样需要定义类似的键值对请求头，里面需要多包含一个匹配模式（有：x-mactch=all,或者x-mactch=any）,只有请求头与消息头相匹配，才能接收到消息，忽略RoutingKey。
     */
    const EXCHANGE_TYPE_HEADER = 'header';

    /**
     * 消息持久化 TRUE。
     */
    const MESSAGE_DURABLE_YES = 2;

    /**
     * 消息持久化 FALSE。
     */
    const MESSAGE_DURABLE_NO = 1;

    /**
     * 当前服务连接。
     * @var type 
     */
    private $_connection;

    /**
     * 当前交换机。
     * @var type 
     */
    private $_exchange;

    /**
     * 当前通道。
     * @var type 
     */
    private $_channel;

    /**
     * 当前消息队列。
     * @var type 
     */
    private $_queue;

    /**
     * 消息队列名称。
     * @var type 
     */
    private $_queueName;

    /**
     * 订阅者唯一标识。
     * @var type 
     */
    private $_consumerID;

    /**
     * 路由Key。
     * @var type 
     */
    private $_routingKey;

    /**
     * 收到消息后的回调方法。
     * @var type 
     */
    private $_callback;

    /**
     * 服务器配置。
     * @var type 
     */
    private $server;
    //静态变量保存全局实例
    private static $_instance = null;

    /**
     * 初始化。
     */
    public function init() {
        parent::init();
        //脚本退出前，关闭连接
        register_shutdown_function([$this, 'close']);
    }

    //静态方法，单例统一访问入口
    static public function getInstance() {
        if (is_null(self::$_instance) || !(self::$_instance instanceof self)) {
            self::$_instance = new self ();
            self::$_instance->server = C('Rabbit_host');
        }
        return self::$_instance;
    }

    /**
     * 初始化服务（同时创建连接、队列、交换机并将创建的队列绑定到创建的交换机）
     * @param type $queueName   队列名称
     * @param type $exchange    交换机名称
     * @param bool $routingKey  路由Key。
     * @param bool $durable     是否持久化。
     * @param bool $exclusive   是否排他性。
     * @param string $type      交换器类型。
     * @param bool $auto_delete 是否自动删除。
     */
    public function initService($queueName, $exchange, $routingKey = '', $durable = true, $exclusive = false, $type = self::EXCHANGE_TYPE_DIRECT, $auto_delete = false) {
        try {
            $this->createConnection();
            $this->createExchange($exchange, $type, false, $durable, $auto_delete);
            $this->createQueue($queueName, false, $durable, $exclusive, $auto_delete);
            $this->queueBind($queueName, $exchange, $routingKey);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * 连接。
     */
    public function connect() {
        return $this->getConnect();
    }

    /**
     * 关闭连接。
     */
    public function close() {
        if ($this->_channel) {
            $this->_channel->close();
        }
        if ($this->_isConnect()) {
            $this->_connection->close();
        }
    }

    /**
     * 获取连接。
     */
    public function getConnect() {
        if (!$this->_isConnect()) {
            try {
                $this->createConnection();
            } catch (Exception\AMQPRuntimeException $e) {
                throw new ErrorException('RabbitMQ Server Connect error', 500, 1);
            }
        }
        return $this->_connection;
    }

    /**
     * 获取当前连接通道。
     * @return AMQPChannel
     * @throws ErrorException
     */
    public function getChannel() {
        if (!$this->_channel) {
            $this->_channel = $this->getConnect()->channel();
        }
        return $this->_channel;
    }

    /**
     * 检测是否已连接。
     * @return bool
     */
    private function _isConnect() {
        if ($this->_connection && $this->_connection->isConnected()) {
            return true;
        }
        return false;
    }

    /**
     * 通道是否已打开
     */
    private function _isChannel() {
        if (($this->_channel instanceof Channel\AMQPChannel)) {
            $channelID = $this->_channel->getChannelId();
            $channelID * 1 > 0 && $channel = $this->_connection->channel($this->_channel->getChannelId());
            if (!empty($channel)) {
                return true;
            }
        }
        return false;
    }

    /**
     * 创建一个RabbitMQ Server连接。
     * @param string $host 消息服务器地址
     * @param string $port 消息服务器端口号
     * @param string $user 消息服务器登录用户名
     * @param string $password 消息服务器登录密码
     * @param string $vhost 虚拟RabbitMQ服务器
     * @return An AMQP channel or FALSE on failure
     */
    public function createConnection($host = null, $port = null, $user = null, $password = null, $vhost = null) {
        logger_task('[' . get_class() . ']', 'Creating connection', 'RabbitMQInfo');
        if ($host)
            $this->server['host'] = $host;
        if ($port)
            $this->server['port'] = $port;
        if ($user)
            $this->server['user'] = $user;
        if ($password)
            $this->server['password'] = $password;
        if ($vhost)
            $this->server['vhost'] = $vhost;
        if (!$this->_isConnect()) {
            $this->_connection = new Connection\AMQPConnection($this->server['host'], $this->server['port'], $this->server['user'], $this->server['password'], $this->server['vhost']);
        }
        if (!$this->_connection) {
            logger_task('[' . get_class() . ']', 'Cannot create connection', 'RabbitMQError');
            return false;
        }
        if (!$this->_isChannel()) {
            $this->_channel = $this->_connection->channel();
        }
        if (!$this->_channel) {
            logger_task('[' . get_class() . ']', 'Cannot create channel', 'RabbitMQError');
            return false;
        }
        return $this->_connection;
    }

    /**
     * 创建一个交换机
     * @param string $name 交换机名称。
     * @param string $type 交换机类型。
     * @param bool $passive 设置为TRUE时，只查询指定交换机是否存在，不会创建队列。如果该交换机已存在，则会返回true；如果不存在，则会返回Error。
     * @param bool $durable 是否持久化。
     * @param bool $auto_delete 是否自动删除，设置为TRUE时如果该队列没有任何订阅的消费者的话，该队列会被自动删除。这种队列适用于临时队列。
     */
    public function createExchange($name, $type = self::EXCHANGE_TYPE_DIRECT, $passive = false, $durable = true, $auto_delete = true) {
        $ret = $this->_channel->exchange_declare($name, $type, $passive, $durable, $auto_delete);
        $this->_exchange = $name;
        return $name;
    }

    /**
     * 创建一个延时队列用交换机(默认非持久)
     * @param string $name 交换机名称。
     * @param bool $passive 设置为TRUE时，只查询指定交换机是否存在，不会创建队列。如果该交换机已存在，则会返回true；如果不存在，则会返回Error。
     * @param bool $durable 是否持久化。
     * @param bool $auto_delete 是否自动删除，设置为TRUE时如果该队列没有任何订阅的消费者的话，该队列会被自动删除。这种队列适用于临时队列。
     */
    public function createDelayExchange($name, $passive = false, $durable = true, $auto_delete = true) {
        return $this->createExchange($name, self::EXCHANGE_TYPE_DIRECT, $passive, $durable, $auto_delete);
    }

    /**
     * 创建一个消息队列。
     * @param string $name 队列名称。
     * @param bool $passive 设置为TRUE时，只查询指定队列是否存在，不会创建队列。如果该队列已存在，则会返回true；如果不存在，则会返回Error。
     * @param bool $durable 是否持久化。
     * @param bool $exclusive 是否排他性.
     * @param bool $auto_delete 是否自动删除。
     * @param array $arguments 选项  x-dead-letter-exchange：出现dead letter之后将dead letter重新发送到指定exchange x-dead-letter-routing-key：指定routing-key发送。
     * @param int $ticket 延时时间。
     */
    public function createQueue($name = null, $passive = false, $durable = true, $exclusive = false, $auto_delete = true, $arguments = null, $ticket = null) {
        if (!$name)
            $name = $this->generateRandomString('10');
        $ret = $this->_channel->queue_declare($name, $passive, $durable, $exclusive, $auto_delete, false, $arguments, $ticket);
        if (!is_array($ret)) {
            logger_task('[' . get_class() . ']', 'Cannot create queue', 'RabbitMQError');
            return false;
        }
        $this->_queue = $ret[0];
        $this->_queueName = $name;
        return $name;
    }

    /**
     * 创建一个延时消息队列(默认非持久)。
     * @param string $name 队列名称。
     * @param array $arguments 选项  x-dead-letter-exchange：出现dead letter之后将dead letter重新发送到指定exchange x-dead-letter-routing-key：指定routing-key发送。
     * @param int $ticket 延时时间（消息的延时秒数）。
     */
    public function createDelayQueue($name = null, $exchange = '', $ticket = 0, $durable = true, $auto_delete = true) {
        $arguments = array();
        if (!empty($exchange)) {
            $arguments = array(
                "x-dead-letter-exchange" => array("S", $exchange)
            );
            if ($ticket * 1 > 0) {
                $ticket = $ticket * 1 + 3600000;
                $arguments["x-expires"] = array("I", $ticket);
            }
        }
        return $this->createQueue($name, false, $durable, false, $auto_delete, $arguments);
    }

    /**
     * 绑定队列到指定交换机。
     * @param string $queue 要绑定到指定交换机的队列名称。
     * @param string $exchange 要绑定的交换机名称。
     * @param string $routingKey 要绑定的路由名称。
     */
    public function queueBind($queueName = '', $exchange = '', $routingKey = null) {
        if (empty($queueName)) {
            $queueName = $this->_queueName;
        }
        if (empty($exchange)) {
            $exchange = $this->_exchange;
        }
        $this->_routingKey = $routingKey;
        $this->_channel->queue_bind($queueName, $exchange, $routingKey);
    }

    /**
     * 设置当前通道的QoS参数。
     * @param string|int $prefetch_size 客户端接受消息的大小，如果设置了no-ack选项，则会忽略该选项。
     * @param string|int $prefetch_count 客户端接受消息的最大数，设置了该值消息被ASK后才会接受下一条消息。 如果设置了no-ack选项，则会忽略该选项。
     * @param bool $global 是否影响当前连接上的全部channel，默认只对当前channel生效。
     */
    public function setQos($prefetch_size, $prefetch_count, $global) {
        $this->_channel->basic_qos($prefetch_size, $prefetch_count, $global);
    }

    /**
     * 订阅消息。
     * @param type $queue       要订阅的消息队列。
     * @param type $consumerTag 订阅者唯一标识。
     * @param type $callback    接收到消息时的回调方法。
     * @param type $noLocal     设置为TRUE时服务器不接受此订阅者发布的消息。
     * @param type $noAck       设置为TRUE时不需要向服务器发送确认消息。
     * @param type $exclusive   是否独占当前消息队列。 
     * @param type $nowait      设置为TRUE时不需要等待服务器响应，可能会引发channel异常。
     */
    public function subscribe($queue = null, $consumerTag = '', $callback = null, $noLocal = false, $noAck = false, $exclusive = false, $nowait = false) {
        if (!$queue)
            $queue = $this->_queue;
        if (!empty($callback) && is_callable($callback)) {
            logger_task('[' . get_class() . ']', 'Registering worker callback', 'RabbitMQInfo');
            $this->_callback = $callback;
        }
        $this->_consumerID = $this->_channel->basic_consume($queue, $consumerTag, $noLocal, $noAck, $exclusive, $nowait, $this->_callback);
        return $this->_consumerID;
    }

    /**
     * 取消订阅。
     * @param type $consumer_tag 订阅者唯一标识。
     */
    public function unsubscribe($consumer_tag = '') {
        if (empty($consumer_tag))
            $consumer_tag = $this->_consumerID;
        return $this->_channel->basic_cancel($consumer_tag);
    }

    /**
     * 发送消息。
     * @param type $msg        消息内容
     * @param type $routingKey 路由Key
     * @param type $exchange   交换机
     */
    public function sendMessage($msg, $routingKey = '', $exchange = '', $ticket = null) {
        $properties = array('content_type' => 'text/JSON', 'delivery_mode' => self::MESSAGE_DURABLE_YES, 'expiration' => $ticket);
        if (is_array($msg)) {
            $properties['content_type'] = 'text/JSON';
            $msg = json_encode($msg, JSON_UNESCAPED_UNICODE);
        } else {
            $properties['content_type'] = 'text/plain';
        }
        if (empty($exchange)) {
            $exchange = $this->_exchange;
        }
        $message = new Message\AMQPMessage($msg, $properties);
        $this->_channel->basic_publish($message, $exchange, $routingKey);
    }

    /**
     * 获取消息。（不可用于循环）。
     * @param type $queueName 队列名称。
     * @param type $exchange  交换机名称。
     * @param type $autoAsk   是否自动回复。
     * @return type
     */
    public function receiveMessage($queueName = '', $exchange = '', $autoAsk = true) {
        $msg = $this->_channel->basic_get($queue);
        if ($msg) {
            if ($autoAsk) {
                $this->_channel->basic_ack($msg->delivery_info['delivery_tag']);
            }
            $mix = $msg->body;
        }
        return $mix;
    }

    /**
     * 开始异步接收消息。
     */
    public function startAsynRecMsg() {
        while (count($this->_channel->callbacks)) {
            $this->_channel->wait();
        }
    }

    /**
     * 获取当前通道ID。
     * @return integer
     */
    public function getChannelId() {
        return $this->_channel->getChannelId();
    }

    /**
     * 随机生成一个指定长度的字符串。
     * @param int $length 长度。
     * @return string
     */
    private function generateRandomString($length = 10) {
        $randomstring = '';
        if ($length > 32) {
            $multiplier = round($length / 32, 0, PHP_ROUND_HALF_DOWN);
            $remainder = $length % 32;
            for ($i = 0; $i < $multiplier; $i++) {
                $randomstring .= substr(str_shuffle(md5(rand())), 0, 32);
            }
            $randomstring .= substr(str_shuffle(md5(rand())), 0, $remainder);
        } else
            $randomstring = substr(str_shuffle(md5(rand())), 0, $length);
        return $randomstring;
    }

}
