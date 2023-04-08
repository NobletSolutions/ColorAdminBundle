<?php declare(strict_types=1);

namespace NS\ColorAdminBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddFormCollectionExtension extends AbstractTypeExtension
{
    public static function getExtendedTypes(): iterable
    {
        return [CollectionType::class];
    }
    public function getExtendedType(): string
    {
        return CollectionType::class;
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $attrs = $view->vars['attr'] ?? [];
        if (isset($options['add_form_collection'])) {
            $view->vars['target_collection'] = $options['add_form_collection'];
            $attrs['data-collection'] = $options['add_form_collection'];
        }

        if (isset($options['add_form_insert_position'])) {
            $attrs['data-insert-position'] = $options['add_form_insert_position'];
        }

        if (isset($options['add_form_scroll_to_view'])) {
            $attrs['data-scroll-to-view'] = true;
        }

        $view->vars['attr'] = $attrs;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined([
            'add_form_collection',
            'add_form_insert_position',
            'add_form_scroll_to_view'
        ]);

        $resolver->setAllowedTypes('add_form_collection', 'string');
        $resolver->setAllowedTypes('add_form_scroll_to_view', 'bool');
        $resolver->setAllowedTypes('add_form_insert_position', 'string');
        $resolver->setAllowedValues('add_form_insert_position', ['append', 'prepend']);
    }
}
