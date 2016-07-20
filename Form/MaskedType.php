<?php

namespace NS\ColorAdminBundle\Form;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormView;
use \Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of KnobType
 *
 * @author mark
 */
class MaskedType extends AbstractType
{
    private $defaults;

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $this->defaults = array(
            'mask'            => false,//ex: "99/99/9999", "(999) 999-9999", "99-999-9999-99"; ? = optional, ex: "(999) 999-9999? x999" for optional phone extension
            'placeholder'     => '_', //Placeholder to use for masked characters
            'definitions'     => false, //Custom mask definitions. Array.
            /*
             * 'defintions' => array(
             *      'h' => '[A-Fa-f0-9]' //"h" in mask will allow only hex characters: "99-hh-9999" = number number hex hex number number number number
             *      '~' => '[+-]' //"~" char represents a plus or minus sign: Temp: "~99" = +15, -22
             * )
             */
        );

        $resolver->setDefaults(
            array_merge(
                $this->defaults,
                array('attr' => array('class' => 'nsMasked'))
            )
        );
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $options['definitions'] = json_encode($options['definitions']);

        foreach($this->defaults as $opt => $val)
            $view->vars['attr']['data-'.$opt] = $options[$opt];
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'masked';
    }
}
