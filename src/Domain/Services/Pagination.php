<?php

namespace App\Domain\Services;

final class Pagination
{

    protected $limit;

    protected $data;

    protected $currentPage;

    public function __construct(int $limit, array $data, int $currentPage)
    {
        $this->limit = $limit;
        $this->data = $data;
        $this->currentPage = $currentPage;
    }

    public function getCurrentPage(): int
    {
        return (int)$this->currentPage;
    }

    public function getFirstPage(): int
    {
        return 1;
    }

    public function getLastPage(): int
    {
        $count = count($this->data);
        $lastPage = ceil($count / $this->limit);

        return (int)$lastPage;
    }

    public function getNextPage(): int
    {
        return $this->getCurrentPage() + 1;
    }

    public function getPreviousPage(): int
    {
        return $this->getCurrentPage() - 1;
    }

    public function getPages(): int
    {
        $count = count($this->data);

        return (int)ceil($count / $this->limit);
    }
}
