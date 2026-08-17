<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DynamicPage;
use App\Traits\ApiResponse;

class DynamicPageController extends Controller
{
    use ApiResponse;

    /**
     * Fetch All Dynamic Pages
     *
     * @return \Illuminate\Http\JsonResponse  JSON response with success or error.
     */
    public function dynamicPages()
    {

        $data = DynamicPage::where('status', 'active')->select('id', 'page_title', 'page_slug')->latest()->get();

        if ($data->isEmpty()) {
            return $this->error([], 'Dynamic Page not found', 200);
        }

        return $this->success($data, 'Dynamic Page fetch Successful!', 200);
    }

    /**
     * Fetch Single Dynamic Page
     *
     * @return \Illuminate\Http\JsonResponse  JSON response with success or error.
     */
    public function single(string $slug)
    {
        $dynamicPage = DynamicPage::where('page_slug', $slug)->select('id', 'page_title', 'page_slug', 'page_content')->firstOrFail();
        return $this->success($dynamicPage, 'Dynamic Page fetch Successful!', 200);
    }
}
