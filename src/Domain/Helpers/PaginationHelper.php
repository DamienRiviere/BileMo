<?php

namespace App\Domain\Helpers;

use App\Domain\Common\Exception\PageNotFoundException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PaginationHelper
 * @package App\Domain\Helpers
 */
final class PaginationHelper
{

    /**
     * Check if the page exist
     *
     * @param Request $request
     * @param array $data
     * @param int $limit
     * @return array|int
     * @throws PageNotFoundException
     */
    public function checkPage(Request $request, array $data, int $limit)
    {
        $page = $request->query->getInt('page');

        if (is_null($page) || $page < 1) {
            $page = 1;
        }

        if ($page > $this->getPages($data, $limit)) {
            throw new PageNotFoundException("Cette page n'existe pas !");
        }

        return $page;
    }

    /**
     * Get the total number of pages
     *
     * @param array $data
     * @param int $limit
     * @return int
     */
    public function getPages(array $data, int $limit): int
    {
        $count = count($data);

        return (int)ceil($count / $limit);
    }
}
