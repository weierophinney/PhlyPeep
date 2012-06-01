<?php

namespace PhlyPeep\Model;

use Zend\Paginator\Adapter\AdapterInterface as PaginatorAdapter;

class PeepPaginator implements PaginatorAdapter
{
    const TYPE_ALL  = 'all';
    const TYPE_USER = 'user';

    protected $table;
    protected $type;
    protected $user;

    public function __construct(PeepTable $table, $timelineType = self::TYPE_ALL, $user = null)
    {
        $this->table = $table;
        $this->timelineType = in_array($timelineType, array(self::TYPE_ALL, self::TYPE_USER))
                            ? $timelineType
                            : self::TYPE_ALL;
        if ($this->timelineType == self::TYPE_USER && !$user) {
            throw new \DomainException('TYPE_USER specified for paginator, but no user provided');
        }
        $this->user = $user;
    }

    public function count()
    {
        switch ($this->timelineType) {
            case self::TYPE_ALL:
                return $this->table->fetchTimelineCount();
            case self::TYPE_USER:
                return $this->table->fetchUserTimelineCount($this->user);
        }
    }

    public function getItems($offset, $itemCountPerPage)
    {
        switch ($this->timelineType) {
            case self::TYPE_ALL:
                return $this->table->fetchTimeline($offset, $itemCountPerPage);
            case self::TYPE_USER:
                return $this->table->fetchUserTimeline($this->user, $offset, $itemCountPerPage);
        }
    }
}
