<?php


namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;

class PasswordUpdate
{

    private $oldPassword;

    /**
     * @Assert\Length(
     *     min=8,
     *     minMessage="Votre mot de passe doit faire au moins 8 caractères."
     * )
     */
    private $newPassword;

    /**
     * @Assert\EqualTo(
     *     propertyPath="newPassword",
     *     message="Les mots de passe ne sont pas identiques"
     * )
     */
    private $confirmPassword;


    public function getOldPassword()
    {
        return $this->oldPassword;
    }


    public function setOldPassword($oldPassword): void
    {
        $this->oldPassword = $oldPassword;
    }


    public function getNewPassword()
    {
        return $this->newPassword;
    }


    public function setNewPassword($newPassword): void
    {
        $this->newPassword = $newPassword;
    }


    public function getConfirmPassword()
    {
        return $this->confirmPassword;
    }


    public function setConfirmPassword($confirmPassword): void
    {
        $this->confirmPassword = $confirmPassword;
    }


}