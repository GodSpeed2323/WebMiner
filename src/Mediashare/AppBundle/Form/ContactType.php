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
                "label" => "Name : * ",
                'required' => true,
                'attr' => array('class' => 'form-control'),
                'label_attr' => array('class' => 'col-lg-2 control-label'),
            ))
            ->add('email', 'text', array(
                "label" => "Email : * ",
                'required' => true,
                'attr' => array('class' => 'form-control'),
                'label_attr' => array('class' => 'col-lg-2 control-label'),
            ))
            ->add('message', 'textarea', array(
                "label" => "Message : * ",
                'required' => true,
                'attr' => array('class' => ''),
                'label_attr' => array('class' => 'col-lg-2 control-label'),
            ))
            ;
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
