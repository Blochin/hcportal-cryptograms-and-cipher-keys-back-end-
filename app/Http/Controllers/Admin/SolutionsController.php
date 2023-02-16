<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Solution\BulkDestroySolution;
use App\Http\Requests\Admin\Solution\DestroySolution;
use App\Http\Requests\Admin\Solution\IndexSolution;
use App\Http\Requests\Admin\Solution\StoreSolution;
use App\Http\Requests\Admin\Solution\UpdateSolution;
use App\Models\Solution;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SolutionsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexSolution $request
     * @return array|Factory|View
     */
    public function index(IndexSolution $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Solution::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'name'],

            // set columns to searchIn
            ['id', 'name']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.solution.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.solution.create');

        return view('admin.solution.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSolution $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreSolution $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the Solution
        $solution = Solution::create($sanitized);

        alert()->success('Success', 'Sucessfully added solution.');

        if ($request->ajax()) {
            return ['redirect' => url('admin/solutions'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/solutions');
    }

    /**
     * Display the specified resource.
     *
     * @param Solution $solution
     * @throws AuthorizationException
     * @return void
     */
    public function show(Solution $solution)
    {
        $this->authorize('admin.solution.show', $solution);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Solution $solution
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(Solution $solution)
    {
        $this->authorize('admin.solution.edit', $solution);


        return view('admin.solution.edit', [
            'solution' => $solution,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSolution $request
     * @param Solution $solution
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateSolution $request, Solution $solution)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values Solution
        $solution->update($sanitized);

        alert()->success('Success', 'Sucessfully updated solution.');

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/solutions'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/solutions');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroySolution $request
     * @param Solution $solution
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroySolution $request, Solution $solution)
    {
        $solution->delete();

        alert()->success('Success', 'Sucessfully deleted solution.');

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroySolution $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroySolution $request): Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Solution::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });


        alert()->success('Success', 'Sucessfully deleted selected solutions.');

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
