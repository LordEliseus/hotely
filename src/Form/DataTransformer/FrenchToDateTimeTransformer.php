<?php


namespace App\Form\DataTransformer;


use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FrenchToDateTimeTransformer implements DataTransformerInterface
{

    /**
     * @inheritDoc
     */
    public function transform($value)
    {
        if ($value === null){
            return '';
        }
        return $value->format('d/m/Y');
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform($value)
    {
       if ($value === null){
            throw new TransformationFailedException("Vous devez fournir une date");
       }

       $date = \DateTime::createFromFormat('d/m/Y', $value);
       if ($date == false){
           throw new TransformationFailedException("Le format de la date n'est pas valide");
       }
       return $date;
    }
}