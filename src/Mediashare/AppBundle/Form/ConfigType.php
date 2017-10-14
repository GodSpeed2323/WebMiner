<?php

namespace Mediashare\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConfigType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$options['edit']) {
        $builder
            ->add('name', 'text', array(
                "label" => "Server Name :",
                'attr' => array('class' => 'form-control'),
                'label_attr' => array('class' => 'col-lg-3 control-label'),
            ));
        }
        $builder
            ->add('description', 'textarea', array(
                "label" => "Description :",
                'required' => false,
                'attr' => array('class' => ''),
                'label_attr' => array('class' => 'col-lg-3 control-label'),
            ))
            ->add('link', 'text', array(
                "label" => "External Link :",
                'required' => false,
                'attr' => array('class' => 'form-control'),
                'label_attr' => array('class' => 'col-lg-3 control-label'),
            ))
            ->add('publicKey', 'text', array(
                "label" => "Public Key CoinHive :",
                'attr' => array('class' => 'form-control'),
                'label_attr' => array('class' => 'col-lg-3 control-label'),
            ))
            ->add('privateKey', 'text', array(
                "label" => "Private Key CoinHive :",
                'attr' => array('class' => 'form-control'),
                'label_attr' => array('class' => 'col-lg-3 control-label'),
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Mediashare\AppBundle\Entity\Config',
            'edit' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mediashare_appbundle_config';
    }
}
