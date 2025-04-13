@extends('layouts.app')

@section('content')
<div class="container py-5">
    @forelse ($apps as $groupName => $groupApps)
        <div class="mb-5">
            <h3 class="mb-4 text-dark border-bottom pb-2">{{ strtoupper($groupName) }}</h3>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4">
                @foreach ($groupApps as $app)
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0 rounded text-center">
                            <div class="p-3">
                                <img src="{{ asset('storage/' . $app->logo) }}" class="img-fluid" alt="{{ $app->name }}" style="height: 80px; object-fit: contain;">
                            </div>
                            <div class="card-body">
                                <h6 class="fw-bold">{{ $app->name }}</h6>
                                <a href="{{ $app->link }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2 rounded-pill">Open</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="alert alert-info">No active apps found.</div>
    @endforelse
</div>
@endsection
