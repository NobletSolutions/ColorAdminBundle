<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 18/09/17
 * Time: 10:16 AM
 */

namespace NS\ColorAdminBundle\Calendar\Model;

class EventEntity implements \JsonSerializable
{
    /**
     * @var mixed Unique identifier of this event (optional).
     */
    protected $id;

    /**
     * @var string Title/label of the calendar event.
     */
    protected $title;

    /**
     * @var string URL Relative to current path.
     */
    protected $url;

    /**
     * @var string HTML color code for the bg color of the event label.
     */
    protected $bgColor;

    /**
     * @var string HTML color code for the foregorund color of the event label.
     */
    protected $fgColor;

    /**
     * @var string css class for the event label
     */
    protected $cssClass;

    /**
     * @var \DateTime DateTime object of the event start date/time.
     */
    protected $startDatetime;

    /**
     * @var \DateTime DateTime object of the event end date/time.
     */
    protected $endDatetime;

    /**
     * @var boolean Is this an all day event?
     */
    protected $allDay = false;

    /**
     * @var array Non-standard fields
     */
    protected $otherFields = array();

    /**
     * EventEntity constructor.
     * @param $title
     * @param \DateTime $startDatetime
     * @param \DateTime|null $endDatetime
     * @param bool $allDay
     */
    public function __construct($title, \DateTime $startDatetime, \DateTime $endDatetime = null, $allDay = false)
    {
        $this->title = $title;
        $this->startDatetime = $startDatetime;
        $this->setAllDay($allDay);

        if ($endDatetime === null && $this->allDay === false) {
            throw new \InvalidArgumentException("Must specify an event End DateTime if not an all day event.");
        }

        $this->endDatetime = $endDatetime;
    }

    /**
     * Convert calendar event details to an array
     *
     * @return array $event
     */
    public function jsonSerialize()
    {
        $event = [];

        if ($this->id !== null) {
            $event['id'] = $this->id;
        }

        $event['title'] = $this->title;
        $event['start'] = $this->startDatetime->format("Y-m-d\TH:i:sP");

        if ($this->url !== null) {
            $event['url'] = $this->url;
        }

        if ($this->bgColor !== null) {
            $event['backgroundColor'] = $this->bgColor;
            $event['borderColor'] = $this->bgColor;
        }

        if ($this->fgColor !== null) {
            $event['textColor'] = $this->fgColor;
        }

        if ($this->cssClass !== null) {
            $event['className'] = $this->cssClass;
        }

        if ($this->endDatetime !== null) {
            $event['end'] = $this->endDatetime->format("Y-m-d\TH:i:sP");
        }

        $event['allDay'] = $this->allDay;

        foreach ($this->otherFields as $field => $value) {
            $event[$field] = $value;
        }

        return $event;
    }

    /**
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param $color
     */
    public function setBgColor($color)
    {
        $this->bgColor = $color;
    }

    /**
     * @return string
     */
    public function getBgColor()
    {
        return $this->bgColor;
    }

    /**
     * @param $color
     */
    public function setFgColor($color)
    {
        $this->fgColor = $color;
    }

    /**
     * @return string
     */
    public function getFgColor()
    {
        return $this->fgColor;
    }

    /**
     * @param $class
     */
    public function setCssClass($class)
    {
        $this->cssClass = $class;
    }

    /**
     * @return string
     */
    public function getCssClass()
    {
        return $this->cssClass;
    }

    /**
     * @param \DateTime $start
     */
    public function setStartDatetime(\DateTime $start)
    {
        $this->startDatetime = $start;
    }

    /**
     * @return \DateTime
     */
    public function getStartDatetime()
    {
        return $this->startDatetime;
    }

    /**
     * @param \DateTime $end
     */
    public function setEndDatetime(\DateTime $end)
    {
        $this->endDatetime = $end;
    }

    public function getEndDatetime()
    {
        return $this->endDatetime;
    }

    public function setAllDay($allDay = false)
    {
        $this->allDay = (boolean) $allDay;
    }

    public function getAllDay()
    {
        return $this->allDay;
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function addField($name, $value)
    {
        $this->otherFields[$name] = $value;
    }

    /**
     * @param string $name
     */
    public function removeField($name)
    {
        if (!array_key_exists($name, $this->otherFields)) {
            return;
        }

        unset($this->otherFields[$name]);
    }

}
