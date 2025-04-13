@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<div class="container py-4">
    {{-- Topbar with title, search, and view switch --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        {{-- <h2 class="fw-bold mb-0">Central App Manager</h2> --}}

        <div class="d-flex gap-2 w-100 w-md-auto">
            <input type="text" id="searchInput" class="form-control" placeholder="Search by name or group..." />

            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-secondary" id="cardViewBtn">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                </button>
                <button type="button" class="btn btn-outline-secondary" id="tileViewBtn">
                    <i class="bi bi-list-task"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Optional Loading Spinner --}}
    <div id="loading" class="text-center my-4">
        <div class="spinner-border text-primary" role="status"></div>
    </div>

    {{-- App Groups --}}
    @forelse ($apps as $groupName => $groupApps)
        <div class="mb-5 app-group" data-group="{{ strtolower($groupName) }}">
            <h4 class="mb-3 text-primary fw-bold border-bottom pb-2 group-title position-relative">
                {{ strtoupper($groupName) }}
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                    {{ count($groupApps) }}
                </span>
            </h4>

            {{-- Card View --}}
            <div class="row g-4 view-type view-card">
                @foreach ($groupApps as $app)
                    <div class="col-6 col-md-4 col-lg-3 col-xl-2 app-item"
                         data-name="{{ strtolower($app->name) }}"
                         data-group="{{ strtolower($app->group) }}">
                        <div class="card h-100 shadow-sm border-0 text-center hover-effect">
                            <div class="p-3">
                                <img src="{{ asset('storage/' . $app->logo) }}" class="img-fluid" alt="{{ $app->name }}" style="height: 80px; object-fit: contain;">
                            </div>
                            <div class="card-body">
                                <h6 class="fw-bold">{{ $app->name }}</h6>
                                <a href="{{ $app->link }}" target="_blank"
                                   class="btn btn-sm btn-outline-primary mt-2 rounded-pill"
                                   data-bs-toggle="tooltip" data-bs-placement="top"
                                   title="Launch {{ $app->name }}">
                                   Open
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Tile View --}}
            <div class="view-type view-tile d-none">
                @foreach ($groupApps as $app)
                    <div class="d-flex align-items-center gap-3 border p-3 mb-2 rounded shadow-sm app-item"
                         data-name="{{ strtolower($app->name) }}"
                         data-group="{{ strtolower($app->group) }}">
                        <img src="{{ asset('storage/' . $app->logo) }}" style="height: 50px; width: 50px; object-fit: contain;">
                        <div class="flex-grow-1">
                            <div class="fw-bold">{{ $app->name }}</div>
                            <small class="text-muted">Group: {{ $groupName }}</small>
                        </div>
                        <a href="{{ $app->link }}" target="_blank"
                           class="btn btn-sm btn-outline-primary"
                           data-bs-toggle="tooltip" title="Launch {{ $app->name }}">
                           Open
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="alert alert-info">No active apps found.</div>
    @endforelse
</div>

{{-- JS: View Switching, Search, Tooltips, Loading --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Tooltip Init
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(el => new bootstrap.Tooltip(el))

        // Loading spinner
        setTimeout(() => {
            document.getElementById('loading')?.remove();
        }, 500); // fake loading

        // View Switching
        const cardBtn = document.getElementById('cardViewBtn');
        const tileBtn = document.getElementById('tileViewBtn');
        const cardViews = document.querySelectorAll('.view-card');
        const tileViews = document.querySelectorAll('.view-tile');

        function toggleView(view) {
            localStorage.setItem('appViewType', view);
            cardViews.forEach(v => v.classList.toggle('d-none', view !== 'card'));
            tileViews.forEach(v => v.classList.toggle('d-none', view !== 'tile'));
        }

        cardBtn.onclick = () => toggleView('card');
        tileBtn.onclick = () => toggleView('tile');

        const savedView = localStorage.getItem('appViewType') || 'card';
        toggleView(savedView);

        // Search Filter
        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('input', function () {
            const query = this.value.trim().toLowerCase();
            document.querySelectorAll('.app-group').forEach(group => {
                let hasVisibleApp = false;
                group.querySelectorAll('.app-item').forEach(app => {
                    const name = app.dataset.name;
                    const groupName = app.dataset.group;
                    const match = name.includes(query) || groupName.includes(query);
                    app.classList.toggle('d-none', !match);
                    if (match) hasVisibleApp = true;
                });
                group.classList.toggle('d-none', !hasVisibleApp);
            });
        });
    });
</script>

{{-- Optional Custom Styling --}}
<style>
    .hover-effect:hover {
        transform: scale(1.03);
        transition: all 0.3s ease;
        box-shadow: 0 0 18px rgba(0, 0, 0, 0.08);
    }
</style>
@endsection
