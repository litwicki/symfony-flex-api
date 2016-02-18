<?php

namespace Tavro\Bundle\CoreBundle\Services;

use MongoDB\Driver\Manager;

/**
 * MongoDB session handler.
 *
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
class MongoDbSessionHandler implements \SessionHandlerInterface
{
    /**
     * @var \Mongo
     */
    private $mongo;

    /**
     * @var \MongoCollection
     */
    private $collection;

    /**
     * @var array
     */
    private $options;

    /**
     * Constructor.
     *
     * List of available options:
     *  * database: The name of the database [required]
     *  * collection: The name of the collection [required]
     *  * id_field: The field name for storing the session id [default: _id]
     *  * data_field: The field name for storing the session data [default: data]
     *  * time_field: The field name for storing the timestamp [default: time]
     *  * expiry_field: The field name for storing the expiry-timestamp [default: expires_at]
     *
     * It is strongly recommended to put an index on the `expiry_field` for
     * garbage-collection. Alternatively it's possible to automatically expire
     * the sessions in the database as described below:
     *
     * A TTL collections can be used on MongoDB 2.2+ to cleanup expired sessions
     * automatically. Such an index can for example look like this:
     *
     *     db.<session-collection>.ensureIndex(
     *         { "<expiry-field>": 1 },
     *         { "expireAfterSeconds": 0 }
     *     )
     *
     * More details on: http://docs.mongodb.org/manual/tutorial/expire-data/
     *
     * If you use such an index, you can drop `gc_probability` to 0 since
     * no garbage-collection is required.
     *
     * @param Manager $mongo   A MongoClient or Mongo instance
     * @param array               $options An associative array of field options
     *
     * @throws \InvalidArgumentException When MongoClient or Mongo instance not provided
     * @throws \InvalidArgumentException When "database" or "collection" not provided
     */
    public function __construct($mongo, array $options)
    {
        if (!($mongo instanceof Manager || $mongo instanceof \MongoDB)) {
            throw new \InvalidArgumentException('MongoClient or Mongo instance required');
        }

        if (!isset($options['database']) || !isset($options['collection'])) {
            throw new \InvalidArgumentException('You must provide the "database" and "collection" option for MongoDBSessionHandler');
        }

        $this->mongo = $mongo;

        $this->options = array_merge(array(
            'id_field' => '_id',
            'data_field' => 'data',
            'time_field' => 'time',
            'expiry_field' => 'expires_at',
        ), $options);
    }

    /**
     * {@inheritdoc}
     */
    public function open($savePath, $sessionName)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function destroy($sessionId)
    {
        $this->getCollection()->remove(array(
            $this->options['id_field'] => $sessionId,
        ));

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function gc($maxlifetime)
    {
        $this->getCollection()->remove(array(
            $this->options['expiry_field'] => array('$lt' => new \MongoDate()),
        ));

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function write($sessionId, $data)
    {
        $expiry = new \MongoDate(time() + (int) ini_get('session.gc_maxlifetime'));

        $fields = array(
            $this->options['data_field'] => new \MongoBinData($data, \MongoBinData::BYTE_ARRAY),
            $this->options['time_field'] => new \MongoDate(),
            $this->options['expiry_field'] => $expiry,
        );

        $this->getCollection()->update(
            array($this->options['id_field'] => $sessionId),
            array('$set' => $fields),
            array('upsert' => true, 'multiple' => false)
        );

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function read($sessionId)
    {
        $dbData = $this->getCollection()->findOne(array(
            $this->options['id_field'] => $sessionId,
            $this->options['expiry_field'] => array('$gte' => new \MongoDate()),
        ));

        return null === $dbData ? '' : $dbData[$this->options['data_field']]->bin;
    }

    /**
     * Return a "MongoCollection" instance.
     *
     * @return \MongoCollection
     */
    private function getCollection()
    {
        if (null === $this->collection) {

            // Construct and execute the listDatabases command
            $listdatabases = new \MongoDB\Driver\Command(["listDatabases" => 1]);
            $result        = $this->mongo->executeCommand("admin", $listdatabases);

            /* The command returns a single result document, which contains the information
             * for all databases in a "databases" array field. */
            $databases     = current($result->toArray());
            
            foreach ($databases["databases"] as $database) {

                // Construct and execute the listCollections command for each database
                $listcollections = new \MongoDB\Driver\Command(["listCollections" => 1]);
                $result          = $this->mongo->executeCommand($database->name, $listcollections);
            
                /* The command returns a cursor, which we can iterate on to access
                 * information for each collection. */
                $collections     = $result->toArray();
            
                foreach ($collections as $collection) {
                    if($collection->name === $this->options['collection']) {
                        $this->collection = $collection;
                    }
                }
            }
            
        }

        return $this->collection;
    }

    /**
     * Return a Mongo instance.
     *
     * @return \Mongo
     */
    protected function getMongo()
    {
        return $this->mongo;
    }
}
