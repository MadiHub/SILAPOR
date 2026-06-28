<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\ProblemCategory;
use App\Models\Report;
use App\Models\ReportImage;
use App\Models\ReportVote;
use App\Models\Department;

class ReportController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
 
        $reports = Report::with(['images', 'category', 'user', 'votes'])
        ->withCount('votes')
        ->latest()
        ->paginate(9)
        ->through(function ($report) use ($userId) {
            return [
                'id'             => $report->id,
                'title'          => $report->title,
                'description'    => $report->description,
                'address'        => $report->address,
                'status'         => $report->status,
                'votes_count'    => $report->votes_count,
                'is_voted'       => $report->votes->contains('user_id', $userId),
                'comments_count' => $report->comments_count ?? 0,
                'created_at'     => $report->created_at,
                'category'       => $report->category,
                'images'         => $report->images,
                'user'           => $report->user,
            ];
        });
    
        return view('Home.Reports.index', compact('reports'));
    }

    public function create()
    {
        $problem_categories = ProblemCategory::all();

        $data = [
            'problem_categories' => $problem_categories,
        ];

        return view('Home.Reports.create', $data);
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

            $category = ProblemCategory::with('department')
                ->findOrFail($request->category_id);

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
                'status'  => 'error',
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
            $status = 'unvoted';
        } else {
            ReportVote::create([
                'user_id'   => $userId,
                'report_id' => $id
            ]);
            $status = 'voted';
        }

        $actualCount = ReportVote::where('report_id', $id)->count();
        $report->update(['votes_count' => $actualCount]);

        return response()->json([
            'status' => $status,
            'votes'  => $actualCount
        ]);
    }

    public function myReports(Request $request)
    {
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

        return view('Home.Reports.my_report', [
            'reports' => $reports,
        ]);
    }

    public function edit($id)
    {
        $report = Report::with(['images', 'category'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);
    
        if ($report->status !== 'active') {
            return redirect()
                ->route('reports.me')
                ->with('error', 'Laporan dengan status ini sudah tidak bisa diedit.');
        }
    
        $problem_categories = ProblemCategory::all();
    
        return view('Home.Reports.edit', compact('report', 'problem_categories'));
    }
    
    public function update(Request $request, $id)
    {
        $report = Report::with('images')
            ->where('user_id', auth()->id())
            ->findOrFail($id);
    
        if ($report->status !== 'active') {
            return redirect()
                ->route('reports.me')
                ->with('error', 'Laporan dengan status ini sudah tidak bisa diedit.');
        }
    
        $request->validate([
            'category_id'        => 'required|exists:problem_categories,id',
            'title'               => 'required|string|max:255',
            'description'         => 'required|string|max:1000',
            'images'              => 'nullable|array|max:10',
            'images.*'            => 'image|mimes:jpg,jpeg,png,webp|max:10240',
            'existing_images'     => 'nullable|array',
            'existing_images.*'   => 'integer|exists:report_images,id',
        ]);
    
        $keepCount = is_array($request->existing_images) ? count($request->existing_images) : 0;
        $newCount  = $request->hasFile('images') ? count($request->file('images')) : 0;
    
        if (($keepCount + $newCount) > 10) {
            return back()
                ->withErrors(['images' => 'Total foto maksimal 10.'])
                ->withInput();
        }
    
        DB::beginTransaction();
    
        try {
    
            $category = ProblemCategory::with('department')
                ->findOrFail($request->category_id);
    
            $report->update([
                'category_id'    => $category->id,
                'department_id'  => $category->department_id,
                'title'          => $request->title,
                'description'    => $request->description,
            ]);
    
            $keepIds = $request->existing_images ?? [];
    
            $imagesToDelete = $report->images->whereNotIn('id', $keepIds);
    
            foreach ($imagesToDelete as $img) {
                Storage::disk('public')->delete($img->image_url);
                $img->delete();
            }
    
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
                ->route('reports.me')
                ->with('success', 'Laporan berhasil diperbarui');
    
        } catch (\Exception $e) {
    
            DB::rollBack();
    
            dd([
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
            ]);
        }
    }
    
    public function destroy($id)
    {
        $report = Report::with('images')
            ->where('user_id', auth()->id())
            ->findOrFail($id);
    
        if ($report->status !== 'active') {
            return redirect()
                ->route('reports.me')
                ->with('error', 'Laporan dengan status ini sudah tidak bisa dihapus.');
        }
    
        DB::beginTransaction();
    
        try {
    
            foreach ($report->images as $img) {
                Storage::disk('public')->delete($img->image_url);
            }
    
            $report->images()->delete();
    
            $report->delete();
    
            DB::commit();
    
            return redirect()
                ->route('reports.me')
                ->with('success', 'Laporan berhasil dihapus');
    
        } catch (\Exception $e) {
    
            DB::rollBack();
    
            dd([
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
            ]);
        }
    }
    
}
