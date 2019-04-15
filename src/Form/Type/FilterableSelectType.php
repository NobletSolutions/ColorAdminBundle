<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 26/03/19
 * Time: 10:41 AM
 */

namespace NS\ColorAdminBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FilterableSelectType
 * @package ColorAdminBundle\Form\Type
 *
 * This classes uses the select2 JS plugin.
 *
 * Docs: https://select2.org/
 *
 * Example AJAX request:
  {
    term : The current search term in the search box.
    q : Contains the same contents as term.
    _type: A "request type". Will usually be "query", but changes to "query:append" for paginated requests.
    page : The current page number to request. Only sent for paginated (infinite scrolling) searches.
  }
 *
 * Example AJAX response:
  {
    "results":
    [
        {
            "id": 0,
            "text": "Uncategorized Option 1"
        },
        {
            "text": "Category 1",
            "children":
            [
                {
                    "id": 1,
                    "text": "Category 1 Option 1"
                },
                {
                    "id": 2,
                    "text": "Category 1 Option 2"
                }
            ]
        },
        {
            "text": "Category 2",
            "children":
            [
                {
                    "id": 3,
                    "text": "Category 2 Option 1"
                }
            ]
        }
    ],
    "pagination":
    {
        "more": true
    }
}
 */
class FilterableSelectType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(['allow_new_value', 'data_url', 'data_type', 'minimum_input_length', 'maximum_input_length', 'maximum_selection_length']);
        $resolver->setAllowedValues('expanded', [false]);
        $resolver->setAllowedValues('allow_new_value', [true, false]);
        $resolver->setDefaults(['expanded' => false, 'allow_new_value'=>false, 'data_url'=>false, 'data_type'=>'json', 'minimum_input_length' => 0, 'maximum_input_length' => 0, 'maximum_selection_length' => 0]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr']['data-tags'] = $options['allow_new_value'];
        $view->vars['attr']['data-minimum-input-length'] = $options['minimum_input_length'];
        $view->vars['attr']['data-maximum-input-length'] = $options['maximum_input_length'];
        $view->vars['attr']['data-maximum-selection-length'] = $options['maximum_selection_length'];

        if($options['data_url'])
        {
            $view->vars['attr']['data-ajax--url'] = $options['data_url'];
            $view->vars['attr']['data-ajax--dataType'] = $options['data_type'];
        }
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
