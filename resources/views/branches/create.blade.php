@extends('layouts.admin')

@section('dashboard_content')
<div class="row justify-content-center">
    <div class="col-6">
        <div class="card">
            <div class="card-header">🏢 Create New Branch</div>
            <div class="card-body">
                <form action="{{ route('branches.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Branch Name</label>
                        <input type="text" name="branch_name" required>
                    </div>
                    <div class="form-group">
                        <label>Location</label>
                        <input type="text" name="location" required>
                    </div>
                    <div class="form-group">
                        <label>Manager Name</label>
                        <input type="text" name="manager_name" required>
                    </div>
                    <div style="margin-top: 20px; display: flex; gap: 10px;">
                        <button type="submit" class="btn btn-primary">Create Branch</button>
                        <a href="{{ route('branches.index') }}" class="btn btn-link">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
