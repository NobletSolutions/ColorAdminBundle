<?php

namespace NS\ColorAdminBundle\Service;

use \IntlDateFormatter;
use Locale;

class DateFormatConverter
{
    public function getFormat(?bool $longYear = false): string
    {
        $formatter = new IntlDateFormatter(
            Locale::getDefault(),
            IntlDateFormatter::SHORT,
            IntlDateFormatter::NONE,
            'UTC',
            IntlDateFormatter::GREGORIAN,
            null
        );

        $formatter->setLenient(false);

        $pattern = $formatter->getPattern();

        if ($longYear && strpos($pattern, 'yyyy') === false) {
            $pattern = preg_replace("/y{1,}/", 'yyyy', $pattern);
        }

        if (strpos($pattern, 'MM') === false) {
            $pattern = preg_replace("/M{1,}/", 'MM', $pattern);
        }

        if (strpos($pattern, 'dd') === false) {
            $pattern = preg_replace("/d{1,}/", 'dd', $pattern);
        }

        return trim(str_replace(' ', '', $pattern), './-');
    }

    public function fromFormat(string $pattern): string
    {
        if (strpos($pattern, 'yyyy') !== false) {
            $pattern = str_replace('yyyy', 'Y', $pattern);
        }

        if (strpos($pattern, 'yy') !== false) {
            $pattern = str_replace('yy', 'y', $pattern);
        }

        if (strpos($pattern, 'MM') !== false) {
            $pattern = str_replace('MM', 'm', $pattern);
        } else {
            $pattern = str_replace('M', 'n', $pattern);
        }

        if (strpos($pattern, 'dd') !== false) {
            $pattern = str_replace('dd', 'd', $pattern);
        } else {
            $pattern = str_replace('d', 'j', $pattern);
        }

        return $pattern;
    }
}
