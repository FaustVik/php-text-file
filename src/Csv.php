<?php

namespace FaustVik\Files;

/**
 * Class Csv
 * @package FaustVik\Files
 */
class Csv extends AbstractFile
{
    /**@var array $list_ass_key */
    protected $list_ass_key = [];

    /**@var bool $skip_first_line */
    protected $skip_first_line = false;

    /**
     * Csv constructor.
     *
     * @param string $path_to_file
     *
     * @throws FIleException
     */
    public function __construct(string $path_to_file)
    {
        parent::__construct($path_to_file);

        if ($this->getExtension() !== 'csv') {
            throw new FIleException('Extension not supported');
        }
    }

    /**
     * Read csv to array
     *
     * @param int|null $length
     * @param string   $separator
     * @param string   $enclosure
     * @param string   $escape
     *
     * @return array
     */
    public function readToArray(?int $length = 1000, string $separator = ',', string $enclosure = '"', string $escape = '\\'): array
    {
        $result = [];

        $counter = 0;

        if (($handle = $this->openFile()) !== false) {
            while (($data = fgetcsv($handle, $length, $separator, $enclosure, $escape)) !== false) {
                if ($counter === 0 && $this->skip_first_line) {
                    $counter++;
                    continue;
                }
                $result[] = $this->replaceAssociations($data);
            }
            fclose($handle);
        }

        return $result;
    }

    /**
     * Replace indexed key on custom key
     *
     * @param array $array_line
     *
     * @return array
     */
    protected function replaceAssociations(array $array_line): array
    {
        if ($this->list_ass_key === []) {
            return $array_line;
        }

        foreach ($array_line as $k => $line) {
            if (isset($this->list_ass_key[$k])) {
                unset($array_line[$k]);
                $array_line[$this->list_ass_key[$k]] = $line;
            }
        }

        return $array_line;
    }

    /**
     * Set rules for replace indexed key for your custom key
     *
     * @param array $list
     *
     * @return $this
     * @example  [1 => 'name']
     *           in result [0 = 'fdg', 2 => 123, 'name' => 'Victor']
     *
     */
    public function setAssociationsIndexKeys(array $list): Csv
    {
        $this->list_ass_key = $list;
        return $this;
    }

    /**
     * Skip first line
     *
     * @return $this
     */
    public function skipFirstLine(): Csv
    {
        $this->skip_first_line = true;
        return $this;
    }

    /**
     * Overwrites the file
     *
     * @param array  $fields
     * @param string $separator
     * @param string $enclosure
     * @param string $escape_char
     */
    public function overwriteToFile(array $fields, string $separator = ",", string $enclosure = '"', string $escape_char = "\\"): void
    {
        $handle_file = $this->openFile('w+');

        if (!$handle_file) {
            return;
        }

        $this->write($handle_file, $fields, $separator, $enclosure, $escape_char);
    }

    /**
     * Append to the end of the file
     *
     * @param array  $fields
     * @param string $separator
     * @param string $enclosure
     * @param string $escape_char
     */
    public function appendToFile(array $fields, string $separator = ",", string $enclosure = '"', string $escape_char = "\\"): void
    {
        $handle_file = $this->openFile('a+');

        if (!$handle_file) {
            return;
        }

        $this->write($handle_file, $fields, $separator, $enclosure, $escape_char);
    }

    /**
     * @param resource $handle_file
     * @param array    $fields
     * @param string   $separator
     * @param string   $enclosure
     * @param string   $escape_char
     */
    protected function write($handle_file, array $fields, string $separator = ",", string $enclosure = '"', string $escape_char = "\\"): void
    {
        if ($fields === []) {
            return;
        }

        foreach ($fields as $line_array) {
            if (!is_array($line_array)) {
                continue;
            }

            fputcsv($handle_file, $line_array, $separator, $enclosure, $escape_char);
        }

        fclose($handle_file);
    }

    /**
     * @param string      $mode
     * @param string|null $path_file
     *
     * @return false|resource
     */
    protected function openFile(string $mode = 'rb', string $path_file = null)
    {
        return fopen($path_file ?? $this->path_to_file, $mode);
    }

    /**
     * @param string $path_to_new_file
     * @param bool   $overwrite_file
     * @param string $separator
     * @param string $enclosure
     * @param string $escape_char
     */
    public function saveToNewFile(string $path_to_new_file, bool $overwrite_file = false, string $separator = ",", string $enclosure = '"', string $escape_char = "\\"): void
    {
        $data = $this->readToArray();
        $mode = $overwrite_file ? 'w+' : 'a+';

        $handle_file = $this->openFile($mode, $path_to_new_file);
        $this->write($handle_file, $data, $separator, $enclosure, $escape_char);
    }
}