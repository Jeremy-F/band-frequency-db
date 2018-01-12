<?php
namespace Jeremyfornarino\Band;
use Jeremyfornarino\Ksac\SignalAnalyzer\SignalAnalyzer;
use Jeremyfornarino\Ksac\SignalAnalyzer\SoftkeyButton\TraceDetectorType;
use Jeremyfornarino\Ksac\SignalAnalyzer\SoftkeyButton\Unit;

class BandFrequency{

    /** @var String */
    private $name;
    /** @var int */
    private $startFrequency;
    /** @var int */
    private $stopFrequency;
    /** @var int */
    private $rbw;
    /** @var int */
    private $points;

    /** @var int */
    private $frequencyUnit;
    /** @var int */
    private $rbwUnit;

    /** @var int */
    private $bandDivider;

    /**
     * Band constructor.
     * @param String $name
     * @param int $startFrequency
     * @param int $stopFrequency
     * @param int $rbw
     * @param int $points
     * @param int $frequencyUnit
     * @param int $rbwUnit
     * @param int $bandDivider
     */
    public function __construct(String $name, int $startFrequency, int $stopFrequency, int $rbw, int $points, int $frequencyUnit = Unit::MHz, int $rbwUnit = Unit::KHz, int $bandDivider = 1){
        $this->name = $name;
        $this->startFrequency = $startFrequency;
        $this->stopFrequency = $stopFrequency;
        $this->rbw = $rbw;
        $this->points = $points;
        $this->frequencyUnit = $frequencyUnit;
        $this->rbwUnit = $rbwUnit;
        $this->bandDivider = $bandDivider;
    }
    public static function constructFromObject($object):BandFrequency{
        if(
            property_exists($object, "name") &&
            property_exists($object, "startFrequency") &&
            property_exists($object, "stopFrequency") &&
            property_exists($object, "rbw") &&
            property_exists($object, "points") &&
            property_exists($object, "frequencyUnit") &&
            property_exists($object, "rbwUnit") &&
            property_exists($object, "bandDivider")
        ){
            return new BandFrequency($object->name, intval($object->startFrequency),intval($object->stopFrequency), intval($object->rbw), intval($object->points),
                            intval($object->frequencyUnit), intval($object->rbwUnit), intval($object->bandDivider));
        }return null;
    }

    /**
     * @param SignalAnalyzer $signalAnalyzer
     * @throws \Exception
     */
    public function run(SignalAnalyzer $signalAnalyzer){
        $signalAnalyzer->updateFrequency(
            $this->getStartFrequency(),
            $this->getStopFrequency(),
            $this->getFrequencyUnit()
        );
        $signalAnalyzer->updateRBW($this->getRbw(), $this->getRbwUnit());
        $signalAnalyzer->updatePointsNumber($this->getPoints());
    }
    public function __toString() : string {
        $objectString = "Band : [";
        foreach($this->__toArray() as $key => $value){
            $objectString .= $key." : ".$value." | ";
        }
        $objectString = substr($objectString, 0, -3)."]";
        return $objectString;
    }
    public function __toArray() : array {
        return [
            "name" => $this->getName(),
            "startFrequency" => $this->getStartFrequency(),
            "stopFrequency" => $this->getStopFrequency(),
            "rbw" => $this->getRbw(),
            "points" => $this->getPoints(),
            "frequencyUnit" => $this->getFrequencyUnit(),
            "rbwUnit" => $this->getRbwUnit(),
            "bandDivider" => $this->getBandDivider()
        ];
    }
    public function __toJSON() : string{
        return json_encode($this->__toArray());
    }

    /**
     * @return String
     */
    public function getName(): String
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getStartFrequency(): int
    {
        return $this->startFrequency;
    }

    /**
     * @return int
     */
    public function getStopFrequency(): int
    {
        return $this->stopFrequency;
    }

    /**
     * @return int
     */
    public function getRbw(): int
    {
        return $this->rbw;
    }

    /**
     * @return int
     */
    public function getPoints(): int
    {
        return $this->points;
    }

    /**
     * @return int
     */
    public function getFrequencyUnit(): int
    {
        return $this->frequencyUnit;
    }

    /**
     * @return int
     */
    public function getRbwUnit(): int
    {
        return $this->rbwUnit;
    }

    /**
     * @return int
     */
    public function getBandDivider(): int
    {
        return $this->bandDivider;
    }

    /**
     * @param null $currentBandFrequency
     * @return bool
     */
    public function equal($currentBandFrequency = null) : bool {
        if($this === $currentBandFrequency) return true;
        if(is_null($currentBandFrequency)) return false;
        if(!($currentBandFrequency instanceof BandFrequency)) return false;
        /** @var BandFrequency $currentBandFrequency */
        return (
            $this->getName() === $currentBandFrequency->getName() &&
            $this->getStartFrequency() === $currentBandFrequency->getStartFrequency() &&
            $this->getStopFrequency() === $currentBandFrequency->getStopFrequency() &&
            $this->getRbw() === $currentBandFrequency->getRbw() &&
            $this->getPoints() === $currentBandFrequency->getPoints() &&
            $this->getFrequencyUnit() === $currentBandFrequency->getFrequencyUnit() &&
            $this->getRbwUnit() === $currentBandFrequency->getRbwUnit() &&
            $this->getBandDivider() === $currentBandFrequency->getBandDivider()
        );
    }
}