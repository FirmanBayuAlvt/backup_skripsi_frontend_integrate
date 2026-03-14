<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BackendApiService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.backend.base_url');
    }

    protected function request($method, $endpoint, $data = [])
    {
        try {
            $response = Http::timeout(30)->$method($this->baseUrl . $endpoint, $data);
            if ($response->successful()) {
                return $response->json();
            }
            Log::error("Backend API error: {$method} {$endpoint}", [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan pada server',
                'status' => $response->status()
            ];
        } catch (\Exception $e) {
            Log::error("Backend API connection failed: {$method} {$endpoint} - " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Tidak dapat terhubung ke server backend'
            ];
        }
    }

    // Livestock
    public function getLivestocks($params = []) { return $this->request('get', '/livestocks', $params); }
    public function getLivestockDetail($id) { return $this->request('get', "/livestocks/{$id}"); }
    public function createLivestock($data) { return $this->request('post', '/livestocks', $data); }
    public function recordWeight($id, $data) { return $this->request('post', "/livestocks/{$id}/record-weight", $data); }
    public function importLivestocks($file)
    {
        try {
            $response = Http::timeout(60)->attach('file', file_get_contents($file), $file->getClientOriginalName())
                ->post($this->baseUrl . '/livestocks/import');
            if ($response->successful()) {
                return $response->json();
            }
            return ['success' => false, 'message' => 'Gagal impor'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // Pens
    public function getPens($params = []) { return $this->request('get', '/pens', $params); }
    public function getPenDetail($id) { return $this->request('get', "/pens/{$id}"); }
    public function getPenAnalytics($id) { return $this->request('get', "/pens/{$id}/analytics"); }
    public function createPen($data) { return $this->request('post', '/pens', $data); }
    public function importPens($file)
    {
        try {
            $response = Http::timeout(60)->attach('file', file_get_contents($file), $file->getClientOriginalName())
                ->post($this->baseUrl . '/pens/import');
            if ($response->successful()) {
                return $response->json();
            }
            return ['success' => false, 'message' => 'Gagal impor'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // Feeds
    public function getFeeds($params = []) { return $this->request('get', '/feeds', $params); }
    public function getFeedStock() { return $this->request('get', '/feeds/stock/summary'); }
    public function getFeedRequirements() { return $this->request('get', '/feeds/requirements'); }
    public function recordFeeding($data) { return $this->request('post', '/feeds/record-feeding', $data); }
    public function updateFeedStock($data) { return $this->request('post', '/feeds/update-stock', $data); }
    public function createFeed($data) { return $this->request('post', '/feeds', $data); }
    public function importFeeds($file)
    {
        try {
            $response = Http::timeout(60)->attach('file', file_get_contents($file), $file->getClientOriginalName())
                ->post($this->baseUrl . '/feeds/import');
            if ($response->successful()) {
                return $response->json();
            }
            return ['success' => false, 'message' => 'Gagal impor'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // Predictions
    public function getPredictions($params = []) { return $this->request('get', '/predictions', $params); }
    public function getPredictionHistory($params = []) { return $this->request('get', '/predictions/history', $params); }
    public function getCorrelationData() { return $this->request('get', '/predictions/correlation'); }
    public function createPrediction($data) { return $this->request('post', '/predictions', $data); }

    // Dashboard
    public function getDashboardOverview() { return $this->request('get', '/dashboard/overview'); }
    public function getDashboardPenAnalytics() { return $this->request('get', '/dashboard/pen-analytics'); }

    // Reports
    public function getReportSummary() { return $this->request('get', '/reports/summary'); }
    public function getReportPerformance() { return $this->request('get', '/reports/performance'); }
    public function getReportGrowth() { return $this->request('get', '/reports/growth'); }
    public function getReportFinancial() { return $this->request('get', '/reports/financial'); }
}
