<?php

namespace App\Form;

use App\Entity\Manager;
use App\Entity\Member;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('tel')
            ->add('quartier')
            ->add('nationalite')
            ->add('createdAt', null, [
                'widget' => 'single_text'
            ])
            ->add('isMember')
            ->add('memberJoinedDate')
            ->add('isBaptized')
            ->add('BaptismDate')
            ->add('hasTransport')
            ->add('transportDate')
            ->add('isInHomeCell')
            ->add('homeCellJoinDate')
            ->add('observations')
            ->add('manager', EntityType::class, [
                'class' => Manager::class,
                'choice_label' => 'id',
            ])
            ->add('admin', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Member::class,
        ]);
    }
}
