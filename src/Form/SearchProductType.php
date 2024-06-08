<?php
namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Tags;

class SearchProductType extends AbstractType
{
    // This function is used to build the form for the SearchProductType form.

    public function buildForm(FormBuilderInterface $builder)
    {
        $builder
            // Add the 'query' field to the form
            ->add('query', SearchType::class, [
                'label' => 'Buscar productos', // Label for the field
                'required' => false // Field is not required
            ])
            // Add the 'tags' field to the form
            ->add('tags', EntityType::class, [
                'class' => Tags::class, // Entity class for the select field
                'choice_label' => 'name', // Field of the entity that will be used as the label for each option
                'multiple' => true, // Allow multiple selections
                'expanded' => true, // Display as a list of checkboxes
                'required' => false // Field is not required
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method' => 'GET',
        ]);
    }
}

