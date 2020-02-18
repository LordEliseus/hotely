<?php

namespace App\Form;

use App\Entity\Booking;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends ApplicationType
{
    /**
     * @var FrenchToDateTimeTransformer
     */
    private $frenchToDateTimeTransformer;

    /**
     * BookingType constructor.
     * @param FrenchToDateTimeTransformer $frenchToDateTimeTransformer
     */
    public function __construct(FrenchToDateTimeTransformer $frenchToDateTimeTransformer)
    {
        $this->frenchToDateTimeTransformer = $frenchToDateTimeTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', TextType::class, $this->getConfiguration("Date arrivée", "La date à laquelle vous comptez arriver"))
            ->add('endDate', TextType::class, $this->getConfiguration("Date de départ", "La data à laquelle vous quittez les lieux"))
            ->add('comment', TextareaType::class, $this->getConfiguration(false, "Si vous avez un commentaire, n'hésitez pas à nous faire part !",[
                'required' => false
            ]));

        $builder->get('startDate')
            ->addModelTransformer($this->frenchToDateTimeTransformer);

        $builder->get('endDate')
            ->addModelTransformer($this->frenchToDateTimeTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
            'validation_groups' => [
                'Default',
                'front'
            ]
        ]);
    }
}
