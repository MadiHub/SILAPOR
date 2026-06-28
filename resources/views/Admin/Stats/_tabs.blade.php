<div style="display:flex; gap:4px; margin-bottom:24px; border-bottom:2px solid var(--background-light); padding-bottom:0;">
    @php
        $tabs = [
            ['route' => 'admin.stats.overview',    'label' => '<i class="fas fa-chart-pie"></i> Overview',    'key' => 'overview'],
            ['route' => 'admin.stats.trends',      'label' => '<i class="fas fa-chart-line"></i> Tren',      'key' => 'trends'],
            ['route' => 'admin.stats.departments', 'label' => '<i class="fas fa-building"></i> Dinas',       'key' => 'departments'],
            ['route' => 'admin.stats.top-votes',   'label' => '<i class="fas fa-arrow-up"></i> Top Vote',   'key' => 'top-votes'],
            ['route' => 'admin.stats.export',      'label' => '<i class="fas fa-download"></i> Export',     'key' => 'export'],
        ];
    @endphp
    @foreach($tabs as $tab)
        <a href="{{ route($tab['route']) }}"
           style="padding:10px 18px; text-decoration:none; font-size:0.88em; font-weight:600; border-radius:6px 6px 0 0; white-space:nowrap;
                  {{ $active === $tab['key']
                      ? 'background:var(--primary-color); color:#fff; margin-bottom:-2px; border:2px solid var(--primary-color); border-bottom:2px solid #fff;'
                      : 'color:#777; border:2px solid transparent;' }}">
            {!! $tab['label'] !!}
        </a>
    @endforeach
</div>