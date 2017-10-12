<?php

namespace Mediashare\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Mediashare\AppBundle\Entity\ConfigRepository;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add your custom field
        $builder
            ->add('username', 'text', array(
                "label" => "Nom d'utilisateur :",
                'attr' => array('class' => 'form-control'),
                'label_attr' => array('class' => 'col-lg-3 control-label'),
            ))
            ->add('serverName', 'entity', array(
                'class' => 'MediashareAppBundle:Config',
                'query_builder' => function (ConfigRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->Where('u.online = true');
                },
                "label" => "Serveur :",
                'property' => 'name',
                'required' => false,
                'placeholder' => 'Choisir',
                'attr' => array('class' => 'form-control'),
                'label_attr' => array('class' => 'col-lg-3 control-label'),

            ))
            ->add('email', 'text', array(
                "label" => "Email :",
                'attr' => array('class' => 'form-control'),
                'label_attr' => array('class' => 'col-lg-3 control-label'),
            ));
        if ($options['edit'] == true) {
            $builder
                ->add('password', 'repeated', array(
                    'type' => 'password',
                    'required' => false,
                    'invalid_message' => 'Les mots de passe ne correspondent pas',
                    'first_options' => array(
                        'label' => 'Mot de passe',
                        'attr' => array('class' => 'form-control'),
                        'label_attr' => array('class' => 'col-lg-3 control-label')
                    ),
                    'second_options' => array(
                        'label' => 'Mot de passe (validation)',
                        'attr' => array('class' => 'form-control'),
                        'label_attr' => array('class' => 'col-lg-3 control-label')
                    )
                ));
        } else {
            $builder
                ->add('password', 'repeated', array(
                    'type' => 'password',
                    'invalid_message' => 'Les mots de passe ne correspondent pas',
                    'first_options' => array(
                        'label' => 'Mot de passe',
                        'attr' => array('class' => 'form-control'),
                        'label_attr' => array('class' => 'col-lg-3 control-label')
                    ),
                    'second_options' => array(
                        'label' => 'Mot de passe (validation)',
                        'attr' => array('class' => 'form-control'),
                        'label_attr' => array('class' => 'col-lg-3 control-label')
                    )
                ));
        }

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Mediashare\UserBundle\Entity\User',
            'edit' => false,
            'roles' => array(
                'ROLE_ADMIN' => 'Admin',
                'ROLE_USER' => 'Utilisateur'
            )
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Mediashare_userbundle_user';
    }
}
