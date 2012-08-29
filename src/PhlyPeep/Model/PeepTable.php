<?php

namespace PhlyPeep\Model;

use SplObjectStorage;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Stdlib\Hydrator\ArraySerializable as ArraySerializableHydrator;

class PeepTable extends AbstractTableGateway
{
    protected $countPrototype;
    protected $peepHydrator;
    protected $peepPrototype;
    protected $peepsPrototype;
    protected $table     = 'peep';
    protected $tableName = 'peep';

    public function __construct(Adapter $adapter, $tableName = 'peep')
    {
        $this->adapter            = $adapter;
        $this->table              = $this->tableName = $tableName;
        $this->peepHydrator       = new ArraySerializableHydrator();
        $this->peepPrototype      = new PeepEntity;
        $this->countPrototype     = new ResultSet;
        $this->peepsPrototype     = new HydratingResultSet($this->peepHydrator, $this->peepPrototype);
        $this->resultSetPrototype = $this->peepsPrototype;
        $this->initialize();
    }

    public function fetchTimeline($offset = 0, $limit = 20)
    {
        $select = $this->getSql()->select();
        $select->offset($offset)
               ->limit($limit)
               ->order('timestamp DESC');
        return $this->getPeepsFromSelect($select);
    }

    public function fetchTimelineCount()
    {
        $select = $this->getCountSelect();
        return $this->getCountFromSelect($select);
    }

    public function fetchUserTimeline($user, $offset = 0, $limit = 20)
    {
        $select = $this->getSql()->select();

        $where  = new Where();
        $where->equalTo('username', $user);
        $select->where($where);

        $select->offset($offset)
               ->limit($limit)
               ->order('timestamp DESC');
        return $this->getPeepsFromSelect($select);
    }

    public function fetchUserTimelineCount($user)
    {
        $select = $this->getCountSelect();
        $where  = new Where();
        $where->equalTo('username', $user);
        $select->where($where);
        return $this->getCountFromSelect($select);
    }

    public function fetchPeep($identifier)
    {
        $rowset = $this->select(array('identifier' => $identifier));
        $peep   = $rowset->current();
        if (!$peep) {
            return false;
        }
        return $peep;
    }

    public function insertPeep(PeepEntity $peep)
    {
        $data = $peep->getArrayCopy();
        if (false !== ($peep = $this->fetchPeep($data['identifier']))) {
            throw new \DomainException(sprintf(
                'Unable to create peep; peep with that identifier ("%s") already exists',
                $data['identifier']
            ));
        }
        $this->insert($data);
    }

    protected function getPeepsFromSelect($select)
    {
        $resultset = $this->selectWith($select);
        $peeps     = new SplObjectStorage();
        foreach ($resultset as $peep) {
            $peeps->attach($peep);
        }
        return $peeps;
    }

    protected function getCountSelect()
    {
        $select = $this->getSql()->select();
        $select->columns(array('peeps' => new Expression('COUNT(identifier)')));
        return $select;
    }

    protected function getCountFromSelect($select)
    {
        $this->resultSetPrototype = $this->countPrototype;
        $resultset = $this->selectWith($select);
        if (!count($resultset)) {
            throw new \DomainException('Unable to determine timeline count!');
        }
        $row = $resultset->current();
        $this->resultSetPrototype = $this->peepsPrototype;
        return $row['peeps'];
    }
}
