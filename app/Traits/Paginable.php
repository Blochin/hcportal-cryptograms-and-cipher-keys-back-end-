<?php

namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;


/*
|--------------------------------------------------------------------------
| Paginable
|--------------------------------------------------------------------------
|
| This trait will be used for paginating and ordering data.
|
*/

trait Paginable
{
	/**
	 *
	 * Filter, order and paginate
	 *
	 */
	private function filterPagination($query, $request, $orderBy = 'id', $orderDirection = 'asc', $paginate = true)
	{
		if ($request->orderBy && $request->orderDirection) {
			$query->reorder($request->orderBy, $request->orderDirection);
		} else {
			$query->reorder($orderBy, $orderDirection);
		}

		if ($paginate && $request->per_page) {
			$query = $query->paginate($request->per_page)->withQueryString();
		} elseif ($paginate) {
			$query = $query->paginate(10000)->withQueryString();
		} else {
			$query = $query->get();
		}

		return $query;
	}

	/**
	 * Paginate
	 *
	 * @param Collection $items
	 * @param integer $perPage
	 * @param int $page
	 * @param array $options
	 * @return LengthAwarePaginator
	 */
	public function paginate($items, $perPage = 15, $page = null, $options = [])
	{
		$page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
		$items = $items instanceof Collection ? $items : Collection::make($items);

		return new LengthAwarePaginator(array_values($items->forPage($page, $perPage)
			->toArray()), $items->count(), $perPage, $page, $options);
	}
}
