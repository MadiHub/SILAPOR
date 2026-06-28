@extends('Layouts.AdminLayout.base_layout')

@section('title', 'Top Vote Laporan')

@section('content')

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
    <h1 style="margin:0;">Laporan Prioritas (Top Vote)</h1>
</div>

@include('Admin.Stats._tabs', ['active' => 'top-votes'])

{{-- STAT CARDS --}}
<div class="dashboard-cards" style="margin-bottom:24px;">
    <div class="card" style="border-left:5px solid var(--primary-color);">
        <h3 style="font-size:0.78em;color:#aaa;text-transform:uppercase;font-weight:600;margin:0 0 6px;">Total Vote</h3>
        <p style="font-size:2em;font-weight:800;color:var(--primary-color);margin:0;">{{ number_format($totalVotes) }}</p>
    </div>
    <div class="card" style="border-left:5px solid #8b5cf6;">
        <h3 style="font-size:0.78em;color:#aaa;text-transform:uppercase;font-weight:600;margin:0 0 6px;">Unique Voter</h3>
        <p style="font-size:2em;font-weight:800;color:#8b5cf6;margin:0;">{{ number_format($uniqueVoters) }}</p>
    </div>
    <div class="card" style="border-left:5px solid #f59e0b;">
        <h3 style="font-size:0.78em;color:#aaa;text-transform:uppercase;font-weight:600;margin:0 0 6px;">Rata-rata Vote/Laporan</h3>
        <p style="font-size:2em;font-weight:800;color:#f59e0b;margin:0;">{{ $avgVotesPerReport }}</p>
    </div>
    @if($mostVoted)
        <div class="card" style="border-left:5px solid #10b981;">
            <h3 style="font-size:0.78em;color:#aaa;text-transform:uppercase;font-weight:600;margin:0 0 6px;">Vote Terbanyak</h3>
            <p style="font-size:1.3em;font-weight:800;color:#10b981;margin:0 0 2px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ Str::limit($mostVoted->title, 28) }}</p>
            <p style="font-size:0.82em;color:#aaa;margin:0;"><i class="fas fa-arrow-up" style="color:var(--primary-color);"></i> {{ $mostVoted->votes_count }} vote</p>
        </div>
    @endif
</div>

{{-- FILTER --}}
<div class="card" style="margin-bottom:20px;">
    <form method="GET" action="{{ route('admin.stats.top-votes') }}"
          style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
        <div style="min-width:140px;">
            <label style="font-size:0.8em;color:#777;display:block;margin-bottom:4px;">Status</label>
            <select name="status" style="width:100%; padding:9px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.9em;">
                <option value="">Semua Status</option>
                <option value="active"   {{ $status=='active'   ? 'selected':'' }}>Aktif</option>
                <option value="process"  {{ $status=='process'  ? 'selected':'' }}>Diproses</option>
                <option value="done"     {{ $status=='done'     ? 'selected':'' }}>Selesai</option>
                <option value="rejected" {{ $status=='rejected' ? 'selected':'' }}>Ditolak</option>
            </select>
        </div>
        <div style="min-width:200px;">
            <label style="font-size:0.8em;color:#777;display:block;margin-bottom:4px;">Dinas</label>
            <select name="department_id" style="width:100%; padding:9px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.9em;">
                <option value="">Semua Dinas</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ $deptId==$dept->id ? 'selected':'' }}>
                        {{ $dept->code }} – {{ $dept->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div style="min-width:120px;">
            <label style="font-size:0.8em;color:#777;display:block;margin-bottom:4px;">Tampilkan</label>
            <select name="limit" style="width:100%; padding:9px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.9em;">
                <option value="20"  {{ request('limit',20)==20  ? 'selected':'' }}>20</option>
                <option value="50"  {{ request('limit')==50  ? 'selected':'' }}>50</option>
                <option value="100" {{ request('limit')==100 ? 'selected':'' }}>100</option>
            </select>
        </div>
        <div style="display:flex; gap:8px;">
            <button type="submit"
                    style="background:var(--primary-color); color:#fff; border:none; padding:9px 18px; border-radius:6px; cursor:pointer; font-size:0.9em;">
                <i class="fas fa-filter"></i> Filter
            </button>
            @if($status || $deptId)
                <a href="{{ route('admin.stats.top-votes') }}"
                   style="background:var(--background-light); color:var(--text-dark); padding:9px 14px; border-radius:6px; text-decoration:none; font-size:0.9em;">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </div>
    </form>
</div>

{{-- TOP VOTES LIST --}}
<div class="card">
    @if($reports->isEmpty())
        <div style="text-align:center; padding:60px 20px; color:#aaa;">
            <i class="fas fa-vote-yea" style="font-size:3em; margin-bottom:12px; display:block;"></i>
            <p style="margin:0;">Tidak ada laporan ditemukan.</p>
        </div>
    @else
        <div style="display:flex; flex-direction:column; gap:0;">
            @foreach($reports as $i => $report)
                @php
                    $sc = match($report->status) {
                        'active'   => '#f59e0b',
                        'process'  => '#3b82f6',
                        'done'     => '#10b981',
                        'rejected' => '#ef4444',
                        default    => '#777',
                    };
                    $sl = match($report->status) {
                        'active'   => 'Aktif',
                        'process'  => 'Diproses',
                        'done'     => 'Selesai',
                        'rejected' => 'Ditolak',
                        default    => $report->status,
                    };
                    $rank = (($reports->currentPage() - 1) * $reports->perPage()) + $loop->iteration;
                    $rankColor = $rank === 1 ? '#f59e0b' : ($rank === 2 ? '#9ca3af' : ($rank === 3 ? '#b45309' : '#ddd'));
                    $firstImg = $report->images->first();
                @endphp
                <div style="display:flex; align-items:center; gap:14px; padding:14px 0; border-bottom:1px solid var(--background-light);">

                    {{-- RANK --}}
                    <div style="width:36px; height:36px; border-radius:50%; background:{{ $rankColor }}; display:flex; align-items:center; justify-content:center; color:{{ $rank <= 3 ? '#fff' : '#aaa' }}; font-weight:800; font-size:{{ $rank <= 9 ? '1em' : '0.82em' }}; flex-shrink:0;">
                        {{ $rank }}
                    </div>

                    {{-- THUMBNAIL --}}
                    @if($firstImg)
                        <img src="{{ asset('storage/' . $firstImg->image_url) }}"
                             style="width:56px; height:56px; object-fit:cover; border-radius:8px; flex-shrink:0;">
                    @else
                        <div style="width:56px; height:56px; border-radius:8px; background:var(--background-light); display:flex; align-items:center; justify-content:center; color:#ccc; flex-shrink:0;">
                            <i class="fas fa-image"></i>
                        </div>
                    @endif

                    {{-- CONTENT --}}
                    <div style="flex:1; min-width:0;">
                        <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap; margin-bottom:4px;">
                            <a href="{{ route('admin.reports.show', $report->id) }}"
                               style="color:var(--text-dark); text-decoration:none; font-weight:700; font-size:0.95em;">
                                {{ $report->title }}
                            </a>
                            <span style="background:{{ $sc }}1A; color:{{ $sc }}; padding:2px 8px; border-radius:20px; font-size:0.75em; font-weight:600;">{{ $sl }}</span>
                        </div>
                        <div style="display:flex; gap:12px; flex-wrap:wrap; font-size:0.8em; color:#aaa;">
                            @if($report->department)
                                <span><i class="fas fa-building"></i> {{ $report->department->name }}</span>
                            @endif
                            @if($report->category)
                                <span><i class="fas fa-tag"></i> {{ $report->category->name }}</span>
                            @endif
                            @if($report->user)
                                <span><i class="fas fa-user"></i> {{ $report->user->name }}</span>
                            @endif
                            <span><i class="fas fa-calendar"></i> {{ $report->created_at->format('d M Y') }}</span>
                        </div>
                        @if($report->address)
                            <div style="font-size:0.78em; color:#bbb; margin-top:3px;">
                                <i class="fas fa-map-marker-alt"></i> {{ Str::limit($report->address, 80) }}
                            </div>
                        @endif
                    </div>

                    {{-- VOTE BADGE --}}
                    <div style="text-align:center; flex-shrink:0;">
                        <div style="background:var(--primary-color); color:#fff; padding:8px 16px; border-radius:8px; min-width:70px;">
                            <p style="font-size:1.5em; font-weight:800; margin:0; line-height:1;">{{ number_format($report->votes_count) }}</p>
                            <p style="font-size:0.7em; margin:2px 0 0; opacity:0.85;">vote</p>
                        </div>
                        <a href="{{ route('admin.reports.show', $report->id) }}"
                           style="display:block; margin-top:6px; font-size:0.75em; color:var(--primary-color); text-decoration:none;">
                            Detail →
                        </a>
                    </div>

                </div>
            @endforeach
        </div>

        {{-- PAGINATION --}}
        <div style="margin-top:20px; display:flex; justify-content:space-between; align-items:center; font-size:0.85em; color:#777;">
            <span>Menampilkan {{ $reports->firstItem() }}–{{ $reports->lastItem() }} dari {{ $reports->total() }} laporan</span>
            {{ $reports->links() }}
        </div>
    @endif
</div>

@endsection