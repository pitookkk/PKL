@extends('layouts.app')

@section('title', 'User Management - Pitocom Admin')

@section('content')
    {{-- Load Font Premium & SweetAlert2 --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        
        /* Animasi Section Reveal */
        .reveal-section {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .reveal-section.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .swal2-popup { border-radius: 2.5rem !important; padding: 2.5rem !important; }
        .swal2-styled.swal2-confirm { background-color: #0f172a !important; border-radius: 1.2rem !important; padding: 1rem 2.5rem !important; font-weight: 800 !important; text-transform: uppercase !important; letter-spacing: 0.1em !important; font-size: 11px !important; }
        .swal2-styled.swal2-cancel { border-radius: 1.2rem !important; padding: 1rem 2.5rem !important; font-weight: 800 !important; text-transform: uppercase !important; letter-spacing: 0.1em !important; font-size: 11px !important; }
    </style>

    {{-- Navbar Admin --}}
    @include('admin.partials.nav')
    
<div class="max-w-[1600px] mx-auto px-6 sm:px-10 py-10" 
     x-data="userManagement()" 
     x-init="initObserver()">
    
    {{-- Header Section --}}
    <div class="reveal-section flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12 text-left">
        <div>
            <h1 class="text-5xl font-black text-slate-900 tracking-tighter">User <span class="text-sky-500">Center.</span></h1>
            <p class="text-slate-500 mt-3 font-medium text-lg italic pl-1">Kelola hak akses, peran, dan tingkat keanggotaan seluruh pengguna Pitocom.</p>
            <div class="h-1.5 w-24 bg-sky-500 mt-6 rounded-full"></div>
        </div>
        
        <a href="{{ route('admin.users.create') }}" 
           class="inline-flex items-center px-10 py-5 bg-slate-900 text-white rounded-[1.8rem] font-black uppercase tracking-[0.15em] hover:bg-sky-500 transition-all shadow-2xl shadow-slate-200 active:scale-95 text-xs group shrink-0">
            <svg class="w-5 h-5 mr-3 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
            Create New Member
        </a>
    </div>

    {{-- Table Container --}}
    <div class="reveal-section bg-white rounded-[3.5rem] shadow-sm border border-slate-100 overflow-hidden" style="transition-delay: 200ms">
        <div class="overflow-x-auto text-left">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-10 py-7 text-left text-[11px] font-black text-slate-400 uppercase tracking-[0.25em]">User Identity</th>
                        <th class="px-10 py-7 text-left text-[11px] font-black text-slate-400 uppercase tracking-[0.25em]">System Role</th>
                        <th class="px-10 py-7 text-left text-[11px] font-black text-slate-400 uppercase tracking-[0.25em]">Membership</th>
                        <th class="px-10 py-7 text-left text-[11px] font-black text-slate-400 uppercase tracking-[0.25em]">Join Date</th>
                        <th class="px-10 py-7 text-right text-[11px] font-black text-slate-400 uppercase tracking-[0.25em]">Actions Hub</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($users as $user)
                        {{-- Animasi Alpine saat hapus --}}
                        <tr x-show="!deletedUsers.includes({{ $user->id }})" 
                            x-transition:leave="transition ease-in duration-300 transform"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95 -translate-x-8"
                            class="hover:bg-slate-50/30 transition-all group">
                            
                            {{-- Identity --}}
                            <td class="px-10 py-7 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 rounded-2xl bg-slate-900 flex items-center justify-center font-black text-white text-base shadow-lg group-hover:bg-sky-500 group-hover:rotate-6 transition-all duration-500">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div class="ml-5">
                                        <div class="text-base font-black text-slate-900 tracking-tight">{{ $user->name }}</div>
                                        <div class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Role --}}
                            <td class="px-10 py-7 whitespace-nowrap">
                                <span class="px-5 py-2 text-[10px] font-black rounded-full uppercase tracking-widest shadow-sm border
                                    {{ $user->isAdmin() ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-slate-50 text-slate-500 border-slate-100' }}">
                                    {{ $user->role }}
                                </span>
                            </td>

                            {{-- Membership --}}
                            <td class="px-10 py-7 whitespace-nowrap">
                                @php
                                    $level = $user->membership_level;
                                    $badgeStyle = match($level) {
                                        'Platinum' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                        'Gold' => 'bg-amber-50 text-amber-600 border-amber-100',
                                        default => 'bg-slate-50 text-slate-400 border-slate-100',
                                    };
                                @endphp
                                <span class="px-5 py-2 text-[10px] font-black rounded-full border {{ $badgeStyle }} uppercase tracking-[0.1em] shadow-sm">
                                    {{ $level }}
                                </span>
                            </td>

                            {{-- Joined Date --}}
                            <td class="px-10 py-7 whitespace-nowrap">
                                <div class="text-[12px] font-black text-slate-400 tracking-tighter uppercase">
                                    {{ $user->created_at->format('M d, Y') }}
                                </div>
                            </td>

                            {{-- Actions --}}
                            <td class="px-10 py-7 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end space-x-3">
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="p-2.5 bg-slate-50 text-slate-400 hover:text-sky-500 hover:bg-white hover:shadow-md rounded-xl transition-all border border-slate-100">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                    </a>
                                    
                                    <button type="button" @click="confirmDelete({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                            class="p-2.5 bg-slate-50 text-slate-400 hover:text-rose-500 hover:bg-white hover:shadow-md rounded-xl transition-all border border-slate-100">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>

                                    <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-10 py-32 text-center bg-slate-50/20">
                                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm border border-slate-100">
                                    <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <p class="text-slate-400 font-black uppercase tracking-[0.2em]">Zero Users Found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    {{-- Pagination --}}
    <div class="mt-12 flex justify-center reveal-section">
        {{ $users->links() }}
    </div>
</div>

<script>
function userManagement() {
    return {
        deletedUsers: [],
        
        initObserver() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) entry.target.classList.add('visible');
                });
            }, { threshold: 0.1 });
            document.querySelectorAll('.reveal-section').forEach(el => observer.observe(el));
        },
        
        confirmDelete(userId, userName) {
            Swal.fire({
                title: 'Terminate User?',
                text: `Akun "${userName}" akan dihapus permanen dari database Pitocom.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0f172a',
                cancelButtonColor: '#f1f5f9',
                confirmButtonText: 'YA, HAPUS AKUN',
                cancelButtonText: 'BATAL',
                reverseButtons: true,
                customClass: {
                    title: 'font-black text-slate-900 uppercase tracking-tight',
                    htmlContainer: 'font-bold text-slate-500',
                    cancelButton: 'text-slate-400 font-black'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.deletedUsers.push(userId);
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