<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 18/09/17
 * Time: 10:14 AM
 */

namespace NS\ColorAdminBundle\Calendar\Event;

use Doctrine\Common\Collections\ArrayCollection;
use NS\ColorAdminBundle\Calendar\Model\EventEntity;
use Symfony\Component\EventDispatcher\Event;

class CalendarEvent extends Event
{
    const CONFIGURE = 'calendar.load_events';

    /** @var \DateTime */
    private $startDateTime;

    /** @var \DateTime */
    private $endDateTime;

    /** @var EventEntity[] */
    private $events;

    /**
     * CalendarEvent constructor.
     * @param \DateTime $startDateTime
     * @param \DateTime $endDateTime
     */
    public function __construct(\DateTime $startDateTime, \DateTime $endDateTime)
    {
        $this->startDateTime = $startDateTime;
        $this->endDateTime = $endDateTime;
        $this->events = new ArrayCollection();
    }

    /**
     * @return \DateTime
     */
    public function getStartDateTime()
    {
        return $this->startDateTime;
    }

    /**
     * @return \DateTime
     */
    public function getEndDateTime()
    {
        return $this->endDateTime;
    }

    /**
     * @return EventEntity[]
     */
    public function getEvents()
    {
        return $this->events->toArray();
    }

    /**
     * @param EventEntity $event
     */
    public function addEvent(EventEntity $event)
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
        }
    }

}
