<?php

namespace App\Http\Controllers\Api\Customer\Branch;

use Illuminate\Http\Request;
use App\Repositories\BranchApiRepository;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Resources\Admin\Branch\BranchApiCollection;
use App\Http\Requests\Admin\Branch\UpdateBranchApiRequest;
use App\Http\Requests\Admin\Branch\CreateBranchApiRequest;

class BranchApiController extends AppBaseController
{
    protected $branchApiRepository;

    public function __construct(BranchApiRepository $branch)
    {
        $this->branchApiRepository = $branch;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */




    /**
     *   @OA\get(
     *     path="/api/branches",
     *      tags={"View all branches"},
     *   
     *      
     *       @OA\Response(
     *           response=200,
     *           description="Success",
     *            @OA\MediaType(
     *               mediaType="application/json",
     *           )
     *       ),
     *
     * )
     */
    public function index(Request $request)
    {
        $items = $this->branchApiRepository->get();

        $datas = BranchApiCollection::collection($items);

        return $this->sendResponse($datas, 'Branch retrived successfully');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateBranchApiRequest $request)
    {
        if ($request->id) {
            $this->branchApiRepository->updateBranch($request->id, $request);
            $message = "Branch Updated Successfully..";
        } else {
            $this->branchApiRepository->createBranch($request);
            $message = "Branch Added Successfully..";
        }
        // return $request;
        // $this->sendResponse(new BranchApiCollection($branch), 'Branch created successfully');
        return redirect('admin/branches')->with('success', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $branch = $this->branchApiRepository->findWithoutFail($id);
        if (empty($branch)) {
            return $this->sendError($this->getLangMessages('Sorry! Branch not found', 'Branch'));
        }

        return $this->sendResponse(new BranchApiCollection($branch), 'Branch retrived successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, UpdateBranchApiRequest $request)
    {
        // return $id;
        $branch = $this->branchApiRepository->findWithoutFail($id);
        if (empty($branch)) {
            return $this->sendError($this->getLangMessages('Sorry! Branch not found', 'Branch'));
        }
        $branch = $this->branchApiRepository->updateBranch($id, $request);

        // $this->sendResponse(new BranchApiCollection($branch), 'Branch updated successfully');
        return redirect('admin/branches');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $branch = $this->branchApiRepository->findWithoutFail($id);
        if (empty($branch)) {
            return $this->sendError($this->getLangMessages('Sorry! Branch not found', 'Branch'));
        }

        $branch->delete();
        // $this->sendResponse([], 'Branch deleted successfully');
        // BranchApiCollection::collection($branch);
        return redirect()->to('/admin/branches')->with('delete', 'Branch Deleted Successfully..');
    }
}
