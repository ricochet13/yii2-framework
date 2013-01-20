<?php
/**
 * MemCacheServer class file
 *
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\caching;

/**
 * MemCacheServer represents the configuration data for a single memcache or memcached server.
 *
 * See [PHP manual](http://www.php.net/manual/en/function.Memcache-addServer.php) for detailed explanation
 * of each configuration property.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class MemCacheServer extends \yii\base\Object
{
	/**
	 * @var string memcache server hostname or IP address
	 */
	public $host;
	/**
	 * @var integer memcache server port
	 */
	public $port = 11211;
	/**
	 * @var integer probability of using this server among all servers.
	 */
	public $weight = 1;
	/**
	 * @var boolean whether to use a persistent connection. This is used by memcache only.
	 */
	public $persistent = true;
	/**
	 * @var integer value in seconds which will be used for connecting to the server. This is used by memcache only.
	 */
	public $timeout = 15;
	/**
	 * @var integer how often a failed server will be retried (in seconds). This is used by memcache only.
	 */
	public $retryInterval = 15;
	/**
	 * @var boolean if the server should be flagged as online upon a failure. This is used by memcache only.
	 */
	public $status = true;
}