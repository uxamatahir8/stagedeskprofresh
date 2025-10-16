@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">{{ $title }}</h4>
        </div>

        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        <i class="ti ti-home"></i>
                    </a>
                </li>

                {{-- <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li> --}}

                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="card">
        <div class="card-header justify-content-between">
            <h4 class="card-title"> {{ $title }}</h4>
            <a href="{{ route('package.create') }}" class="btn btn-primary">Add Package</a>
        </div>

        <div class="card-body">
            <table data-tables="export-data-dropdown" class="table table-striped align-middle mb-0">
                <thead class="thead-sm text-uppercase fs-xxs">
                    <tr>
                        <th>Company</th>
                        <th>Symbol</th>
                        <th>Price</th>
                        <th>Change</th>
                        <th>Volume</th>
                        <th>Market Cap</th>
                        <th>Rating</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Apple Inc.</td>
                        <td>AAPL</td>
                        <td>$2109.53</td>
                        <td>-0.42%</td>
                        <td>48,374,838</td>
                        <td>$53.59B</td>
                        <td>4.7 ★</td>
                        <td><span class="badge badge-label badge-soft-danger">Bearish</span></td>
                    </tr>
                </tbody>
            </table>
        </div> <!-- end card-body-->
    </div> <!-- end card-->
@endsection
