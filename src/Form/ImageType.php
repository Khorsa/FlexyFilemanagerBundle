<?php

namespace flexycms\FlexyFilemanagerBundle\Form;

use flexycms\FlexyFilemanagerBundle\Entity\FlexyFile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', FileType::class, array(
                'label' => 'Изображение',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Выберите изображение JPEG или PNG',
                    ])
                ],
            ))
            ->add('alt', TextType::class, array(
                'label' => 'Альтернативная надпись (тэг Alt)',
                'mapped' => true,
                'required' => false,
            ))
            ->add('title', TextType::class, array(
                'label' => 'Заголовок (тэг Title)',
                'mapped' => true,
                'required' => false,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FlexyFile::class,
        ]);
    }

}
