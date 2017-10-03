<?php

namespace Mediashare\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', 'text', array(
                "label" => "Nom : * "
            ))
            ->add('firstname', 'text', array(
                "label" => "Prénom :",
                "required" => false
            ))
            ->add('phone', 'text', array(
                "label" => "Téléphone :",
                "required" => false
            ))
            ->add('email', 'text', array(
                "label" => "Email : * "
            ))
            ->add('address', 'text', array(
                "label" => "Adresse :",
                "required" => false
            ))
            ->add('zipCode', 'text', array(
                "label" => "Code postal :",
                "required" => false
            ))
            ->add('city', 'text', array(
                "label" => "Ville :",
                "required" => false
            ))
            ->add('message', 'textarea', array(
                "label" => "Message : * "
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Mediashare\AppBundle\Entity\Contact'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Mediashare_appbundle_contact';
    }
}
