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

class Drawer extends Base
{

    /**
     * 
     * @param boolean $useOpenssl
     */
    public function __construct($useOpenssl = true)
    {
        parent::__construct($useOpenssl);
    }

    /**
     * 
     * @return string
     * @throws LogicException
     */
    public function drawKey()
    {
        $weights = $this->getWeights();
        if (!$weights) {
            return null;
        }

        $sum = (real)array_sum($weights);
        if ($sum <= 0) {
            return null;
        }

        $stack = 0;
        $random = (mt_rand() * ($sum / mt_getrandmax()));
        foreach ($weights as $key=>$weight) {
            $stack += $weight;
            if ($random <= $stack) {
                return $key;
            }
        }

        throw new LogicException();
    }

    /**
     * 
     * @param numeric $requiredCount
     * @param boolean $uniqueFlag
     * @return array
     */
    public function drawKeys($requiredCount, $uniqueFlag = false)
    {
        $requiredCount = (int)$requiredCount;
        if ($requiredCount <= 0) {
            return array();
        }

        $list = array();
        $beforeKeys = $this->getWeights();
        for ($i = 0; $i < $requiredCount; $i++) {
            $key = $this->drawKey();
            if (is_null($key)) {
                break;
            }

            $list[] = $key;
            if ($uniqueFlag === true) {
                $this->removeKey($key);
            }
        }
        $this->setWeights($beforeKeys);
        return $list;
    }

    /**
     * 
     * @param numeric $requiredCount
     * @param boolean $uniqueFlag
     * @return array
     */
    public function drawUniqueKeys($requiredCount)
    {
        return (array)$this->drawKeys($requiredCount, true);
    }

}