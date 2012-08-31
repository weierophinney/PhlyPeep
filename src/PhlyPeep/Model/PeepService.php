<?php

namespace PhlyPeep\Model;

use Zend\Paginator\Adapter\DbSelect;

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
        $paginator = $this->table->fetchTimeline();
        $paginator->setItemCountPerPage($this->getPageSize());
        $paginator->setCurrentPageNumber($page);
        return $paginator;
    }

    public function fetchUserTimeline($user, $page = 1)
    {
        $paginator = $this->table->fetchUserTimeline($user);
        $paginator->setItemCountPerPage($this->getPageSize());
        $paginator->setCurrentPageNumber($page);
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
}
