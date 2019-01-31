<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressChooserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //dd($options['data']);
        $builder->add('addresses', EntityType::class, [
            'class' => Address::class,
            'choice_label' => 'fulladdress',
            'label' => 'Delivery address : ',
            'choices' => $options['data']->getAddresses() ,
            'placeholder' => count($options['data']->getAddresses()) >= 1 ? 'Choose an address' : 'No address registered',
            'mapped' => false,
        ]);
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
