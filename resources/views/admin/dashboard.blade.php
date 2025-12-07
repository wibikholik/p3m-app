@extends('admin.layouts.app')

@section('title', 'Dashboard Admin P3M')

@section('content')

    <!-- Greeting Section -->
    <div class="bg-white overflow-hidden shadow-xl rounded-xl p-8 mb-8 border border-gray-100">
        <div class="text-2xl font-semibold text-gray-900 flex items-center">
            👋 Selamat datang, <span class="text-blue-600 ml-2">{{ Auth::user()->name }}</span>!
        </div>
        <p class="mt-2 text-gray-600">Ini adalah halaman utama dashboard admin untuk memantau aktivitas P3M.</p>
    </div>
    <!-- Statistics Section -->
<div class="row">
  <div class="col-md-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <div class="d-sm-flex align-items-baseline report-summary-header">
              <h3    class="font-weight-semibold">Ringkasan Statistik</h3> <span class="ms-auto">Updated Report</span> <button class="btn btn-icons border-0 p-2"><i class="icon-refresh"></i></button>
            </div>
          </div>
        </div>
        <div class="row report-inner-cards-wrapper">
            <div class=" col-md -6 col-xl report-inner-card">
                <div class="inner-card-text">
                    <span class="report-title">Total Reviewer</span>
                        <h4>
                            {{ $totalReviewer ?? 0 }}
                        </h4>
                    <span href="{{ route('admin.users.index', ['role' => 'reviewer']) }}" class="text-xs text-blue-500 hover:text-blue-700 font-medium mt-2 block"> Lihat Detail &rarr;</span>
                </div>
                <div class="inner-card-icon bg-success">
                <i class="icon-rocket"></i>
                </div>
            </div>
            <div class="col-md-6 col-xl report-inner-card">
                <div class="inner-card-text">
                    <span class="report-title">Total Dosen</span>
                    <h4>
                        {{ $totalDosen ?? 0 }}
                    </h4>
                    <a href="{{ route('admin.users.index', ['role' => 'dosen']) }}" class="text-xs text-green-500 hover:text-green-700 font-medium mt-2 block">Lihat Detail &rarr;</a>
                </div>
                <div class="inner-card-icon bg-danger">
                    <i class="fa fa-graduation-cap"></i>
                </div>
            </div>
            <div class="col-md-6 col-xl report-inner-card">
                <div class="inner-card-text">
                    <span class="report-title">Pengumuman Aktif</span>
                    <h4>
                        {{ $pengumuman ?? 0 }}
                    </h4>
                    <a href="{{ route('admin.pengumuman.index') }}" class="text-xs text-yellow-500 hover:text-yellow-700 font-medium mt-2 block">Lihat Detail &rarr;</a>
                </div>
                <div class="inner-card-icon bg-warning">
                    <i class="icon-globe-alt"></i>
                </div>
            </div>
            <div class="col-md-6 col-xl report-inner-card">
                <div class="inner-card-text">
                    <span class="report-title">Total Usulan Masuk</span>
                    <h4>
                        {{ $totalUsulan ?? 'N/A' }}
                    </h4>
                    <span class="report-count"> Data Usulans &rarr;</span>
                </div>
                <div class="inner-card-icon bg-primary">
                    <i class="icon-diamond"></i>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
