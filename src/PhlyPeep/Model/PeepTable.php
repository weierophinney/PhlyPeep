<?php

namespace PhlyPeep\Model;

use SplObjectStorage;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\AbstractTableGateway;

class PeepTable extends AbstractTableGateway
{
    protected $peepPrototype;
    protected $table     = 'peep';
    protected $tableName = 'peep';

    public function __construct(Adapter $adapter, $tableName = 'peep')
    {
        $this->adapter            = $adapter;
        $this->table              = $this->tableName = $tableName;
        $this->peepPrototype      = new PeepEntity;
        $this->resultSetPrototype = new ResultSet;
        $this->resultSetPrototype->setReturnType(ResultSet::TYPE_ARRAY);
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
        $row    = $rowset->current();
        if (!$row) {
            return false;
        }
        $peep = $this->getPeepFromRow($row);
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
        foreach ($resultset as $row) {
            $peep = $this->getPeepFromRow($row);
            $peeps->attach($peep);
        }
        return $peeps;
    }

    protected function getPeepFromRow($row)
    {
        $peep = clone $this->peepPrototype;
        $peep->exchangeArray($row);
        return $peep;
    }

    protected function getCountSelect()
    {
        $select = $this->getSql()->select();
        $select->columns(array('peeps' => new Expression('COUNT(identifier)')));
        return $select;
    }

    protected function getCountFromSelect($select)
    {
        $resultset = $this->selectWith($select);
        if (!count($resultset)) {
            throw new \DomainException('Unable to determine timeline count!');
        }
        $row = $resultset->current();
        return $row['peeps'];
    }
}
