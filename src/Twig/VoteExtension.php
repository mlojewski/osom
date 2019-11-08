<?php

namespace App\Twig;

use App\Service\VoteVerificator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class VoteExtension extends AbstractExtension
{
    private $voteVerificator;
    public function __construct(VoteVerificator $voteVerificator)
    {
        $this->voteVerificator = $voteVerificator;
    }
    
    public function getFunctions()
    {
       return [
           new TwigFunction('checkVote', [$this, 'verifyResolutionCreation']),
        ];
    }
    
    public function verifyResolutionCreation($id)
    {
        if ($this->voteVerificator->verifyVoting($id) === null) {
            return false;
        }
        return true;
    }
}