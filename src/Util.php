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

class Util
{

    /**
     * 
     * @return \WeightedRandom\Drawer
     */
    public static function newInstance()
    {
        return new \WeightedRandom\Drawer();
    }

    /**
     * 
     * @param array $keys
     * @return string
     */
    public static function drawKeyWithEquality($keys)
    {
        return static::drawKeyWithWeights(array_fill_keys($keys, 1));
    }

    /**
     * 
     * @param array $keys
     * @param numeric $requiredCount
     * @param boolean $uniqueFlag
     * @return array
     */
    public static function drawKeysWithEquality(array $keys, $requiredCount, $uniqueFlag = false)
    {
        return static::drawKeysWithWeights(array_fill_keys($keys, 1), $requiredCount, $uniqueFlag);
    }

    /**
     * 
     * @param array $keys
     * @param numeric $requiredCount
     * @return array
     */
    public static function drawUniqueKeysWithEquality(array $keys, $requiredCount)
    {
        return static::drawKeysWithEquality($keys, $requiredCount, true);
    }

    /**
     * 
     * @param array $weights
     * @return string
     */
    public static function drawKeyWithWeights(array $weights)
    {
        $instance = static::newInstance();
        $instance->setWeights($weights);
        return $instance->drawKey();
    }

    /**
     * 
     * @param array $weights
     * @param numeric $requiredCount
     * @param boolean $uniqueFlag
     * @return array
     */
    public static function drawKeysWithWeights(array $weights, $requiredCount, $uniqueFlag = false)
    {
        $instance = static::newInstance();
        $instance->setWeights($weights);
        return $instance->drawKeys($requiredCount, $uniqueFlag);
    }

    /**
     * 
     * @param array $weights
     * @param numeric $requiredCount
     * @return array
     */
    public static function drawUniqueKeysWithWeights(array $weights, $requiredCount)
    {
        return static::drawKeysWithWeights($weights, $requiredCount, true);
    }

    /**
     * 
     * @param numeric $probability
     * @param numeric $denominator
     * @return boolean
     */
    public static function judgeProbability($probability, $denominator = 100)
    {
        $probability = (real)$probability;
        if ($probability <= 0) {
            return false;
        }

        if ($probability >= $denominator) {
            return true;
        }

        return (static::drawKeyWithWeights(array(
            0=>($denominator - $probability),
            1=>$probability
        )) === 1);
    }

}