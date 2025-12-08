@extends('layouts.sellers-main')

@section('title', 'Plant Monitoring')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>Plant Monitoring</h4>
    <a href="{{ route('plants.create') }}" class="btn btn-primary">+ Add Record</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Species</th>
                    <th>Growth Stage</th>
                    <th>Watering</th>
                    <th>Environment</th>
                    <th width="80">Edit</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($plants as $p)
                <tr>
                    <td>{{ $p->species }}</td>
                    <td>{{ $p->growth_stage }}</td>
                    <td>{{ $p->watering_frequency }}</td>
                    <td>{{ $p->environment_conditions }}</td>
                    <td>
                        <a class="btn btn-sm btn-secondary">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection