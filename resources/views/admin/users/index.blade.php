@extends('layouts.app')

@section('title', 'User Management - Pitocom Admin')

@section('content')
    {{-- Load Font & SweetAlert2 --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .swal2-popup { border-radius: 2.5rem !important; padding: 2rem !important; }
        .swal2-styled.swal2-confirm { background-color: #0f172a !important; border-radius: 1rem !important; padding: 0.75rem 2rem !important; font-weight: 800 !important; text-transform: uppercase !important; letter-spacing: 0.1em !important; }
        .swal2-styled.swal2-cancel { border-radius: 1rem !important; padding: 0.75rem 2rem !important; font-weight: 800 !important; text-transform: uppercase !important; letter-spacing: 0.1em !important; }
    </style>

    {{-- Navbar Admin --}}
    @include('admin.partials.nav')
    
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="userManagement()">
    
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 text-left animate-fade-in-up">
        <div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">User <span class="text-sky-500">Management</span></h1>
            <p class="text-slate-500 mt-2 font-medium text-lg italic">Kelola hak akses, peran, dan tingkat keanggotaan pengguna Pitocom.</p>
            <div class="h-1.5 w-24 bg-sky-500 mt-4 rounded-full"></div>
        </div>
        
        <a href="{{ route('admin.users.create') }}" 
           class="inline-flex items-center px-8 py-4 bg-slate-900 text-white rounded-2xl font-black uppercase tracking-widest hover:bg-sky-500 transition-all shadow-xl shadow-slate-200 active:scale-95 text-sm group">
            <svg class="w-5 h-5 mr-2 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Create New User
        </a>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm overflow-hidden border border-slate-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">User Info</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Role</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Membership</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Joined Date</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($users as $user)
                        {{-- Animasi saat baris dihapus (Layout Transition) --}}
                        <tr x-show="!deletedUsers.includes({{ $user->id }})" 
                            x-transition:leave="transition ease-in duration-300 transform"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95 -translate-x-4"
                            class="hover:bg-slate-50/50 transition-colors">
                            
                            <td class="px-8 py-6 whitespace-nowrap text-left">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-xl bg-slate-900 flex items-center justify-center font-black text-white text-sm shadow-sm mr-4">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-black text-slate-900">{{ $user->name }}</div>
                                        <div class="text-[11px] text-slate-400 font-bold tracking-tight">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-8 py-6 whitespace-nowrap text-left">
                                <span class="px-4 py-1.5 text-[9px] font-black rounded-full uppercase tracking-tighter shadow-sm
                                    {{ $user->isAdmin() ? 'bg-emerald-100 text-emerald-600 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                                    {{ $user->role }}
                                </span>
                            </td>

                            <td class="px-8 py-6 whitespace-nowrap text-left">
                                @php
                                    $level = $user->membership_level;
                                    $badgeStyle = match($level) {
                                        'Platinum' => 'bg-indigo-100 text-indigo-600 border-indigo-200',
                                        'Gold' => 'bg-amber-100 text-amber-600 border-amber-200',
                                        default => 'bg-slate-100 text-slate-400 border-slate-200',
                                    };
                                @endphp
                                <span class="px-4 py-1.5 text-[9px] font-black rounded-full border {{ $badgeStyle }} uppercase tracking-widest shadow-sm">
                                    {{ $level }}
                                </span>
                            </td>

                            <td class="px-8 py-6 whitespace-nowrap text-left">
                                <div class="text-[11px] font-bold text-slate-500 tracking-tight italic text-left">
                                    {{ $user->created_at->format('d M, Y') }}
                                </div>
                            </td>

                            <td class="px-8 py-6 whitespace-nowrap text-right text-left">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-slate-50 text-sky-500 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-sky-500 hover:text-white transition-all shadow-sm">
                                        Edit
                                    </a>
                                    
                                    {{-- Custom Delete Button dengan SweetAlert2 --}}
                                    <button type="button" @click="confirmDelete({{ $user->id }}, '{{ $user->name }}')"
                                            class="inline-flex items-center px-4 py-2 bg-slate-50 text-rose-400 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                                        Delete
                                    </button>

                                    {{-- Hidden Form --}}
                                    <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center text-slate-400 font-bold italic bg-slate-50/20">
                                <svg class="w-12 h-12 mx-auto text-slate-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                Belum ada data pengguna di sistem.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-10">
        {{ $users->links() }}
    </div>
</div>

<script>
function userManagement() {
    return {
        deletedUsers: [],
        
        confirmDelete(userId, userName) {
            Swal.fire({
                title: 'Hapus Akun?',
                text: `Anda akan menghapus "${userName}" secara permanen dari Pitocom.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0f172a',
                cancelButtonColor: '#f1f5f9',
                confirmButtonText: 'YA, HAPUS USER',
                cancelButtonText: 'BATAL',
                reverseButtons: true,
                customClass: {
                    title: 'font-black text-slate-900 uppercase tracking-tight',
                    htmlContainer: 'font-bold text-slate-500',
                    cancelButton: 'text-slate-400 font-black'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Trigger animasi Alpine
                    this.deletedUsers.push(userId);
                    
                    // Delay sebentar agar animasi transisi selesai sebelum form submit (page reload)
                    setTimeout(() => {
                        document.getElementById(`delete-form-${userId}`).submit();
                    }, 400);
                }
            })
        }
    }
}
</script>
@endsection