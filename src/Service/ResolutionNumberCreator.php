<?php

namespace App\Service;

use App\Repository\ResolutionRepository;

class ResolutionNumberCreator
{
    private $resolutionRepository;
    public function __construct(ResolutionRepository $resolutionRepository)
    {
        $this->resolutionRepository = $resolutionRepository;
    }
    
    public function createNewRepositoryNumber()
    {
        $date = new \DateTime();
        $year = $date->format('Y');
        $month = $date->format('m');
        
        if (empty($lastRepositoryNumber)) {
            return '1/'.$month.'/'.$year;
        } else {
            $lastRepositoryNumber = $this->resolutionRepository->getLatestResolution()[0];
        }
        
        $numberParts = explode("/", $lastRepositoryNumber->getNumber());
        $switcher = $this->checkSwitch($numberParts);
        if ($switcher == true) {
            return '1/'.$month.'/'.$year;
        } else {
            $number = (int)$numberParts[0]+1;
            return $number.'/'.$numberParts[1].'/'.$numberParts[2];
        }
    }
    
    public function checkSwitch(array $number): bool
    {
        $date = new \DateTime();
        $year = $date->format('Y');
        $month = $date->format('m');
        if ($number[2] === $year) {
            if ($number[1] === $month) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }
}
