<?php
/**
 * Created by IntelliJ IDEA.
 * User: jeremyfornarino
 * Date: 11/01/2018
 * Time: 18:21
 */

namespace Jeremyfornarino\Band;

use Jeremyfornarino\Ksac\DataAnalyzer\Column\ColumnDefaultValue;
use Jeremyfornarino\Ksac\DataAnalyzer\DataAnalyzerCSV;
use Jeremyfornarino\Ksac\SignalAnalyzer\SignalAnalyzer;
use Jeremyfornarino\Ksac\SignalAnalyzer\SoftkeyButton\TraceDetectorType;

class BandFrequencyDB
{
    /** @var string */
    private $dbFilePath;

    private $bandsFrequency;

    /**
     * BandFrequencyDB constructor.
     * @param string $dbFilePath
     */
    public function __construct(string $dbFilePath){
        $this->dbFilePath = $dbFilePath;
        $this->bandsFrequency = [];
    }

    public function loadDatabase(){
        if (!is_file($this->getDbFilePath())) file_put_contents($this->getDbFilePath(), json_encode([]));
        $currentDatabase = json_decode(file_get_contents($this->getDbFilePath()));
        if(!is_null($currentDatabase) && is_array($currentDatabase)){
            foreach($currentDatabase AS $bandObject){
                $this->bandsFrequency[] = BandFrequency::constructFromObject($bandObject);
            }
            return true;
        }return false;
    }

    /**
     * @return string
     */
    public function __toString() : string {
        $objectString = "[BandFrequencyDB : [";
        /** @var BandFrequency $bandFrequency */
        foreach($this->getBandsFrequency() AS $bandFrequency){
            $objectString .= $bandFrequency->__toString()." | ";
        }
        $objectString = substr($objectString, 0, -3)."]";
        return $objectString;
    }

    /**
     * @param BandFrequency $band
     * @return BandFrequency
     */
    public function addBand(BandFrequency $band) : BandFrequency{
        $this->bandsFrequency[] = $band;
        return $band;
    }

    /**
     * @param BandFrequency $bandFrequency
     * @return BandFrequency
     */
    public function removeBand(BandFrequency $bandFrequency) : bool {
        /** @var BandFrequency $currentBandFrequency */
        foreach($this->bandsFrequency AS $i => $currentBandFrequency){
            if($bandFrequency->equal($currentBandFrequency)){
                unset($this->bandsFrequency[$i]);
                return true;
            }
        }return false;
    }

    /**
     * @return string
     */
    public function getDbFilePath(): string{
        return $this->dbFilePath;
    }

    /**
     * @return mixed
     */
    public function getBandsFrequency(){
        return $this->bandsFrequency;
    }

    public function equal($object = null){
        if(is_null($object)) return false;
        if($this === $object) return true;
        if(!($object instanceof BandFrequencyDB)) return false;
        /** @var BandFrequencyDB $object */
        if($this->getDbFilePath() != $object->getDbFilePath()) return false;
        if(count($this->getBandsFrequency()) != count($object->getBandsFrequency())) return false;
        /**
         * @var int $key
         * @var BandFrequency $band
         */
        foreach ($this->getBandsFrequency() AS $key => $band){
            $currentBandFrequency = $object->getBandsFrequency()[$key];
            if(!$band->equal($currentBandFrequency)){
                return false;
            }
        }
        return true;
    }
    public function getBandsFrequencyArray() : array {
        $bandsFrequencyArray = [];
        /** @var BandFrequency $bandFrequency */
        foreach ($this->getBandsFrequency() AS $bandFrequency){
            $bandsFrequencyArray[] = $bandFrequency->__toArray();
        }
        return $bandsFrequencyArray;
    }
    public function saveDatabase(){
        return file_put_contents($this->getDbFilePath(), json_encode($this->getBandsFrequencyArray()));
    }


    /**
     * @param SignalAnalyzer $signalAnalyzer
     * @param string $dirPath
     * @throws \Exception
     */
    public function run(SignalAnalyzer $signalAnalyzer, $dirPath = "./"){
        if(!is_dir($dirPath)) mkdir($dirPath);

        $columnDefaultValue = new ColumnDefaultValue(time(), "timestamp");
        $dataAnalyzerCSV = new DataAnalyzerCSV($signalAnalyzer, [$columnDefaultValue]);

        $signalAnalyzer->restoreModeSetupDefaults();
        $signalAnalyzer->updateTraceType(TraceDetectorType::traceAverage);
        $signalAnalyzer->updateAverageHoldNumber(1);
        /** @var BandFrequency $bandsFrequency */
        foreach ($this->getBandsFrequency() AS $bandsFrequency){
            $bandsFrequency->run($signalAnalyzer);
            $bandDirPath = $dirPath.urlencode($bandsFrequency->getName());
            if(!is_dir($bandDirPath))mkdir($bandDirPath);

            sleep(1);

            $columnDefaultValue->setDefaultValue(time());
            $dataAnalyzerCSV->saveDataIn($bandDirPath."/".date("Y-m-d_H\hi\ms\s").".csv");
        }
    }
}