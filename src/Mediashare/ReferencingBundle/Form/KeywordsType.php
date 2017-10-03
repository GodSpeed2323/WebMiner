<?php

namespace Mediashare\ReferencingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class KeywordsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('keywords', 'textarea', array(
                'label' => 'Key words : ',
                'attr' => array('class' => 'form-control counter'),
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
            'data_class' => 'Mediashare\ReferencingBundle\Entity\Keywords'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Mediashare_referencingbundle_keywords';
    }
}
