<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class LandingPageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Your name:',
                'constraints' => [new NotBlank([
                    'message' => 'Please provide a name',
                ])]
            ])
            ->add('email', TextType::class, [
                'label' => 'Your email:',
                'constraints' => [
                    new Email([
                        'message' => 'Please provide a valid email address',
                    ]),
                    new NotBlank([
                        'message' => 'Please provide a valid email address',
                    ])
                ]
            ])
            ->add('optin', CheckboxType::class, [
                'label' => 'Subscribe to Blackfire\'s mailing list',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'optin' => false,
        ]);
    }
}
