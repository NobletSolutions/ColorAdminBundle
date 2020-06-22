<?php

namespace NS\ColorAdminBundle\Form\Mapper;

class DatetimeMapper implements \Symfony\Component\Form\DataMapperInterface
{
    /**
     * @param \DateTime|null    $viewData
     * @param iterable $forms
     */
    public function mapDataToForms($viewData, iterable $forms)
    {
        if($viewData !== null)
        {
            $forms = iterator_to_array($forms);
            $forms['date']->setData($viewData);
            $forms['time']->setData($viewData);
        }
    }

    public function mapFormsToData(iterable $forms, &$viewData)
    {
        $forms = iterator_to_array($forms);
        $viewData = \DateTime::createFromFormat('Y-m-d H:i:s', $forms['date']->getData()->format('Y-m-d').' '.$forms['time']->getData()->format('H:i:s'));
    }
}
