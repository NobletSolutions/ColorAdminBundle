<?php

namespace NS\ColorAdminBundle\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

class TimeTransformer implements DataTransformerInterface
{
    protected string $format;

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
     */
    public function transform($value): mixed
    {
        return $value ? $value->format($this->format) : null;
    }

    /**
     * @param string|null $value
     */
    public function reverseTransform($value): mixed
    {
        return $value ? \DateTime::createFromFormat('Ymd' . $this->format, date('Ymd') . $value) : null;
    }
}
