<?php

namespace NS\ColorAdminBundle\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

class TimeTransformer implements DataTransformerInterface
{
    /** @var string */
    protected $format;

    public function __construct(bool $meridian, bool $seconds)
    {
        $this->format = "H:i%s";
        if ($meridian) {
            $this->format = "h:i%s A";
        }

        if ($seconds) {
            $this->format = sprintf($this->format, ":s");
        }

        $this->format = sprintf($this->format, "");
    }

    /**
     * @param \DateTime|null $value
     *
     * @return string|null
     */
    public function transform($value)
    {
        return $value ? $value->format($this->format) : null;
    }

    /**
     * @param string|null $value
     *
     * @return string|null
     */
    public function reverseTransform($value)
    {
        return $value ? \DateTime::createFromFormat('Ymd' . $this->format, date('Ymd') . $value) : null;
    }
}
