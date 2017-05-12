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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Coupon generate instruction type.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class CouponGenerateInstructionType extends AbstractType
{
    protected $dataClass;
    protected $validationGroups;

    public function __construct($dataClass, array $validationGroups)
    {
        $this->dataClass = $dataClass;
        $this->validationGroups = $validationGroups;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount', IntegerType::class, array(
                'label' => 'sylius.form.coupon_generate_instruction.amount'
            ))
            ->add('usageLimit', IntegerType::class, array(
                'label' => 'sylius.form.coupon_generate_instruction.usage_limit'
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'data_class'        => $this->dataClass,
                'validation_groups' => $this->validationGroups,
            ))
        ;
    }

    public function getBlockPrefix()
    {
        return 'sylius_promotion_coupon_generate_instruction';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
