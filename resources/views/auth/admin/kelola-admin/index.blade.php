@extends('layouts.app')
@section('title', 'Kelola Admin')
@section('header_title', 'Kelola Admin')
@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Breadcrumb -->
    <nav class="text-sm text-[#a1b0cb] dark:text-[#7e8ba1] font-medium tracking-wide mb-6">
        Dashboard <span class="mx-1">&gt;</span> Admin <span class="mx-1">&gt;</span> <span class="text-[#697a8d] dark:text-[#b4bdc6] font-semibold">Kelola Admin</span>
    </nav>

    <!-- Header Card -->
    <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h2 class="text-xl font-bold text-[#566a7f] dark:text-[#d5d5e2] flex items-center gap-2 m-0">
            Kelola Admin
        </h2>
        <a href="{{ route('admin.kelola-admin.create') }}" class="sneat-btn-primary"><i data-lucide="user-plus" class="w-4 h-4"></i> Tambah Admin</a>
    </div>
    <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] overflow-hidden">
        <div class="w-full overflow-x-auto">
            <table class="sneat-table w-full table-auto whitespace-nowrap">
                <thead><tr><th>#</th><th>Nama</th><th>Email</th><th>Role</th><th>Dibuat</th><th class="text-right">Aksi</th></tr></thead>
                <tbody>
                    @forelse($admins as $i => $admin)
                    <tr>
                        <td>{{ $admins->firstItem() + $i }}</td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-[#e7e7ff] dark:bg-[#696cff]/20 flex items-center justify-center text-[#696cff] font-bold text-sm uppercase">{{ substr($admin->name, 0, 1) }}</div>
                                <div>
                                    <p class="font-medium text-[#566a7f] dark:text-[#d5d5e2]">{{ $admin->name }}</p>
                                    @if($admin->id === auth()->id())<span class="text-xs text-[#696cff] font-medium">(Anda)</span>@endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $admin->email }}</td>
                        <td>
                            @if($admin->role === 'super_admin')
                                <span class="sneat-badge bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400"><i data-lucide="crown" class="w-3 h-3"></i> Super Admin</span>
                            @else
                                <span class="sneat-badge bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400"><i data-lucide="shield" class="w-3 h-3"></i> Admin</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap">{{ $admin->created_at->format('d M Y') }}</td>
                        <td class="text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.kelola-admin.edit', $admin->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-[#f5f5f9] dark:bg-[#232333] text-[#697a8d] hover:bg-blue-50 dark:hover:bg-blue-500/10 hover:text-blue-600 transition-colors" title="Edit"><i data-lucide="edit-3" class="w-4 h-4"></i></a>
                                @if($admin->id !== auth()->id())
                                <form action="{{ route('admin.kelola-admin.destroy', $admin->id) }}" method="POST" class="inline delete-form">@csrf @method('DELETE')
                                    <button type="button" class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-[#f5f5f9] dark:bg-[#232333] text-[#697a8d] hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-600 transition-colors btn-delete" title="Hapus"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-8 text-center text-[#a1b0cb]">Belum ada data admin.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($admins->hasPages())<div class="px-6 py-4 border-t border-[#d9dee3] dark:border-[#434463]">{{ $admins->links() }}</div>@endif
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-delete').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var form = this.closest('.delete-form');
            Swal.fire({ title: 'Yakin ingin menghapus?', text: 'Data admin ini akan dihapus permanen.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#697a8d', confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal' }).then((result) => { if (result.isConfirmed) form.submit(); });
        });
    });
});
</script>
@endsection
