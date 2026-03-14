<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\BackendApiService;
use Illuminate\Http\Request;

class PredictionController extends Controller
{
    protected $api;

    public function __construct(BackendApiService $api)
    {
        $this->api = $api;
    }

    public function index() { return view('predictions.index'); }
    public function correlation() { return view('predictions.correlation'); }

    public function getPredictionsData(Request $request)
    {
        return response()->json($this->api->getPredictions($request->all()));
    }

    public function getPredictionHistory(Request $request)
    {
        return response()->json($this->api->getPredictionHistory($request->all()));
    }

    public function getCorrelationData()
    {
        return response()->json($this->api->getCorrelationData());
    }

    public function createPrediction(Request $request)
    {
        return response()->json($this->api->createPrediction($request->all()));
    }
}
