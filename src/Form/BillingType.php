<?php
namespace App\Form;

use App\Entity\BillingDetails;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class BillingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('surname', TextType::class)
            ->add('phone_number', TextType::class)
            ->add('email', EmailType::class)
            ->add('addressLine1', TextType::class)
            ->add('addressLine2', TextType::class, [
                'required' => false,
            ])
            ->add('city', TextType::class)
            ->add('state', TextType::class)
            ->add('postalCode', TextType::class)
            ->add('country', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BillingDetails::class,
        ]);
    }
}