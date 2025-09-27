<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\String;

use Symfony\Component\String\Exception\ExceptionInterface;
use Symfony\Component\String\Exception\InvalidArgumentException;

/**
 * Represents a string of Unicode code points encoded as UTF-8.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 * @author Hugo Hamon <hugohamon@neuf.fr>
 *
 * @throws ExceptionInterface
 */
class CodePointString extends AbstractUnicodeString
{
    /**
     * @param string $string
     */
    public function __construct($string = '')
    {
        if ('' !== $string && !preg_match('//u', $string)) {
            throw new InvalidArgumentException('Invalid UTF-8 string.');
        }

        $this->string = $string;
    }

    /**
     * @return $this
     * @param string ...$suffix
     */
    public function append(...$suffix)
    {
        $str = clone $this;
        $str->string .= 1 >= \count($suffix) ? ($suffix[0] ?? '') : implode('', $suffix);

        if (!preg_match('//u', $str->string)) {
            throw new InvalidArgumentException('Invalid UTF-8 string.');
        }

        return $str;
    }

    /**
     * @param int $length
     */
    public function chunk($length = 1)
    {
        if (1 > $length) {
            throw new InvalidArgumentException('The chunk length must be greater than zero.');
        }

        if ('' === $this->string) {
            return [];
        }

        $rx = '/(';
        while (65535 < $length) {
            $rx .= '.{65535}';
            $length -= 65535;
        }
        $rx .= '.{'.$length.'})/us';

        $str = clone $this;
        $chunks = [];

        foreach (preg_split($rx, $this->string, -1, \PREG_SPLIT_DELIM_CAPTURE | \PREG_SPLIT_NO_EMPTY) as $chunk) {
            $str->string = $chunk;
            $chunks[] = clone $str;
        }

        return $chunks;
    }

    /**
     * @param int $offset
     */
    public function codePointsAt($offset)
    {
        $str = $offset ? $this->slice($offset, 1) : $this;

        return '' === $str->string ? [] : [mb_ord($str->string, 'UTF-8')];
    }

    /**
     * @param string|mixed[]|\Symfony\Component\String\AbstractString $suffix
     */
    public function endsWith($suffix)
    {
        if ($suffix instanceof AbstractString) {
            $suffix = $suffix->string;
        } elseif (!\is_string($suffix)) {
            return parent::endsWith($suffix);
        }

        if ('' === $suffix || !preg_match('//u', $suffix)) {
            return false;
        }

        if ($this->ignoreCase) {
            return preg_match('{'.preg_quote($suffix).'$}iuD', $this->string);
        }

        return \strlen($this->string) >= \strlen($suffix) && 0 === substr_compare($this->string, $suffix, -\strlen($suffix));
    }

    /**
     * @param string|mixed[]|\Symfony\Component\String\AbstractString $string
     */
    public function equalsTo($string)
    {
        if ($string instanceof AbstractString) {
            $string = $string->string;
        } elseif (!\is_string($string)) {
            return parent::equalsTo($string);
        }

        if ('' !== $string && $this->ignoreCase) {
            return \strlen($string) === \strlen($this->string) && 0 === mb_stripos($this->string, $string, 0, 'UTF-8');
        }

        return $string === $this->string;
    }

    /**
     * @param string|mixed[]|\Symfony\Component\String\AbstractString $needle
     * @param int $offset
     */
    public function indexOf($needle, $offset = 0)
    {
        if ($needle instanceof AbstractString) {
            $needle = $needle->string;
        } elseif (!\is_string($needle)) {
            return parent::indexOf($needle, $offset);
        }

        if ('' === $needle) {
            return null;
        }

        $i = $this->ignoreCase ? mb_stripos($this->string, $needle, $offset, 'UTF-8') : mb_strpos($this->string, $needle, $offset, 'UTF-8');

        return false === $i ? null : $i;
    }

    /**
     * @param string|mixed[]|\Symfony\Component\String\AbstractString $needle
     * @param int $offset
     */
    public function indexOfLast($needle, $offset = 0)
    {
        if ($needle instanceof AbstractString) {
            $needle = $needle->string;
        } elseif (!\is_string($needle)) {
            return parent::indexOfLast($needle, $offset);
        }

        if ('' === $needle) {
            return null;
        }

        $i = $this->ignoreCase ? mb_strripos($this->string, $needle, $offset, 'UTF-8') : mb_strrpos($this->string, $needle, $offset, 'UTF-8');

        return false === $i ? null : $i;
    }

    public function length()
    {
        return mb_strlen($this->string, 'UTF-8');
    }

    /**
     * @return $this
     * @param string ...$prefix
     */
    public function prepend(...$prefix)
    {
        $str = clone $this;
        $str->string = (1 >= \count($prefix) ? ($prefix[0] ?? '') : implode('', $prefix)).$this->string;

        if (!preg_match('//u', $str->string)) {
            throw new InvalidArgumentException('Invalid UTF-8 string.');
        }

        return $str;
    }

    /**
     * @return $this
     * @param string $from
     * @param string $to
     */
    public function replace($from, $to)
    {
        $str = clone $this;

        if ('' === $from || !preg_match('//u', $from)) {
            return $str;
        }

        if ('' !== $to && !preg_match('//u', $to)) {
            throw new InvalidArgumentException('Invalid UTF-8 string.');
        }

        if ($this->ignoreCase) {
            $str->string = implode($to, preg_split('{'.preg_quote($from).'}iuD', $this->string));
        } else {
            $str->string = str_replace($from, $to, $this->string);
        }

        return $str;
    }

    /**
     * @return $this
     * @param int $start
     * @param int|null $length
     */
    public function slice($start = 0, $length = null)
    {
        $str = clone $this;
        $str->string = mb_substr($this->string, $start, $length, 'UTF-8');

        return $str;
    }

    /**
     * @return $this
     * @param string $replacement
     * @param int $start
     * @param int|null $length
     */
    public function splice($replacement, $start = 0, $length = null)
    {
        if (!preg_match('//u', $replacement)) {
            throw new InvalidArgumentException('Invalid UTF-8 string.');
        }

        $str = clone $this;
        $start = $start ? \strlen(mb_substr($this->string, 0, $start, 'UTF-8')) : 0;
        $length = $length ? \strlen(mb_substr($this->string, $start, $length, 'UTF-8')) : $length;
        $str->string = substr_replace($this->string, $replacement, $start, $length ?? \PHP_INT_MAX);

        return $str;
    }

    /**
     * @param string $delimiter
     * @param int|null $limit
     * @param int|null $flags
     */
    public function split($delimiter, $limit = null, $flags = null)
    {
        if (1 > ($limit = $limit ?? \PHP_INT_MAX)) {
            throw new InvalidArgumentException('Split limit must be a positive integer.');
        }

        if ('' === $delimiter) {
            throw new InvalidArgumentException('Split delimiter is empty.');
        }

        if (null !== $flags) {
            return parent::split($delimiter.'u', $limit, $flags);
        }

        if (!preg_match('//u', $delimiter)) {
            throw new InvalidArgumentException('Split delimiter is not a valid UTF-8 string.');
        }

        $str = clone $this;
        $chunks = $this->ignoreCase
            ? preg_split('{'.preg_quote($delimiter).'}iuD', $this->string, $limit)
            : explode($delimiter, $this->string, $limit);

        foreach ($chunks as &$chunk) {
            $str->string = $chunk;
            $chunk = clone $str;
        }

        return $chunks;
    }

    /**
     * @param string|mixed[]|\Symfony\Component\String\AbstractString $prefix
     */
    public function startsWith($prefix)
    {
        if ($prefix instanceof AbstractString) {
            $prefix = $prefix->string;
        } elseif (!\is_string($prefix)) {
            return parent::startsWith($prefix);
        }

        if ('' === $prefix || !preg_match('//u', $prefix)) {
            return false;
        }

        if ($this->ignoreCase) {
            return 0 === mb_stripos($this->string, $prefix, 0, 'UTF-8');
        }

        return 0 === strncmp($this->string, $prefix, \strlen($prefix));
    }
}
