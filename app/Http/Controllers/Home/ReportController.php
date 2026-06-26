<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProblemCategory;
use Illuminate\Support\Facades\DB;
use App\Models\Report;
use App\Models\ReportImage;
use App\Models\ReportVote;
use App\Models\Department;

class ReportController extends Controller
{
    public function index()
    {
        // INSTANSI FOOTER
        $departments = Department::all();

        $problem_categories = ProblemCategory::all();

        $data = [
            'departments' => $departments,
            'problem_categories' => $problem_categories,
        ];

        return view('Home.report', $data);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'category_id' => 'required|exists:problem_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'address' => 'required|string|max:255',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp',
        ]);

        DB::beginTransaction();

        try {

            // ambil category + department otomatis
            $category = ProblemCategory::with('department')
                ->findOrFail($request->category_id);

            // 1. CREATE REPORT
            $report = Report::create([
                'user_id' => auth()->id(),
                'category_id' => $category->id,
                'department_id' => $category->department_id,
                'title' => $request->title,
                'description' => $request->description,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'address' => $request->address,
                'status' => 'active',
                'votes_count' => 0,
            ]);

            // 2. UPLOAD IMAGES (jika ada)
            if ($request->hasFile('images')) {

                foreach ($request->file('images') as $file) {

                    $path = $file->store('reports', 'public');

                    ReportImage::create([
                        'report_id' => $report->id,
                        'image_url' => $path,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('reports.index')
                ->with('success', 'Laporan berhasil dikirim');

        } catch (\Exception $e) {

            DB::rollBack();

            dd([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    public function vote($id)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Harus login dulu!'
            ], 401);
        }

        $userId = Auth::id();

        $report = Report::findOrFail($id);

        $existingVote = ReportVote::where('user_id', $userId)
            ->where('report_id', $id)
            ->first();

        if ($existingVote) {
            $existingVote->delete();

            $report->decrement('votes_count');

            return response()->json([
                'status' => 'unvoted',
                'votes' => $report->votes_count
            ]);
        } else {
            // 🔺 VOTE
            ReportVote::create([
                'user_id' => $userId,
                'report_id' => $id
            ]);

            $report->increment('votes_count');

            return response()->json([
                'status' => 'voted',
                'votes' => $report->votes_count
            ]);
        }
    }

    public function myReports(Request $request)
    {
        // INSTANSI FOOTER
        $departments = Department::all();

        $reports = Report::with([
                'images',
                'category',
                'user',
                'comments'
            ])
            ->withCount(['comments', 'votes'])
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(9)
            ->withQueryString();

        $reports->getCollection()->transform(function ($report) {
            return [
                'id' => $report->id,
                'title' => $report->title,
                'description' => $report->description,
                'address' => $report->address,
                'latitude' => $report->latitude,
                'longitude' => $report->longitude,
                'votes_count' => $report->votes_count,
                'comments_count' => $report->comments_count,
                'status' => $report->status,
                'is_voted' => $report->votes->contains('user_id', auth()->id()),
                'category' => $report->category,
                'images' => $report->images,
                'created_at' => $report->created_at,
            ];
        });

        return view('Home.my_report', [
            'reports' => $reports,
            'departments' => $departments,
        ]);
    }
}
