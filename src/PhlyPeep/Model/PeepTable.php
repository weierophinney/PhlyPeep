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
        $this->peepsPrototype     = new HydratingResultSet($this->peepHydrator, $this->peepPrototype);
        $this->peepsPrototype->buffer();
        $this->resultSetPrototype = $this->peepsPrototype;
        $this->initialize();
    }

    public function fetchTimeline()
    {
        $select = $this->getSql()->select();
        $select->order('timestamp DESC');
        return $select;
    }

    public function fetchUserTimeline($user)
    {
        $select = $this->getSql()->select();

        $where  = new Where();
        $where->equalTo('username', $user);
        $select->where($where)
               ->order('timestamp DESC');
        return $select;
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
}
