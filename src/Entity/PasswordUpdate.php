<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class PasswordUpdate
{

    /**
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     */
    private $actualPassword;

    /**
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     * @Assert\NotBlank
     * @Assert\NotCompromisedPassword(message="Votre mot de passe est compromis merci de le modifier.")
     * @Assert\Regex(pattern="/^(?=.*[A-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$/",match=true,message="Sécurité du mot de passe incorrect. Minimum 8 caractères, 1 chiffre, 1 majuscule, 1 caractère spécial")
     */
    private $newPassword;

    /**
     * @Assert\EqualTo(propertyPath="newPassword", message="Le mot de passe doit être identique")
     * 
     */
    private $confirmPassword;

    /**
     * Get the value of actualPassword
     */
    public function getActualPassword()
    {
        return $this->actualPassword;
    }

    public function setActualPassword(string $actualPassword): self
    {
        $this->actualPassword = $actualPassword;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }
}
