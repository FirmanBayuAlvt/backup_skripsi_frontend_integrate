<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\BackendApiService;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    protected $api;

    public function __construct(BackendApiService $api)
    {
        $this->api = $api;
    }

    public function index() { return view('feeds.index'); }
    public function stock() { return view('feeds.stock'); }
    public function requirements() { return view('feeds.requirements'); }

    public function getFeedsData(Request $request)
    {
        return response()->json($this->api->getFeeds($request->all()));
    }

    public function getStockLevels()
    {
        return response()->json($this->api->getFeedStock());
    }

    public function getFeedRequirements()
    {
        return response()->json($this->api->getFeedRequirements());
    }

    public function recordFeeding(Request $request)
    {
        return response()->json($this->api->recordFeeding($request->all()));
    }

    public function updateStock(Request $request)
    {
        return response()->json($this->api->updateFeedStock($request->all()));
    }

    public function storeFeed(Request $request)
    {
        return response()->json($this->api->createFeed($request->all()));
    }

    public function importFeeds(Request $request)
    {
        $file = $request->file('file');
        if (!$file) {
            return response()->json(['success' => false, 'message' => 'File tidak ditemukan']);
        }
        return response()->json($this->api->importFeeds($file));
    }
}
