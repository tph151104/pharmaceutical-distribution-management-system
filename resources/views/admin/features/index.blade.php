@extends('layouts.app')

@section('title', 'Quản lý Bảo trì chức năng')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="content-header-title mb-0"><i class="bi bi-tools text-primary me-2"></i>Quản lý Bảo trì chức năng</h1>
            <p class="text-muted small mb-0 mt-1">Bật/tắt các module tính năng trong hệ thống</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 20%">Mã chức năng</th>
                            <th style="width: 25%">Tên chức năng</th>
                            <th style="width: 35%">Mô tả</th>
                            <th class="text-center" style="width: 20%">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($features as $feature)
                            <tr>
                                <td class="ps-4"><code>{{ $feature->ma_chuc_nang }}</code></td>
                                <td class="fw-semibold">{{ $feature->ten_chuc_nang }}</td>
                                <td class="text-muted small">{{ $feature->mo_ta }}</td>
                                <td class="text-center">
                                    <div class="form-check form-switch d-inline-block">
                                        <input class="form-check-input feature-toggle" type="checkbox" role="switch" 
                                            id="toggle_{{ $feature->ma_chuc_nang }}" 
                                            data-id="{{ $feature->ma_chuc_nang }}"
                                            {{ $feature->trang_thai ? 'checked' : '' }}>
                                    </div>
                                    <span class="ms-2 badge {{ $feature->trang_thai ? 'bg-success' : 'bg-danger' }}" id="badge_{{ $feature->ma_chuc_nang }}">
                                        {{ $feature->trang_thai ? 'Hoạt động' : 'Bảo trì' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Chưa có dữ liệu cấu hình.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggles = document.querySelectorAll('.feature-toggle');
        
        toggles.forEach(toggle => {
            toggle.addEventListener('change', function() {
                const id = this.getAttribute('data-id');
                const status = this.checked ? 1 : 0;
                const badge = document.getElementById('badge_' + id);
                
                // Show updating toast
                const toastId = 'toast' + Date.now();
                const toastHtml = `
                    <div id="${toastId}" class="toast align-items-center text-white bg-info border-0" role="alert" aria-live="assertive" aria-atomic="true">
                      <div class="d-flex">
                        <div class="toast-body">Đang cập nhật...</div>
                      </div>
                    </div>
                `;
                document.body.insertAdjacentHTML('beforeend', `<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11">${toastHtml}</div>`);
                const toastEl = document.getElementById(toastId);
                const bsToast = new bootstrap.Toast(toastEl, {delay: 2000});
                bsToast.show();

                fetch(`{{ url('admin/features') }}/${id}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        trang_thai: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        badge.className = 'ms-2 badge ' + (status ? 'bg-success' : 'bg-danger');
                        badge.textContent = status ? 'Hoạt động' : 'Bảo trì';
                        
                        toastEl.classList.remove('bg-info');
                        toastEl.classList.add('bg-success');
                        toastEl.querySelector('.toast-body').textContent = '✅ Đã lưu cấu hình';
                    } else {
                        throw new Error('Lỗi cập nhật');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Revert toggle
                    this.checked = !status;
                    
                    toastEl.classList.remove('bg-info');
                    toastEl.classList.add('bg-danger');
                    toastEl.querySelector('.toast-body').textContent = '❌ Có lỗi xảy ra!';
                });
            });
        });
    });
</script>
@endpush
