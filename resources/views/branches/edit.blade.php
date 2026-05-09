@extends('layouts.admin')

@section('dashboard_content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><strong>Edit Branch</strong></div>
            <div class="card-body">
                <form action="{{ route('branches.update', $branch) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Branch Name</label>
                        <input type="text" name="branch_name" class="form-control" value="{{ $branch->branch_name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control" value="{{ $branch->location }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Manager Name</label>
                        <input type="text" name="manager_name" class="form-control" value="{{ $branch->manager_name }}" required>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Update Branch</button>
                        <a href="{{ route('branches.index') }}" class="btn btn-link">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
