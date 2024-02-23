<?php




class FurryPets
{
    private $sound;

    protected function fourlegs()
    {
        return "walk on all fours";
    }

    protected function makesSound($petNoise)
    {
        $this->sound = $petNoise;
        return $this->sound;
    }
}
