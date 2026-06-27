@extends('Layouts.AdminLayout.base_layout')

@section('title', 'Detail Dinas – ' . $department->name)

@section('content')

{{-- BREADCRUMB --}}
<div style="font-size:0.85em; color:#999; margin-bottom:16px;">
    <a href="{{ route('admin.departments.index') }}" style="color:var(--primary-color); text-decoration:none;">Dinas</a>
    <span style="margin:0 6px;">/</span>
    {{ $department->name }}
</div>

{{-- FLASH --}}
@if(session('success'))
    <div style="background:#d1fae5; border:1px solid #10b981; color:#065f46; padding:12px 16px; border-radius:8px; margin-bottom:20px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div style="background:#fee2e2; border:1px solid #ef4444; color:#991b1b; padding:12px 16px; border-radius:8px; margin-bottom:20px;">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
@endif

{{-- HEADER --}}
<div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:24px;">
    <div>
        <div style="display:inline-block; background:var(--primary-color); color:#fff; padding:5px 14px; border-radius:6px; font-size:0.85em; font-weight:700; letter-spacing:0.08em; margin-bottom:8px;">
            {{ $department->code }}
        </div>
        <h1 style="margin:0; font-size:1.5em;">{{ $department->name }}</h1>
        @if($department->description)
            <p style="color:#777; font-size:0.9em; margin:6px 0 0; max-width:600px;">{{ $department->description }}</p>
        @endif
    </div>
    <div style="display:flex; gap:8px;">
        <a href="{{ route('admin.departments.edit', $department->id) }}"
           style="background:var(--primary-color); color:#fff; padding:9px 18px; border-radius:8px; text-decoration:none; font-weight:600; font-size:0.9em;">
            <i class="fas fa-pencil-alt"></i> Edit
        </a>
        <form method="POST" action="{{ route('admin.departments.destroy', $department->id) }}"
              onsubmit="return confirm('Hapus dinas {{ addslashes($department->name) }}?')">
            @csrf @method('DELETE')
            <button type="submit"
                    style="background:#fee2e2; color:#dc2626; border:none; padding:9px 16px; border-radius:8px; cursor:pointer; font-size:0.9em; font-weight:600;">
                <i class="fas fa-trash"></i> Hapus
            </button>
        </form>
    </div>
</div>

{{-- STAT CARDS --}}
<div style="display:grid; grid-template-columns:repeat(6,1fr); gap:14px; margin-bottom:24px;">
    @foreach([
        ['label'=>'Total Laporan','value'=>$reportStats['total'],'color'=>'#8b5cf6'],
        ['label'=>'Aktif','value'=>$reportStats['active'],'color'=>'#f59e0b'],
        ['label'=>'Diproses','value'=>$reportStats['process'],'color'=>'#3b82f6'],
        ['label'=>'Selesai','value'=>$reportStats['done'],'color'=>'#10b981'],
        ['label'=>'Ditolak','value'=>$reportStats['rejected'],'color'=>'#ef4444'],
        ['label'=>'% Selesai','value'=>$completionRate.'%','color'=> $completionRate>=75 ? '#10b981' : ($completionRate>=40 ? '#f59e0b' : '#ef4444')],
    ] as $s)
        <div class="card" style="border-top:3px solid {{ $s['color'] }}; text-align:center; padding:14px 10px;">
            <p style="font-size:1.6em; font-weight:700; color:{{ $s['color'] }}; margin:0 0 4px;">{{ $s['value'] }}</p>
            <p style="font-size:0.72em; color:#999; margin:0;">{{ $s['label'] }}</p>
        </div>
    @endforeach
</div>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:24px;">

    {{-- LEFT --}}
    <div style="display:flex; flex-direction:column; gap:20px;">

        {{-- STAF / PEMDA --}}
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
                <h3 style="margin:0;">Staf Dinas ({{ $department->users_count }})</h3>
                @if($availableStaff->isNotEmpty())
                    <button onclick="document.getElementById('assign-staff-form').classList.toggle('hidden')"
                            style="background:var(--primary-color); color:#fff; border:none; padding:7px 14px; border-radius:6px; cursor:pointer; font-size:0.82em; font-weight:600;">
                        <i class="fas fa-user-plus"></i> Tambah Staf
                    </button>
                @endif
            </div>

            {{-- ASSIGN STAFF --}}
            <div id="assign-staff-form" class="hidden"
                 style="background:var(--background-light); padding:12px; border-radius:8px; margin-bottom:14px;">
                <p style="font-size:0.8em; color:#777; margin:0 0 8px;">Tambah pengguna Pemda ke dinas ini:</p>
                <form method="POST" action="{{ route('admin.users.departments.assign', '__user__') }}"
                      id="form-assign-staff"
                      style="display:flex; gap:8px; align-items:flex-end;">
                    @csrf
                    <div style="flex:1;">
                        <select name="department_id" id="staff-select" required
                                style="width:100%; padding:9px 12px; border:1px solid #e5e7eb; border-radius:6px; font-size:0.9em;">
                            <option value="{{ $department->id }}" selected hidden>{{ $department->name }}</option>
                        </select>
                        {{-- hidden real select for user --}}
                        <select name="user_id_for_dept" id="staff-user-select" required
                                style="width:100%; padding:9px 12px; border:1px solid #e5e7eb; border-radius:6px; font-size:0.9em; margin-top:8px;">
                            <option value="">-- Pilih Staf --</option>
                            @foreach($availableStaff as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }} ({{ $staff->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" id="btn-assign-staff"
                            style="background:var(--primary-color); color:#fff; border:none; padding:9px 14px; border-radius:6px; cursor:pointer; font-weight:600; white-space:nowrap;">
                        Tambah
                    </button>
                </form>
            </div>

            @if($department->users->isEmpty())
                <p style="color:#aaa; font-size:0.9em;">Belum ada staf di dinas ini.</p>
            @else
                <div style="display:flex; flex-direction:column; gap:8px; max-height:350px; overflow-y:auto;">
                    @foreach($department->users as $staff)
                        @php
                            $roleColor = match($staff->role) {
                                'admin' => '#ef4444',
                                'pemda' => '#3b82f6',
                                default => '#10b981',
                            };
                        @endphp
                        <div style="display:flex; align-items:center; gap:10px; padding:10px; background:var(--background-light); border-radius:8px;">
                            <img src="{{ $staff->avatar_url }}"
                                 style="width:36px; height:36px; border-radius:50%; object-fit:cover; flex-shrink:0;">
                            <div style="flex:1; min-width:0;">
                                <a href="{{ route('admin.users.show', $staff->id) }}"
                                   style="color:var(--text-dark); text-decoration:none; font-weight:600; font-size:0.9em;">
                                    {{ $staff->name }}
                                </a>
                                <div style="font-size:0.78em; color:#999; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $staff->email }}</div>
                            </div>
                            <span style="background:{{ $roleColor }}1A; color:{{ $roleColor }}; padding:2px 8px; border-radius:20px; font-size:0.75em; font-weight:600; flex-shrink:0;">
                                {{ $staff->role }}
                            </span>
                            <form method="POST" action="{{ route('admin.users.departments.remove', [$staff->id, $department->id]) }}"
                                  onsubmit="return confirm('Lepas staf ini dari dinas?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        style="background:#fee2e2; color:#dc2626; border:none; padding:5px 9px; border-radius:5px; cursor:pointer; font-size:0.8em;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- KATEGORI MASALAH --}}
        <div class="card">
            <h3 style="margin:0 0 14px;">Kategori Masalah ({{ $department->categories_count }})</h3>

            @if($department->categories->isEmpty())
                <p style="color:#aaa; font-size:0.9em;">Belum ada kategori untuk dinas ini.</p>
            @else
                <div style="display:flex; flex-direction:column; gap:8px;">
                    @foreach($department->categories as $cat)
                        <div style="display:flex; align-items:center; justify-content:space-between; padding:10px 12px; background:var(--background-light); border-radius:8px;">
                            <div>
                                <p style="margin:0; font-weight:600; font-size:0.9em;">{{ $cat->name }}</p>
                                @if($cat->description)
                                    <p style="margin:2px 0 0; font-size:0.78em; color:#999;">{{ Str::limit($cat->description, 60) }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

    {{-- RIGHT: LAPORAN TERBARU --}}
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
            <h3 style="margin:0;">Laporan Terbaru</h3>
            <a href="{{ route('admin.reports.index', ['department_id' => $department->id]) }}"
               style="font-size:0.85em; color:var(--primary-color); text-decoration:none;">
                Lihat semua →
            </a>
        </div>

        @if($department->reports->isEmpty())
            <p style="color:#aaa; font-size:0.9em;">Belum ada laporan masuk ke dinas ini.</p>
        @else
            <div style="display:flex; flex-direction:column; gap:12px; max-height:600px; overflow-y:auto;">
                @foreach($department->reports as $report)
                    @php
                        $sc = match($report->status) {
                            'active'   => '#f59e0b',
                            'process'  => '#3b82f6',
                            'done'     => '#10b981',
                            'rejected' => '#ef4444',
                            default    => '#777',
                        };
                        $firstImage = $report->images->first();
                    @endphp
                    <div style="display:flex; gap:10px; align-items:flex-start; padding:10px; border:1px solid var(--background-light); border-radius:8px;">
                        @if($firstImage)
                            <img src="{{ asset('storage/' . $firstImage->image_url) }}"
                                 style="width:52px; height:52px; object-fit:cover; border-radius:6px; flex-shrink:0;">
                        @else
                            <div style="width:52px; height:52px; border-radius:6px; background:var(--background-light); display:flex; align-items:center; justify-content:center; color:#ccc; flex-shrink:0;">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                        <div style="flex:1; min-width:0;">
                            <a href="{{ route('admin.reports.show', $report->id) }}"
                               style="color:var(--text-dark); text-decoration:none; font-weight:600; font-size:0.9em; display:block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                {{ $report->title }}
                            </a>
                            <div style="font-size:0.78em; color:#aaa; margin:2px 0 6px;">{{ $report->created_at->format('d M Y') }}</div>
                            <span style="background:{{ $sc }}1A; color:{{ $sc }}; padding:3px 9px; border-radius:20px; font-size:0.75em; font-weight:600; text-transform:capitalize;">
                                {{ $report->status }}
                            </span>
                        </div>
                        <div style="display:flex; align-items:center; gap:4px; font-size:0.8em; color:#999; flex-shrink:0;">
                            <i class="fas fa-arrow-up" style="color:var(--primary-color);"></i>
                            {{ $report->votes_count }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

@endsection

@section('scripts')
<style>.hidden { display:none !important; }</style>
<script>
    // Assign staff: redirect form action dynamically
    document.getElementById('btn-assign-staff')?.addEventListener('click', function(e) {
        e.preventDefault();
        const userId = document.getElementById('staff-user-select').value;
        if (!userId) { alert('Pilih staf terlebih dahulu.'); return; }
        const form = document.getElementById('form-assign-staff');
        form.action = form.action.replace('__user__', userId);
        form.submit();
    });
</script>
@endsection