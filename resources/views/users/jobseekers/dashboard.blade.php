@extends('layouts.dashboard')

@section('content')
    <h1 class="mb-4">Available Jobs</h1>
    <div class="row">
        <!-- Example job cards, replace with dynamic jobs if available -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Software Engineer</h5>
                    <p class="card-text">Join our team to build amazing web applications. Experience with Laravel required.</p>
                    <a href="#" class="btn btn-primary">View Details</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Graphic Designer</h5>
                    <p class="card-text">Creative designer needed for branding and marketing projects. Portfolio required.</p>
                    <a href="#" class="btn btn-primary">View Details</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Data Analyst</h5>
                    <p class="card-text">Analyze data trends and provide insights for business growth. Excel and SQL skills a plus.</p>
                    <a href="#" class="btn btn-primary">View Details</a>
                </div>
            </div>
        </div>
        <!-- End example cards -->
    </div>
@endsection
