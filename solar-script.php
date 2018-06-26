<?php

class Sun
{
    /**
     * @var UnitConverter
     */
    private $unitConverter;

    /**
     * @var SkyObserver
     */
    private $skyObserver;

    /**
     * Sun constructor.
     * @param UnitConverter $unitConverter
     * @param SkyObserver $skyObserver
     */
    public function __construct(
        UnitConverter $unitConverter,
        SkyObserver $skyObserver
    )
    {
        $this->unitConverter = $unitConverter;
        $this->skyObserver = $skyObserver;
    }

    /**
     * @return float|int
     */
    public function getSunriseTime()
    {
        $day = $this->skyObserver->getDay();

        $declination = $this->unitConverter->getRadian($this->getDeclination($day));

        $latitude = $this->unitConverter->getRadian($this->skyObserver->getLatitude());

        $cos = tan($latitude) * tan($declination);

        $hourDegree = $this->unitConverter->getDegree(acos($cos));

        $time = $hourDegree * (1 / 15);

        return $time;
    }

    /**
     * @return float|int
     */
    public function getSunsetTime()
    {
        $sunsetTime = $this->getSunriseTime();

        return 12 - $sunsetTime;
    }

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

        $declination = 23.45 * sin($this->unitConverter->getRadian($orbitDegree));

        return $declination;
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

class UnitConverter
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
$skyObserver->setDay(177);
$skyObserver->setLatitude(50);

$sun = new Sun(new UnitConverter(), $skyObserver);

echo 'Sunrise time: ' . $sun->getSunriseTime() . PHP_EOL;
echo 'Sunset time: ' . $sun->getSunsetTime() . PHP_EOL;