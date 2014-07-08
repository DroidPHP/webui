<?php

/*
 * This file is part of the DroidPHP Web Interface.
 *
 * (c) Shushant Kumar <shushantkumar786@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DroidPHP;

class Preference {

    const IS_BOOLEAN = 0x01;
    const IS_STRING = 0x02;

    protected $xmlPath;
    protected $simpleXml;

    public function __construct($xmlFile) {
        $this->setPreference($xmlFile);
        $this->create();
    }

    public function create() {
        $this->simpleXml = new \SimpleXMLElement(@file_get_contents($this->getPreference()));
        return $this;
    }

    public function getSimpleXml() {
        return $this->simpleXml;
    }

    public function getValue($key, $flag = Preference::IS_STRING) {

        if ($flag & static::IS_STRING) {

            $child = $this->simpleXml->xpath(sprintf("//string[@name='%s']", $key));
            return (string) $child[0][0];
        }

        if ($flag & static::IS_BOOLEAN) {

            $child = $this->simpleXml->xpath(sprintf("//boolean[@name='%s']", $key));
            return (string) $child[0]['value'];
        }

        throw new Exception("Preference not found", 0x30);
    }

    public function setValue($key, $value, $flag = Preference::IS_STRING) {

        if ($flag & static::IS_STRING) {

            $child = $this->simpleXml->xpath(sprintf("//string[@name='%s']", $key));
            $child[0][0] = $value;
        } else if ($flag & static::IS_BOOLEAN) {

            $child = $this->simpleXml->xpath(sprintf("//boolean[@name='%s']", $key));
            $child[0]['value'] = (1 == $value) ? 'true' : 'false';
        } else {
            throw new Exception("Preference not found", 0x30);
        }

        return $this;
    }

    public function commit() {

        if (0 !== @file_put_contents($this->getPreference(), $this->simpleXml->asXML())) {
            return true;
        }

        return false;
    }

    /**
     * Get the current Preference location
     * 
     * @return string location of preference
     */
    public function getPreference() {
        return $this->xmlPath;
    }

    /**
     * Set the current Preference filename
     * 
     * @param string $prefFilename location to preference
     */
    public function setPreference($xmlFile) {
        $this->xmlPath = $xmlFile;
    }

}
