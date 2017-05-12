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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Promotion rule choice type.
 *
 * @author Saša Stamenković <umpirsky@gmail.com>
 */
class RuleChoiceType extends AbstractType
{
    protected $rules;

    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'choices' => $this->rules
            ))
        ;
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix()
    {
        return 'sylius_promotion_rule_choice';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
