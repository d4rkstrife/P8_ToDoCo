<?php

namespace App\Security\Voter;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoter extends Voter
{
    public function __construct(private Security $security)
    {
    }

    public const DELETE = 'POST_DELETE';
    public const VIEW = 'POST_VIEW';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::DELETE])
            && $subject instanceof \App\Entity\Task;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {

            case self::DELETE:

                if ($user === $subject->getUser()) {
                    return true;
                }
                //dd($subject->getUser(), $this->security->isGranted('ROLE_ADMIN'),$subject->getUser() === null && !$this->security->isGranted('ROLE_ADMIN') );
                if ($subject->getUser() === null && $this->security->isGranted('ROLE_ADMIN')) {
                    return true;
                }
                return false;
        }

        return false;
    }
}
