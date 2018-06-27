<?php

class Sun
{
    use UnitConverter;

    /**
     * Declination is calculated using Cooper formula
     *
     * @param $day
     * @return float
     */
    public function getDeclination($day)
    {
        $orbitDegree = (360 / 365) * ($day - 81);

        // turn degree in radian because php sin() take radian as argument

        $declination = 23.45 * sin($this->getRadian($orbitDegree));

        return $declination;
    }
}

class SkyEvent
{
    use UnitConverter;

    private $sun;

    private $skyObserver;

    public function __construct
    (
        Sun $sun,
        SkyObserver $skyObserver
    )
    {
        $this->sun = $sun;
        $this->skyObserver = $skyObserver;
    }

    public function getSunriseTime()
    {
        $day = $this->skyObserver->getDay();

        $declination = $this->getRadian($this->sun->getDeclination($day));

        $latitude = $this->getRadian($this->skyObserver->getLatitude());

        $cos = tan($latitude) * tan($declination);

        $hourDegree = $this->getDegree(acos($cos));

        $time = $hourDegree * (1 / 15);

        return $time;
    }

    public function getSunsetTime()
    {
        return 12 - $this->getSunriseTime();
    }
}

class SkyObserver
{
    private $day;

    private $latitude;

    /**
     * @return int
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param $day
     */
    public function setDay($day)
    {
        $this->day = $day;
    }

    /**
     * @param $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return int|float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }
}

trait UnitConverter
{
    /**
     * @param $degree
     * @return float|int
     */
    public function getRadian($degree)
    {
        $radian = ($degree * pi()) / 180;

        return $radian;
    }

    /**
     * @param $radian
     * @return float|int
     */
    public function getDegree($radian)
    {
        $degree = ($radian * 180) / pi();

        return $degree;
    }
}

$skyObserver = new SkyObserver();
$skyObserver->setDay(19);
$skyObserver->setLatitude(50);

$sun = new Sun();

$skyEvent = new SkyEvent($sun, $skyObserver);

echo 'Sunrise time: ' . $skyEvent->getSunriseTime() . PHP_EOL;
echo 'Sunset time: ' . $skyEvent->getSunsetTime() . PHP_EOL;