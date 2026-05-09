@extends('layouts.admin')

@section('dashboard_content')
<div class="header">
    <h2 style="margin:0;">Retail Branches</h2>
    <a href="#" class="btn btn-primary" onclick="alert('Creating branch functionality...')">Add New Branch</a>
</div>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Branch Name</th>
                <th>Location</th>
                <th>Manager</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($branches as $branch)
            <tr>
                <td>{{ $branch->branch_name }}</td>
                <td>{{ $branch->location }}</td>
                <td>{{ $branch->manager_name ?: 'Unassigned' }}</td>
                <td>
                    <span style="padding: 4px 8px; border-radius: 12px; font-size: 12px; background: #d4edda; color: #856404;">Active</span>
                </td>
                <td>
                    <a href="#" class="btn btn-info btn-sm">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top: 20px;">
        @if($branches->hasPages())
            <div style="display:flex; justify-content:center; gap:10px;">
                <a href="{{ $branches->previousPageUrl() }}" class="btn btn-primary btn-sm">&laquo; Prev</a>
                <a href="{{ $branches->nextPageUrl() }}" class="btn btn-primary btn-sm">Next &raquo;</a>
            </div>
        @endif
    </div>
</div>
@endsection
