<?php

namespace NS\ColorAdminBundle\Form\Extension;

use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class VichFileExtension extends FileExtension
{
    public static function getExtendedTypes(): iterable
    {
        return [VichFileType::class, VichImageType::class];
    }
}

