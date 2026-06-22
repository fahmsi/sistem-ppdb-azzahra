@extends('layouts.app')
@section('title', 'Kelola Admin')

@section('content')
@php
    $oldMode = old('form_mode');
    $oldEditId = old('update_user_id');
@endphp

<div class="space-y-5">
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="sneat-card p-5 flex items-center justify-between gap-4">
            <div>
                <p class="text-sm text-[#a1b0cb]">Total Admin</p>
                <p class="text-2xl font-bold text-[#566a7f] dark:text-[#d5d5e2] mt-1">{{ $stats['total'] }}</p>
            </div>
            <span class="w-11 h-11 rounded-md bg-[#e7e7ff] dark:bg-[#696cff]/20 text-[#696cff] flex items-center justify-center">
                <i data-lucide="users" class="w-5 h-5"></i>
            </span>
        </div>
        <div class="sneat-card p-5 flex items-center justify-between gap-4">
            <div>
                <p class="text-sm text-[#a1b0cb]">Admin Aktif</p>
                <p class="text-2xl font-bold text-[#566a7f] dark:text-[#d5d5e2] mt-1">{{ $stats['active'] }}</p>
            </div>
            <span class="w-11 h-11 rounded-md bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                <i data-lucide="user-check" class="w-5 h-5"></i>
            </span>
        </div>
        <div class="sneat-card p-5 flex items-center justify-between gap-4">
            <div>
                <p class="text-sm text-[#a1b0cb]">Admin Suspended</p>
                <p class="text-2xl font-bold text-[#566a7f] dark:text-[#d5d5e2] mt-1">{{ $stats['suspended'] }}</p>
            </div>
            <span class="w-11 h-11 rounded-md bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 flex items-center justify-center">
                <i data-lucide="user-x" class="w-5 h-5"></i>
            </span>
        </div>
        <div class="sneat-card p-5 flex items-center justify-between gap-4">
            <div>
                <p class="text-sm text-[#a1b0cb]">Super Admin</p>
                <p class="text-2xl font-bold text-[#566a7f] dark:text-[#d5d5e2] mt-1">{{ $stats['super_admin'] }}</p>
            </div>
            <span class="w-11 h-11 rounded-md bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                <i data-lucide="crown" class="w-5 h-5"></i>
            </span>
        </div>
    </div>

    <div class="admin-table-card">
        <div class="admin-table-header">
            <h2 class="admin-table-title">Daftar Admin</h2>

            <form method="GET" action="{{ route('admin.kelola-admin.index') }}" class="admin-table-toolbar">
                <div class="admin-table-search relative">
                        <i data-lucide="search" class="w-4 h-4 text-[#a1b0cb] absolute left-3 top-1/2 -translate-y-1/2"></i>
                    <input type="search" name="search" value="{{ request('search') }}" class="sneat-input h-10 !pl-10" placeholder="Cari nama, email, telepon">
                </div>

                <div class="admin-table-actions">
                    <select name="role" class="sneat-input h-10 sm:w-[170px]">
                        <option value="">Semua Role</option>
                        <option value="super_admin" @selected(request('role') === 'super_admin')>Super Admin</option>
                        <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                    </select>
                    <select name="status" class="sneat-input h-10 sm:w-[170px]">
                        <option value="">Semua Status</option>
                        <option value="active" @selected(request('status') === 'active')>Aktif</option>
                        <option value="suspended" @selected(request('status') === 'suspended')>Suspended</option>
                    </select>
                    <button type="submit" class="sneat-btn-secondary h-10 justify-center admin-table-action-btn">
                        <i data-lucide="filter" class="w-4 h-4"></i> Filter
                    </button>
                    @if(request()->hasAny(['search', 'role', 'status']))
                        <a href="{{ route('admin.kelola-admin.index') }}" class="sneat-btn-secondary h-10 justify-center admin-table-action-btn">
                            <i data-lucide="x" class="w-4 h-4"></i> Reset
                        </a>
                    @endif
                    <button type="button" class="sneat-btn-primary h-10 justify-center admin-table-action-btn" data-open-admin-drawer="create">
                        <i data-lucide="user-plus" class="w-4 h-4"></i> Tambah Admin
                    </button>
                </div>
            </form>
        </div>

        <div class="admin-table-responsive text-nowrap">
            <table class="table table-hover align-middle admin-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No Telepon</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $i => $admin)
                        @php
                            $isSelf = $admin->id === auth()->id();
                            $isLastActiveSuperAdmin = $admin->role === 'super_admin' && ! $admin->isSuspended() && $activeSuperAdminCount <= 1;
                            $canSuspend = ! $isSelf && ! $admin->isSuspended() && ! $isLastActiveSuperAdmin;
                            $canUnsuspend = ! $isSelf && $admin->isSuspended();
                        @endphp
                        <tr>
                            <td>{{ $admins->firstItem() + $i }}</td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-[#e7e7ff] dark:bg-[#696cff]/20 flex items-center justify-center text-[#696cff] font-bold text-sm uppercase">{{ substr($admin->name, 0, 1) }}</div>
                                    <div>
                                        <p class="font-medium text-[#566a7f] dark:text-[#d5d5e2]">{{ $admin->name }}</p>
                                        @if($isSelf)
                                            <span class="text-xs text-[#696cff] font-medium">(Anda)</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $admin->email }}</td>
                            <td>{{ $admin->no_telpon ?: '-' }}</td>
                            <td>
                                @if($admin->role === 'super_admin')
                                    <span class="sneat-badge bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400"><i data-lucide="crown" class="w-3 h-3"></i> Super Admin</span>
                                @else
                                    <span class="sneat-badge bg-slate-100 dark:bg-slate-500/10 text-slate-600 dark:text-slate-300"><i data-lucide="shield" class="w-3 h-3"></i> Admin</span>
                                @endif
                            </td>
                            <td>
                                @if($admin->isSuspended())
                                    <span class="sneat-badge bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400"><i data-lucide="pause-circle" class="w-3 h-3"></i> Suspended</span>
                                @else
                                    <span class="sneat-badge bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400"><i data-lucide="check-circle" class="w-3 h-3"></i> Aktif</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap">{{ $admin->created_at->format('d M Y') }}</td>
                            <td class="text-right admin-table-actions-cell">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-[#f5f5f9] dark:bg-[#232333] text-[#697a8d] hover:bg-blue-50 dark:hover:bg-blue-500/10 hover:text-blue-600 transition-colors btn-edit-admin"
                                        title="Edit"
                                        data-id="{{ $admin->id }}"
                                        data-name="{{ $admin->name }}"
                                        data-email="{{ $admin->email }}"
                                        data-phone="{{ $admin->no_telpon }}"
                                        data-role="{{ $admin->role }}">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </button>

                                    @if($canSuspend)
                                        <form action="{{ route('admin.kelola-admin.suspend', $admin->id) }}" method="POST" class="inline suspend-form">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="suspend_reason">
                                            <button type="button" class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-[#f5f5f9] dark:bg-[#232333] text-[#697a8d] hover:bg-orange-50 dark:hover:bg-orange-500/10 hover:text-orange-600 transition-colors btn-suspend" title="Suspend">
                                                <i data-lucide="user-x" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    @elseif($canUnsuspend)
                                        <form action="{{ route('admin.kelola-admin.unsuspend', $admin->id) }}" method="POST" class="inline unsuspend-form">
                                            @csrf
                                            @method('PATCH')
                                            <button type="button" class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-[#f5f5f9] dark:bg-[#232333] text-[#697a8d] hover:bg-emerald-50 dark:hover:bg-emerald-500/10 hover:text-emerald-600 transition-colors btn-unsuspend" title="Aktifkan kembali">
                                                <i data-lucide="user-check" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if(! $isSelf)
                                        <form action="{{ route('admin.kelola-admin.destroy', $admin->id) }}" method="POST" class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-[#f5f5f9] dark:bg-[#232333] text-[#697a8d] hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-600 transition-colors btn-delete" title="Hapus">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-[#a1b0cb]">Belum ada data admin.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($admins->hasPages())
            <div class="px-6 py-4 border-t border-[#d9dee3] dark:border-[#434463]">{{ $admins->links() }}</div>
        @endif
    </div>
</div>

<div id="adminDrawerBackdrop" class="fixed inset-0 bg-black/40 z-[9998] hidden opacity-0 transition-opacity"></div>
<aside id="adminDrawer" class="fixed top-0 right-0 h-full w-full max-w-md bg-white dark:bg-[#2b2c40] z-[9999] translate-x-full transition-transform duration-300 shadow-2xl flex flex-col">
    <div class="px-5 py-4 border-b border-[#d9dee3] dark:border-[#434463] flex items-center justify-between">
        <div>
            <h3 id="adminDrawerTitle" class="text-lg font-semibold text-[#566a7f] dark:text-[#d5d5e2]">Tambah Admin</h3>
            <p id="adminDrawerSubtitle" class="text-sm text-[#a1b0cb] mt-1">Buat akun admin baru.</p>
        </div>
        <button type="button" class="w-9 h-9 rounded-md hover:bg-[#f5f5f9] dark:hover:bg-[#232333] text-[#697a8d] dark:text-[#a1b0cb] flex items-center justify-center" data-close-admin-drawer title="Tutup">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto p-5">
        @if($errors->any())
            <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-300">
                <p class="font-semibold mb-1">Periksa kembali data admin.</p>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="adminDrawerForm" method="POST" action="{{ route('admin.kelola-admin.store') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="_method" id="adminFormMethod" value="POST" disabled>
            <input type="hidden" name="form_mode" id="adminFormMode" value="create">
            <input type="hidden" name="update_user_id" id="adminUpdateUserId" value="">

            <div>
                <label for="adminName" class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-2">Nama Lengkap</label>
                <input type="text" id="adminName" name="name" value="{{ old('name') }}" required class="sneat-input @error('name') !border-red-500 @enderror" placeholder="Nama lengkap admin">
            </div>

            <div>
                <label for="adminEmail" class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-2">Email</label>
                <input type="email" id="adminEmail" name="email" value="{{ old('email') }}" required class="sneat-input @error('email') !border-red-500 @enderror" placeholder="admin@contoh.com">
            </div>

            <div>
                <label for="adminPhone" class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-2">No Telepon/WhatsApp</label>
                <input type="text" id="adminPhone" name="no_telpon" value="{{ old('no_telpon') }}" class="sneat-input @error('no_telpon') !border-red-500 @enderror" placeholder="08xxxxxxxxxx">
            </div>

            <div>
                <label for="adminRole" class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-2">Role</label>
                <select id="adminRole" name="role" required class="sneat-input @error('role') !border-red-500 @enderror">
                    <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                    <option value="super_admin" @selected(old('role') === 'super_admin')>Super Admin</option>
                </select>
            </div>

            <div>
                <label for="adminPassword" class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-2">Password</label>
                <input type="password" id="adminPassword" name="password" class="sneat-input @error('password') !border-red-500 @enderror" placeholder="Minimal 8 karakter">
                <p id="adminPasswordHelp" class="text-xs text-[#a1b0cb] mt-1 hidden">Kosongkan jika tidak ingin mengganti password.</p>
            </div>

            <div>
                <label for="adminPasswordConfirmation" class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-2">Konfirmasi Password</label>
                <input type="password" id="adminPasswordConfirmation" name="password_confirmation" class="sneat-input" placeholder="Ulangi password">
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" class="sneat-btn-secondary" data-close-admin-drawer>Batal</button>
                <button type="submit" class="sneat-btn-primary">
                    <i data-lucide="save" class="w-4 h-4"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</aside>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const drawer = document.getElementById('adminDrawer');
    const backdrop = document.getElementById('adminDrawerBackdrop');
    const form = document.getElementById('adminDrawerForm');
    const methodInput = document.getElementById('adminFormMethod');
    const modeInput = document.getElementById('adminFormMode');
    const updateUserIdInput = document.getElementById('adminUpdateUserId');
    const title = document.getElementById('adminDrawerTitle');
    const subtitle = document.getElementById('adminDrawerSubtitle');
    const password = document.getElementById('adminPassword');
    const passwordHelp = document.getElementById('adminPasswordHelp');
    const storeAction = @json(route('admin.kelola-admin.store'));
    const updateBaseAction = @json(url('/admin/kelola-admin'));
    const oldMode = @json($oldMode);
    const oldEditId = @json($oldEditId);

    function openDrawer() {
        backdrop.classList.remove('hidden');
        setTimeout(function() {
            backdrop.classList.remove('opacity-0');
            drawer.classList.remove('translate-x-full');
        }, 10);
        document.body.classList.add('overflow-hidden');
    }

    function closeDrawer() {
        drawer.classList.add('translate-x-full');
        backdrop.classList.add('opacity-0');
        setTimeout(function() {
            backdrop.classList.add('hidden');
        }, 250);
        document.body.classList.remove('overflow-hidden');
    }

    function setCreateMode() {
        form.action = storeAction;
        methodInput.disabled = true;
        modeInput.value = 'create';
        updateUserIdInput.value = '';
        title.textContent = 'Tambah Admin';
        subtitle.textContent = 'Buat akun admin baru.';
        password.required = true;
        passwordHelp.classList.add('hidden');
        form.reset();
        document.getElementById('adminRole').value = 'admin';
    }

    function setEditMode(data) {
        form.action = updateBaseAction + '/' + data.id;
        methodInput.disabled = false;
        methodInput.value = 'PUT';
        modeInput.value = 'edit';
        updateUserIdInput.value = data.id;
        title.textContent = 'Edit Admin';
        subtitle.textContent = 'Perbarui data akun admin.';
        document.getElementById('adminName').value = data.name || '';
        document.getElementById('adminEmail').value = data.email || '';
        document.getElementById('adminPhone').value = data.phone || '';
        document.getElementById('adminRole').value = data.role || 'admin';
        password.value = '';
        document.getElementById('adminPasswordConfirmation').value = '';
        password.required = false;
        passwordHelp.classList.remove('hidden');
    }

    document.querySelectorAll('[data-open-admin-drawer="create"]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            setCreateMode();
            openDrawer();
        });
    });

    document.querySelectorAll('.btn-edit-admin').forEach(function(btn) {
        btn.addEventListener('click', function() {
            setEditMode(btn.dataset);
            openDrawer();
        });
    });

    document.querySelectorAll('[data-close-admin-drawer]').forEach(function(btn) {
        btn.addEventListener('click', closeDrawer);
    });
    backdrop.addEventListener('click', closeDrawer);
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDrawer();
        }
    });

    document.querySelectorAll('.btn-delete').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const form = this.closest('.delete-form');
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: 'Data admin ini akan dihapus permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#697a8d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    document.querySelectorAll('.btn-suspend').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const form = this.closest('.suspend-form');
            Swal.fire({
                title: 'Suspend admin?',
                input: 'text',
                inputLabel: 'Alasan suspend (opsional)',
                inputPlaceholder: 'Contoh: sementara tidak bertugas',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#697a8d',
                confirmButtonText: 'Ya, Suspend',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    form.querySelector('[name="suspend_reason"]').value = result.value || '';
                    form.submit();
                }
            });
        });
    });

    document.querySelectorAll('.btn-unsuspend').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const form = this.closest('.unsuspend-form');
            Swal.fire({
                title: 'Aktifkan admin?',
                text: 'Admin ini dapat login kembali setelah diaktifkan.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#697a8d',
                confirmButtonText: 'Ya, Aktifkan',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    if (oldMode === 'create') {
        title.textContent = 'Tambah Admin';
        subtitle.textContent = 'Periksa kembali data admin.';
        password.required = true;
        passwordHelp.classList.add('hidden');
        openDrawer();
    }

    if (oldMode === 'edit' && oldEditId) {
        const editButton = document.querySelector('.btn-edit-admin[data-id="' + oldEditId + '"]');
        if (editButton) {
            setEditMode(editButton.dataset);
            document.getElementById('adminName').value = @json(old('name'));
            document.getElementById('adminEmail').value = @json(old('email'));
            document.getElementById('adminPhone').value = @json(old('no_telpon'));
            document.getElementById('adminRole').value = @json(old('role', 'admin'));
            openDrawer();
        }
    }
});
</script>
@endsection
