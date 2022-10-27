<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Services\NeoApiService;
use App\Http\Requests\NeoStatsRequest;

class NeoStatsController extends Controller
{
    use ResponseTrait;
    
    protected $neoApiService;

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(NeoApiService $neoApiService)
    {
        $this->neoApiService = $neoApiService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(NeoStatsRequest $request)
    {
        $response = $this->neoApiService->getNeoStats($request->from_date, $request->to_date);

        if(!is_null($response)) return $this->successResponse('Records fetched successfully', $response);

        return $this->failureResponse('Some error occured, Please try again later!', $response);
    }

}
