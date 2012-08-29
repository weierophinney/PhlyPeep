<?php

namespace PhlyPeep\Model;

use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class PeepService
{
    protected $pageSize = 20;
    protected $table;

    public function __construct(PeepTable $table)
    {
        $this->table = $table;
    }

    /**
     * Set value for page size
     *
     * @param  int $pageSize
     * @return PeepService
     */
    public function setPageSize($pageSize)
    {
        $this->pageSize = (int) $pageSize;
        return $this;
    }
    
    /**
     * Get value for page size
     *
     * @return int
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    public function fetchTimeline($page = 1)
    {
        $paginator = $this->getTimelinePaginator($page);
        return $paginator;
    }

    public function fetchUserTimeline($user, $page = 1)
    {
        $paginator = $this->getUserTimelinePaginator($user, $page);
        return $paginator;
    }

    public function fetchPeep($identifier)
    {
        return $this->table->fetchPeep($identifier);
    }

    public function insertPeep(PeepEntity $peep)
    {
        $this->table->insertPeep($peep);
    }

    protected function getTimelinePaginator($page)
    {
        $paginator = new Paginator(new DbSelect($this->table->fetchTimeline(), $this->table->getAdapter(), $this->table->getResultSetPrototype()));
        $paginator->setCurrentPageNumber($page);
        return $paginator;
    }

    protected function getUserTimelinePaginator($user, $page)
    {
        $paginator = new Paginator(new DbSelect($this->table->fetchUserTimeline($user), $this->table->getAdapter(), $this->table->getResultSetPrototype()));
        $paginator->setCurrentPageNumber($page);
        return $paginator;
    }
}
