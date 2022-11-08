<?php

namespace App\Http\Controllers\Api\Customer\Review;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ReviewApiRepository;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Resources\Api\Customer\Review\ReviewApiCollection;
use App\Http\Requests\Api\Customer\Review\UpdateReviewApiRequest;
use App\Http\Requests\Api\Customer\Review\CreateReviewApiRequest;

class ReviewApiController extends AppBaseController
{
    protected $reviewApiRepository;

    public function __construct(ReviewApiRepository $review)
    {
        $this->reviewApiRepository = $review;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->reviewApiRepository->pushCriteria(new RequestCriteria($request));
        $items = $this->reviewApiRepository->get();

        return $this->sendResponse(ReviewApiCollection::collection($items), '');
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


     /**
     *   @OA\Post(
     *     path="/api/reviews",
     *      tags={"Add Review"},
     *      @OA\Parameter(
     *           name="order_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="Numeric"
     *           )
     *       ),
     *      @OA\Parameter(
     *           name="item_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="Numeric"
     *           )
     *       ),
     *      @OA\Parameter(
     *           name="rating",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="decimal"
     *           )
     *       ),
     *      @OA\Parameter(
     *           name="comment",
     *           in="query",
     *           required=false,
     *           @OA\Schema(
     *               type="stirng"
     *           )
     *       ),
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
    public function store(CreateReviewApiRequest $request)
    {
        $review =  $this->reviewApiRepository->createReview($request);

        return $this->sendResponse(new ReviewApiCollection($review), 'Review created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $review = $this->reviewApiRepository->findWithoutFail($id);
        if (empty($review)) {
            return $this->sendError($this->getLangMessages('Sorry! Review not found', 'Review'));
        }
        return $this->sendResponse(new ReviewApiCollection($review), 'Review retrived successfully');
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
    public function update($id, UpdateReviewApiRequest $request)
    {
        // return $id;
        $review = $this->reviewApiRepository->findWithoutFail($id);
        if (empty($review)) {
            return $this->sendError($this->getLangMessages('Sorry! Review not found', 'Review'));
        }
        $review = $this->reviewApiRepository->updateReview($id, $request);

        return $this->sendResponse(new ReviewApiCollection($review), 'Review updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $review = $this->reviewApiRepository->findWithoutFail($id);
        if (empty($review)) {
            return $this->sendError($this->getLangMessages('Sorry! Review not found', 'Review'));
        }

        $review->delete();
        return $this->sendResponse([], 'Review deleted successfully');
    }
}
