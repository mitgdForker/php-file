<?php

namespace KHerGe\File;

use Exception;
use KHerGe\File\Exception\FileException;
use SplFileObject;

/**
 * Manages errors for read and write operations.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class File extends SplFileObject
{
    /**
     * @override
     */
    public function __construct(
        $filename,
        $open_mode = 'r',
        $use_include_path = false,
        $context = null
    ) {
        try {
            if (null === $context) {
                parent::__construct(
                    $filename,
                    $open_mode,
                    $use_include_path
                );
            } else {
                parent::__construct(
                    $filename,
                    $open_mode,
                    $use_include_path,
                    $context
                );
            }
        } catch (Exception $exception) {
            throw FileException::openFailed($filename, $exception);
        }
    }

    /**
     * @override
     */
    public function fflush()
    {
        if (!parent::fflush()) {
            throw FileException::flushFailed($this);
        }

        return true;
    }

    /**
     * @override
     */
    public function fgetc()
    {
        if (false === ($c = parent::fgetc())) {
            throw FileException::reachedEOF($this);
        }

        return $c;
    }

    /**
     * @override
     */
    public function fgetcsv(
        $delimiter = ',',
        $enclosure = '"',
        $escape = '\\'
    ) {
        if (!is_array($row = parent::fgetcsv($delimiter, $enclosure, $escape))) {
            throw FileException::readFailed($this);
        }

        return $row;
    }

    /**
     * @override
     */
    public function fgets()
    {
        try {
            if (false === ($string = parent::fgets())) {
                throw FileException::readFailed($this);
            }
        } catch (Exception $exception) {
            throw FileException::readFailed($this, $exception);
        }

        return $string;
    }

    /**
     * @override
     */
    public function fgetss($allowable_tags = null)
    {
        if (false === ($string = parent::fgetss($allowable_tags))) {
            throw FileException::readFailed($this);
        }

        return $string;
    }

    /**
     * @override
     */
    public function flock($operation, &$wouldblock = null)
    {
        if (!parent::flock($operation, $wouldblock)) {
            throw FileException::lockFailed($this);
        }

        return true;
    }

    /**
     * @override
     */
    public function fputcsv($fields, $delimiter = ',', $enclosure = '"')
    {
        if (false === ($length = parent::fputcsv($fields, $delimiter, $enclosure))) {
            throw FileException::writeFailed($this);
        }

        return $length;
    }

    /**
     * @override
     */
    public function fseek($offset, $whence = SEEK_SET)
    {
        if (-1 === parent::fseek($offset, $whence)) {
            throw FileException::seekFailed($this);
        }

        return 0;
    }

    /**
     * @override
     */
    public function ftell()
    {
        if (false === ($position = parent::ftell())) {
            throw FileException::tellFailed($this);
        }

        return $position;
    }

    /**
     * @override
     */
    public function ftruncate($size)
    {
        if (!parent::ftruncate($size)) {
            throw FileException::truncateFailed($this);
        }

        return true;
    }

    /**
     * @override
     */
    public function fwrite($str, $length = null)
    {
        if (null === $length) {
            $length = strlen($str);
        }

        if (null === ($bytes = parent::fwrite($str, $length))) {
            throw FileException::writeFailed($this);
        }

        return $bytes;
    }

    /**
     * @override
     */
    public function seek($line_pos)
    {
        try {
            parent::seek($line_pos);
        } catch (Exception $exception) {
            throw FileException::seekFailed($this, $exception);
        }
    }
}
