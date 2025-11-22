@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white py-3 border-bottom-0 rounded-top-4">
                    <h4 class="mb-0 fw-bold text-primary"><i class="bi bi-person-circle me-2"></i>ข้อมูลโปรไฟล์</h4>
                </div>
                <div class="card-body p-4">
                    @if(session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="username" class="form-label fw-bold">Username</label>
                                <input type="text" class="form-control bg-light" id="username" value="{{ $user->username ?? '-' }}" disabled>
                                <small class="text-muted">Username ไม่สามารถแก้ไขได้</small>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-bold">Email</label>
                                <input type="email" class="form-control bg-light" id="email" value="{{ $user->email ?? '-' }}" disabled>
                                <small class="text-muted">Email ไม่สามารถแก้ไขได้</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="tel" class="form-label fw-bold">เบอร์โทรศัพท์</label>
                            <input type="tel" class="form-control @error('tel') is-invalid @enderror" id="tel" name="tel" value="{{ old('tel', $user->tel) }}" pattern="[0-9+\-\s]{7,15}">
                            @error('tel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label fw-bold">ที่อยู่</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($user->affiliation)
                            <div class="mb-3">
                                <label for="affiliation" class="form-label fw-bold">สังกัด</label>
                                <input type="text" class="form-control bg-light" id="affiliation" value="{{ $user->affiliation->name }}" disabled>
                                <small class="text-muted">สังกัดไม่สามารถแก้ไขได้</small>
                            </div>
                        @endif

                        <!-- <div class="mb-3">
                            <label for="role" class="form-label fw-bold">บทบาท</label>
                            <input type="text" class="form-control bg-light" id="role" value="{{ ucfirst($user->role) }}" disabled>
                        </div> -->

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('dashboard.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>กลับ
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-2"></i>บันทึกการเปลี่ยนแปลง
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
