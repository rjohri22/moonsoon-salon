<?php

namespace App\Repositories;

use App\Models\Review;
use App\Repositories\BaseRepository;

/**
 * Class ReviewRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method ReviewRepository findWithoutFail($id, $columns = ['*'])
 * @method ReviewRepository find($id, $columns = ['*'])
 * @method ReviewRepository first($columns = ['*'])
 */
class ReviewApiRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'order_id',
        'user_id',
        'item_id',
        'rating',
        'comment',
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Review::class;
    }

    /**
     * Create a  review
     *
     * @param Request $request
     *
     * @return Review
     */
    public function createReview($request)
    {
        $input = collect($request->all());
        $review = Review::create($input->only($request->fillable('reviews'))->all());
        return $review;
    }

    /**
     * Update the Review
     *
     * @param Request $request
     *
     * @return Review
     */

    public function updateReview($id, $request)
    {

        $input = collect($request->all());
        $review = Review::findOrFail($id);
        $review->update($input->only($request->fillable('reviews'))->all());

        return $review;
    }



}
