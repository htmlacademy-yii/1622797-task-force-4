<?php

declare(strict_types=1);

namespace taskforce\dataConverter;

use SplFileObject;
use RuntimeException;
use taskforce\exception\SourceFileException;
use taskforce\exception\FileFormatException;
use taskforce\exception\HeadersColumnException;
use taskforce\exception\RequireColumnsException;

class DataConverter
{
    private string $csvFileName;
    private array $columns;
    private object $fileObject;
    private string $tableSqlName;
    private string $sqlFileName;

    /**
     * @param string $csvFileName
     * @param array $columns
     * @param string $tableSqlName
     * @param string $sqlFileName
     */
    public function __construct(string $csvFileName, array $columns, string $tableSqlName, string $sqlFileName)
    {
        $this->csvFileName = $csvFileName;
        $this->columns = $columns;
        $this->tableSqlName = $tableSqlName;
        $this->sqlFileName = $sqlFileName;
    }

    /**
     * @return void
     * @throws FileFormatException
     * @throws HeadersColumnException
     * @throws RequireColumnsException
     * @throws SourceFileException
     */
    private function import(): void
    {
        if (!$this->validateColumns($this->columns)) {
            throw new HeadersColumnException();
        }
        if (!file_exists($this->csvFileName)) {
            throw new SourceFileException();
        }

        try {
            $this->fileObject = new SplFileObject($this->csvFileName);
        } catch (RuntimeException $exception) {
            throw new RequireColumnsException();
        }

        $headerData = $this->getHeaderData();
        if ($headerData !== $this->columns) {
            throw new FileFormatException();
        }

        foreach ($this->getNextLine() as $line) {
            $this->result[] = $line;
        }
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->result;
    }

    /**
     * @return array|null
     */
    private function getHeaderData(): ?array
    {
        $this->fileObject->rewind();
        $data = $this->fileObject->fgetcsv();

        return $data;
    }

    /**
     * @return iterable|null
     */
    private function getNextLine(): ?iterable
    {
        while (!$this->fileObject->eof()) {
            yield $this->fileObject->fgetcsv();
        }
        return null;
    }

    /**
     * @param array $columns
     * @return bool
     */
    private function validateColumns(array $columns): bool
    {
        $result = true;

        if (count($columns)) {
            foreach ($columns as $column) {
                if (!is_string($column)) {
                    $result = false;
                }
            }
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * @return void
     */
    private function convertToSql(): void
    {
        $sqlQuery = 'INSERT INTO ' . $this->tableSqlName;
        $sqlQueryArray = [];

        foreach ($this->getData() as $values) {
            if ($values !== []) {
                foreach ($this->getHeaderData() as $key) {
                    $newKey[] = '`' . $key . '`';
                }
                foreach ($values as $value) {
                    $newValues[] = '"' . $value . '"';
                }
                $sqlQueryArray[] = $sqlQuery . ' (' . implode(', ', $newKey) . ') VALUE ' .
                    '(' . implode(', ', $newValues) . ');' . PHP_EOL;
                $newKey = [];
                $newValues = [];
            }
        }
        $this->sqlQueries = $sqlQueryArray;
    }

    /**
     * @return void
     */
    private function saveSqlFile(): void
    {
        $sqlFile = new SplFileObject($this->sqlFileName . '.sql', 'w');

        foreach ($this->sqlQueries as $query) {
            $sqlFile->fwrite($query);
        }
    }

    /**
     * @return void
     * @throws FileFormatException
     * @throws HeadersColumnException
     * @throws RequireColumnsException
     * @throws SourceFileException
     */
    public function converting(): void
    {
        $this->import();
        $this->convertToSql();
        $this->saveSqlFile();
    }
}
