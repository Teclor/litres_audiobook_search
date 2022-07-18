<?php

namespace Catalog;


use ORM\BookNameTable;

class Searcher
{
    private string $searchText;
    private string $comparisonMethod;
    private float $accuracy;
    private bool $isFound = false;
    private int $bookId;
    private string $bookName;
    
    public const COMPARISON_METHOD_LEVENSHTEIN = 'levenshtein';
    public const COMPARISON_METHOD_SIMILAR_TEXT = 'similar_text';
    
    public function __construct(string $searchText, float $accuracy = 0.9, string $comparisonMethod = self::COMPARISON_METHOD_LEVENSHTEIN)
    {
        $this->searchText = $searchText;
        $this->accuracy = $accuracy;
        $this->comparisonMethod = $comparisonMethod;
    }
    
    public function search(): bool
    {
        $book = BookNameTable::getSearchedName($this->searchText);
        $this->isFound = false;
        if (!empty($book)) {
            $this->bookId = (int)$book[BookNameTable::getPrimaryKey()];
            $this->bookName = (string)$book['NAME'];
            $this->isFound = true;
        }
        
        return $this->isFound;
    }
    
    public function validate(): bool
    {
        $isValidResult = $this->isFound;
        if ($this->isFound) {
            $similarity = 0.0;
            if ($this->comparisonMethod === self::COMPARISON_METHOD_LEVENSHTEIN) {
                $bookNameLength = strlen($this->bookName);
                $similarity = ($bookNameLength - levenshtein(trim($this->bookName), trim($this->searchText))) / $bookNameLength;
            }
            elseif ($this->comparisonMethod === self::COMPARISON_METHOD_SIMILAR_TEXT) {
                similar_text(trim($this->bookName), trim($this->searchText), $percent);
                $similarity = $percent / 100;
            }
            $isValidResult = $similarity >= $this->accuracy;
        }
        
        return $isValidResult;
    }
    
    public function getBookId(): int
    {
        return $this->bookId;
    }
    
    public function getBookName(): string
    {
        return $this->bookName;
    }
}