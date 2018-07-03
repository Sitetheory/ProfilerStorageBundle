<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sitetheory\Bundle\ProfilerStorageBundle\Profiler;

use Symfony\Component\HttpKernel\Profiler\Profile;

/**
 * MongoDbProfilerStorage stores profiling information in a Mongo database.
 *
 * Class MongoDbProfilerStorage
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Alex Gurrola <alex@sitetheory.io>
 */
class MongoDbProfilerStorage implements ProfilerStorageInterface
{
    /**
     * @var string
     */
    protected $dsn;

    /**
     * @var int
     */
    protected $lifetime;

    /**
     * @var \MongoDB\Collection
     */
    private $mongo;

    /**
     * Constructor.
     *
     * @param string $dsn      A data source name
     * @param string $username Not used
     * @param string $password Not used
     * @param int    $lifetime The lifetime to use for the purge
     */
    public function __construct($dsn, $username = '', $password = '', $lifetime = 86400)
    {
        $this->dsn = $dsn;
        $this->lifetime = (int) $lifetime;
    }

    /**
     * {@inheritdoc}
     */
    public function find($ip, $url, $limit, $method, $start = null, $end = null, $statusCode = null)
    {
        $cursor = $this->getMongo()->find(
            $this->buildQuery($ip, $url, $method, $start, $end, $statusCode),
            array('data' => 0, 'sort' => array('time' => -1), 'limit' => intval($limit))
        );

        $tokens = array();
        foreach ($cursor as $profile) {
            $tokens[] = $this->getData($profile);
        }

        return $tokens;
    }

    /**
     * {@inheritdoc}
     */
    public function purge()
    {
        return $this->getMongo()->deleteMany(array());
    }

    /**
     * {@inheritdoc}
     */
    public function read($token)
    {
        $profile = $this->getMongo()->findOne(array('_id' => $token, 'data' => array('$exists' => true)));

        if (null !== $profile) {
            $profile = $this->createProfileFromData($this->getData($profile));
        }

        return $profile;
    }

    /**
     * {@inheritdoc}
     */
    public function write(Profile $profile)
    {
        $this->cleanup();

        $record = array(
            '_id' => $profile->getToken(),
            'parent' => $profile->getParentToken(),
            'data' => base64_encode(serialize($profile->getCollectors())),
            'ip' => $profile->getIp(),
            'method' => $profile->getMethod(),
            'url' => $profile->getUrl(),
            'time' => $profile->getTime(),
            'status_code' => $profile->getStatusCode(),
        );

        $result = $this->getMongo()->updateOne(array('_id' => $profile->getToken()), array(
            '$set' => array_filter($record, function ($v) {
                return !empty($v);
            }),
        ), array('upsert' => true));

        return $result->isAcknowledged();
    }

    /**
     * Internal convenience method that returns the instance of the MongoDB Collection.
     *
     * @return \MongoDB\Collection
     */
    protected function getMongo()
    {
        if (null !== $this->mongo) {
            return $this->mongo;
        }

        if (!$parsedDsn = $this->parseDsn($this->dsn)) {
            throw new \RuntimeException(sprintf('Please check your configuration. You are trying to use MongoDB with an invalid dsn "%s". The expected format is "mongodb://[user:pass@]host/database/collection"', $this->dsn));
        }

        list($server, $database, $collection) = $parsedDsn;

        $client = new \MongoDB\Client('mongodb://'.$server.'/'.$database);

        return $this->mongo = $client->selectCollection($database, $collection);
    }

    /**
     * @param array $data
     *
     * @return Profile
     */
    protected function createProfileFromData(array $data)
    {
        $profile = $this->getProfile($data);

        if ($data['parent']) {
            $parent = $this->getMongo()->findOne(array('_id' => $data['parent'], 'data' => array('$exists' => true)));
            if ($parent) {
                $profile->setParent($this->getProfile($this->getData($parent)));
            }
        }

        $profile->setChildren($this->readChildren($data['token']));

        return $profile;
    }

    /**
     * @param string $token
     *
     * @return Profile[] An array of Profile instances
     */
    protected function readChildren($token)
    {
        $profiles = array();

        $cursor = $this->getMongo()->find(array('parent' => $token, 'data' => array('$exists' => true)));
        foreach ($cursor as $d) {
            $profiles[] = $this->getProfile($this->getData($d));
        }

        return $profiles;
    }

    protected function cleanup()
    {
        $this->getMongo()->deleteMany(array('time' => array('$lt' => time() - $this->lifetime)));
    }

    /**
     * @param string $ip
     * @param string $url
     * @param string $method
     * @param int    $start
     * @param int    $end
     * @param int    $statusCode
     *
     * @return array
     */
    private function buildQuery($ip, $url, $method, $start, $end, $statusCode)
    {
        $query = array();

        if (!empty($ip)) {
            $query['ip'] = $ip;
        }

        if (!empty($url)) {
            $query['url'] = $url;
        }

        if (!empty($method)) {
            $query['method'] = $method;
        }

        if (!empty($start) || !empty($end)) {
            $query['time'] = array();
        }

        if (!empty($start)) {
            $query['time']['$gte'] = $start;
        }

        if (!empty($end)) {
            $query['time']['$lte'] = $end;
        }

        if (!empty($statusCode)) {
            $query['status_code'] = $statusCode;
        }

        return $query;
    }

    /**
     * @param \MongoDB\Model\BSONDocument $data
     *
     * @return array
     */
    private function getData(\MongoDB\Model\BSONDocument $data)
    {
        $data = $data->getArrayCopy();

        return array(
            'token' => $data['_id'],
            'parent' => isset($data['parent']) ? $data['parent'] : null,
            'ip' => isset($data['ip']) ? $data['ip'] : null,
            'method' => isset($data['method']) ? $data['method'] : null,
            'url' => isset($data['url']) ? $data['url'] : null,
            'time' => isset($data['time']) ? $data['time'] : null,
            'data' => isset($data['data']) ? $data['data'] : null,
            'status_code' => isset($data['status_code']) ? $data['status_code'] : null,
        );
    }

    /**
     * @param array $data
     *
     * @return Profile
     */
    private function getProfile(array $data)
    {
        $profile = new Profile($data['token']);
        $profile->setIp($data['ip']);
        $profile->setMethod($data['method']);
        $profile->setUrl($data['url']);
        $profile->setTime($data['time']);
        $profile->setCollectors(unserialize(base64_decode($data['data'])));

        return $profile;
    }

    /**
     * @param string $dsn
     *
     * @return null|array Array($server, $database, $collection)
     */
    private function parseDsn($dsn)
    {
        if (!preg_match('#^mongodb://(.*)/(.*)/(.*)$#', $dsn, $matches)) {
            return;
        }

        $server = $matches[1];
        $database = $matches[2];
        $collection = $matches[3];

        /* FIXME: This adds backwards compatibility but cannot function for modern implementations *
        preg_match('#^mongodb://(([^:]+):?(.*)(?=@))?@?([^/]*)(.*)$#', $server, $matchesServer);

        if ('' == $matchesServer[5] && '' != $matches[2]) {
            $server .= '/'.$matches[2];
        }
        /* */

        return array($server, $database, $collection);
    }
}
