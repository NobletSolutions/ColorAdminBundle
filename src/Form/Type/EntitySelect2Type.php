<?php

namespace NS\ColorAdminBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntitySelect2Type extends AbstractType
{
    use Select2Input {
        configureOptions as _configureOptions;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (isset($options['class'], $options['transformer']) && $options['transformer'] !== false) {
            $builder->addViewTransformer($options['transformer']);
        }

        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'preSetData']);
        $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'preSubmit'], 30);
    }

    // Only pre-populate the existing field, not the entire entity list
    public function preSetData(FormEvent $event): void
    {
        $form   = $event->getForm();
        $data   = $event->getData();
        $config = $form->getConfig();

        if ($data && ($config->getOption('url') || $config->getOption('route'))) {
            /** @var QueryBuilder $qb */
            $qb   = $event->getForm()->getConfig()->getOption('query_builder');
            $data = is_iterable($data) ? $data : [$data];

            if ($qb) {
                $aliases = $qb->getRootAliases();
                if (!isset($aliases[0])) {
                    throw new \RuntimeException('No alias was set before invoking getRootAlias().');
                }
                $qb->andWhere($aliases[0] . '.id IN (:es2_ids)')->setParameter('es2_ids', $data);
            }
        }
    }

    //Overwrite the querybuilder so the submitted selection is a valid choice.  Persistence code must ensure the user is allowed to select this option (same as the old autocompleter)
    public function preSubmit(FormEvent $event): void
    {
        $data   = $event->getData();
        $form   = $event->getForm();
        $config = $form->getConfig();

        if ($data && ($config->getOption('url') || $config->getOption('route'))) {
            $data = is_iterable($data) ? $data : [$data];
            /** @var QueryBuilder $qb */
            $qb = $form->getConfig()->getOption('query_builder');

            if (!$qb->getParameter('es2_ids')) {
                $aliases = $qb->getRootAliases();
                if (!isset($aliases[0])) {
                    throw new \RuntimeException('No alias was set before invoking getRootAlias().');
                }
                $qb->andWhere($aliases[0] . '.id IN (:es2_ids)');
            }

            $existing = $qb->getParameter('es2_ids') ? $qb->getParameter('es2_ids')->getValue() : [];

            $qb->setParameter('es2_ids', array_merge($data, $existing));
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $this->_configureOptions($resolver);

        $queryBuilderNormalizer = function (Options $options, $queryBuilder) {
            if (\is_callable($queryBuilder)) {
                $queryBuilder = $queryBuilder($options['em']->getRepository($options['class']), $options);

                if (!$queryBuilder instanceof QueryBuilder) {
                    throw new UnexpectedTypeException($queryBuilder, QueryBuilder::class);
                }
            }

            return $queryBuilder;
        };

        $resolver->setNormalizer('query_builder', $queryBuilderNormalizer);

        $resolver->setDefault('query_builder', static function (EntityRepository $er, Options $options) {
            if (!empty($options['url']) || !empty($options['route'])) {
                return $er->createQueryBuilder('e')->where("e.id IN (:es2_ids)")->setParameter('es2_ids', []);
            }

            throw new InvalidArgumentException("One of the options 'url', 'route', or 'query_builder' must be provided.");
        });
    }

    public function getParent(): string
    {
        return EntityType::class;
    }
}
