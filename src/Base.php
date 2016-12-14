<?php

/*
 * The MIT License (MIT)
 * 
 * Copyright (c) 2016 Kawaguchi Kazuhiro
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace WeightedRandom;

abstract class Base
{

    /**
     *
     * @var boolean
     */
    private static $IS_SEED_STRONG = false;

    /**
     *
     * @var boolean
     */
    private static $IS_SEED_INITIALIZED = false;

    /**
     *
     * @var array
     */
    protected $weights = array();

    /**
     * 
     * @param boolean $useOpenssl
     */
    public function __construct($useOpenssl = true)
    {
        if ($useOpenssl !== false) {
            static::initMtRandSeedOpenSsl();
        }
    }

    /**
     * 
     * @return void
     */
    public static function initMtRandSeedOpenSsl()
    {
        if (self::$IS_SEED_INITIALIZED === true) {
            return;
        }

        self::$IS_SEED_INITIALIZED = true;
        if (!function_exists('openssl_random_pseudo_bytes')) {
            return;
        }

        mt_srand(hexdec(bin2hex(openssl_random_pseudo_bytes(4, self::$IS_SEED_STRONG))));
    }

    /**
     * 
     * @return boolean
     */
    public static function isMtRandSeedStrong()
    {
        return (self::$IS_SEED_STRONG === true);
    }

    /**
     * 
     * @param array $weights
     * @throws \InvalidArgumentException
     */
    public function setWeights(array $weights)
    {
        $this->weights = (array)array_filter($weights, function ($weight) {
            return (is_numeric($weight) && (real)$weight >= 0);
        });
        if (count($weights) < count($this->weights)) {
            throw new \InvalidArgumentException();
        }

        $this->weights = $weights;
    }

    /**
     * 
     * @return array
     */
    public function getWeights()
    {
        return (array)$this->weights;
    }

    /**
     * 
     * @param array $key
     * @return string
     */
    public function getWeightByKey($key)
    {
        if (isset($this->weights[$key])) {
            return $this->weights[$key];
        }
    }

    /**
     * 
     * @param array $keys
     * @return array
     */
    public function getWeightByKeys(array $keys)
    {
        $list = array();
        foreach ($keys as $key) {
            $list[$key] = $this->getWeightByKey($key);
        }
        return $list;
    }

    /**
     * 
     * @param string $key
     * @param numeric $weight
     * @return void
     * @throws \InvalidArgumentException
     */
    public function addWeight($key, $weight)
    {
        if (isset($this->weights[$key])) {
            throw new \InvalidArgumentException();
        }

        $this->weights[$key] = $weight;
    }

    /**
     * 
     * @param array $weights
     * @return void
     */
    public function addWeights(array $weights)
    {
        foreach ($weights as $key=>$weight) {
            $this->addWeight($key, $weight);
        }
    }

    /**
     * 
     * @param string $key
     */
    public function removeKey($key)
    {
        if (isset($this->weights[$key])) {
            unset($this->weights[$key]);
        }
    }

    /**
     * 
     * @param array $keys
     * @return void
     */
    public function removeKeys(array $keys)
    {
        foreach ($keys as $key) {
            $this->removeKey($key);
        }
    }

}