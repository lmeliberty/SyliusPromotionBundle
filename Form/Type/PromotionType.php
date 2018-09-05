<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\PromotionBundle\Form\Type;

use JMS\TranslationBundle\Annotation\Ignore;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Promotion form type.
 *
 * @author Saša Stamenković <umpirsky@gmail.com>
 */
class PromotionType extends AbstractType
{
    protected $dataClass;
    protected $validationGroups;
    protected $checkerRegistry;
    protected $actionRegistry;

    public function __construct($dataClass, array $validationGroups, ServiceRegistryInterface $checkerRegistry, ServiceRegistryInterface $actionRegistry)
    {
        $this->dataClass = $dataClass;
        $this->validationGroups = $validationGroups;
        $this->checkerRegistry = $checkerRegistry;
        $this->actionRegistry = $actionRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'sylius.form.promotion.name'
            ))
            ->add('description', TextType::class, array(
                'label' => 'sylius.form.promotion.description'
            ))
            ->add('exclusive', CheckboxType::class, array(
                'label' => 'sylius.form.promotion.exclusive'
            ))
            ->add('usageLimit', IntegerType::class, array(
                'label' => 'sylius.form.promotion.usage_limit'
            ))
            ->add('startsAt', DateType::class, array(
                'label' => 'sylius.form.promotion.starts_at',
                'empty_value' => /** @Ignore */ array('year' => '-', 'month' => '-', 'day' => '-')
            ))
            ->add('endsAt', DateType::class, array(
                'label' => 'sylius.form.promotion.ends_at',
                'empty_value' => /** @Ignore */ array('year' => '-', 'month' => '-', 'day' => '-')
            ))
            ->add('couponBased', CheckboxType::class, array(
                'label' => 'sylius.form.promotion.coupon_based',
                'required' => false
            ))
            ->add('rules', CollectionType::class, array(
                'type'         => 'sylius_promotion_rule',
                'allow_add'    => true,
                'by_reference' => false,
                'label'        => 'sylius.form.promotion.rules'
            ))
            ->add('actions', CollectionType::class, array(
                'type'         => 'sylius_promotion_action',
                'allow_add'    => true,
                'by_reference' => false,
                'label'        => 'sylius.form.promotion.actions'
            ))
        ;

        $prototypes = array();
        $prototypes['rules'] = array();

        foreach ($this->checkerRegistry->all() as $type => $checker) {
            $prototypes['rules'][$type] = $builder->create('__name__', $checker->getConfigurationFormType())->getForm();
        }

        $prototypes['actions'] = array();

        foreach ($this->actionRegistry->all() as $type => $action) {
            $prototypes['actions'][$type] = $builder->create('__name__', $action->getConfigurationFormType())->getForm();
        }

        $builder->setAttribute('prototypes', $prototypes);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['prototypes'] = array();

        foreach ($form->getConfig()->getAttribute('prototypes') as $group => $prototypes) {
            foreach ($prototypes as $type => $prototype) {
                $view->vars['prototypes'][$group.'_'.$type] = $prototype->createView($view);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'data_class'        => $this->dataClass,
                'validation_groups' => $this->validationGroups,
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sylius_promotion';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
