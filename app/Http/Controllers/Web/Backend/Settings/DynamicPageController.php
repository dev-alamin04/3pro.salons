<?php
namespace App\Http\Controllers\Web\Backend\Settings;

use App\Http\Controllers\Controller;
use App\Models\DynamicPage;
use App\Models\User;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class DynamicPageController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the dynamic pages.
     *
     * @param Request $request
     * @return View|JsonResponse
     * @throws Exception
     */
    public function index(Request $request): View | JsonResponse
    {
        if ($request->ajax()) {
            $query = DynamicPage::latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('page_content', function (DynamicPage $page) {
                    return e(Str::limit(strip_tags($page->page_content), 100));
                })
                ->addColumn('status', function (DynamicPage $page) {
                    $checked = $page->status === 'active' ? 'checked' : '';
                    return '
                    <a href="#" class="change_status"
                       data-id="' . $page->id . '"
                       data-enabled="' . ($page->status === 'active' ? 'inactive' : 'active') . '"
                       data-title="Do you want to ' . ($page->status === 'active' ? 'Deactivate' : 'Activate') . ' it?"
                       data-description="This will change the page visibility."
                       data-bs-toggle="modal" data-bs-target="#statusModal">
                        <label class="switch">
                            <input type="checkbox" ' . $checked . '>
                            <span class="slider round"></span>
                        </label>
                    </a>';
                })
                ->addColumn('action', function (DynamicPage $page) {
                    return view('components.action-buttons', [
                        'id'     => $page->id,
                        'show'   => 'dynamic_page.show',
                        'edit'   => 'dynamic_page.edit',
                    ])->render();
                })
                ->rawColumns(['page_content', 'status', 'action'])
                ->make(true);
        }

        return view('backend.layouts.settings.dynamic_page.index');
    }

    /**
     * Show the form for creating a new dynamic page.
     *
     * @return View|RedirectResponse
     */
    public function create(): View | RedirectResponse
    {
        if (User::find(auth()->user()->id)) {
            return view('backend.layouts.settings.dynamic_page.create');
        }
        return redirect()->route('dynamic_page.index');
    }

    /**
     * Display the specified dynamic page.
     *
     * @param int $id
     * @return View|RedirectResponse
     */
    public function show(int $id): View | RedirectResponse
    {
        if (User::find(auth()->user()->id)) {
            $page = DynamicPage::findOrFail($id);
            return view('backend.layouts.settings.dynamic_page.show', compact('page'));
        }
        return redirect()->route('dynamic_page.index');
    }

    /**
     * Store a newly created dynamic page in the database.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            if (User::find(auth()->user()->id)) {
                $validator = Validator::make($request->all(), [
                    'page_title'   => 'required|string',
                    'banner'       => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
                    'page_content' => 'required|string',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                $data               = new DynamicPage();
                $data->page_title   = $request->page_title;
                $data->page_slug    = Str::slug($request->page_title);
                $data->page_content = $request->page_content;
                $data->save();
            }
            return redirect()->route('dynamic_page.index')->with('success', 'Dynamic Page created successfully.');
        } catch (Exception) {
            return redirect()->route('dynamic_page.index')->with('error', 'Dynamic Page failed created.');
        }
    }

    /**
     * Show the form for editing the specified dynamic page.
     *
     * @param int $id
     * @return View|RedirectResponse
     */
    public function edit(int $id): View | RedirectResponse
    {
        if (User::find(auth()->user()->id)) {
            $data = DynamicPage::find($id);
            return view('backend..layouts.settings.dynamic_page.edit', compact('data'));
        }
        return redirect()->route('dynamic_page.index');
    }

    /**
     * Update the specified dynamic page in the database.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        try {
            if (User::find(auth()->user()->id)) {
                $validator = Validator::make($request->all(), [
                    'page_title'   => 'nullable|string',
                    'banner'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
                    'page_content' => 'nullable|string',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                $data = DynamicPage::findOrFail($id);

                $banner = $data->banner; // ← keep old banner by default

                if ($request->hasFile('banner')) {
                    deleteFile($data->banner);
                    $banner = uploadFile($request->file('banner'), 'uploads/dynamic_page');
                }

                $data->update([
                    'page_title'   => $request->page_title,
                    'banner'       => $banner,
                    'page_content' => $request->page_content,
                ]);

                return redirect()->route('dynamic_page.index')->with('success', 'Successfully updated!');
            }
        } catch (Exception $e) {
            return redirect()->route('dynamic_page.index')->with('error', 'Unsuccessful!');
        }

        return redirect()->route('dynamic_page.index');
    }

    /**
     * Change the status of the specified dynamic page.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function status(int $id): JsonResponse
    {
        $data = DynamicPage::findOrFail($id);
        if ($data->status == 'active') {
            $data->status = 'inactive';
            $data->save();

            return response()->json([
                'success' => false,
                'message' => 'Unpublished Successfully.',
                'data'    => $data,
            ]);
        } else {
            $data->status = 'active';
            $data->save();

            return response()->json([
                'success' => true,
                'message' => 'Published Successfully.',
                'data'    => $data,
            ]);
        }
    }

    /**
     * Remove the specified dynamic page from the database.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $page = DynamicPage::findOrFail($id);

        deleteFile($page->banner);
        $page->delete();
        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully.',
        ]);
    }

    // public function dynamicPageforApp(Request $request)
    // {
    //     $page = DynamicPage::where('status', 'active')
    //         // ->where('page_slug',$request->query(, $defaultPerPage))
    //         ->first();

    //     if (! $page) {
    //         return $this->error([], 'Page not found.', 404);
    //     }

    //     return $this->success([
    //         'title' => $page->page_title,
    //         'slug' => $page->page_slug,
    //         'content' => $page->page_content, // HTML content for app to render
    //     ], 'Page retrieved successfully.');
    // }
    public function dynamicPageforApp($slug)
    {
        $page = DynamicPage::select(['page_title', 'page_slug', 'banner', 'page_content'])
            ->where('status', 'active')
            ->where('page_slug', $slug)
            ->first();

        if (! $page) {
            return $this->error([], 'Page not found.', 404);
        }

        return $this->success([
            // 'url' => url($page->page_slug),
            'banner'  => $page->banner,
            'title'   => $page->page_title,
            'content' => $page->page_content,
        ], 'Page retrieved successfully.');
        // //

        // return $this->success([
        //     'title'   => $page->page_title,
        //     'content' => $page->page_content,
        // ], 'Page retrieved successfully.');
    }
}
