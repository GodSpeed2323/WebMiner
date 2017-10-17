<?php

namespace Mediashare\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TradeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amout', 'integer', array(
                "label" => "Amout :",
                'required' => true,
                'attr' => array('class' => 'form-control'),
                'label_attr' => array('class' => 'col-lg-3 control-label'),
            ))
            ->add('ticketpass', 'text', array(
                "label" => "TicketPass :",
                'required' => true,
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
            'data_class' => 'Mediashare\AppBundle\Entity\Trade'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mediashare_appbundle_trade';
    }
}
