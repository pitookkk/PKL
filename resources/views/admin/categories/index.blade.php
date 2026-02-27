@extends('layouts.app')

@section('title', 'Manage Categories - Pitocom Admin')

@section('content')
    {{-- Load Font & SweetAlert2 --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        
        /* Animasi Section Reveal */
        .reveal-section {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .reveal-section.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .no-scrollbar::-webkit-scrollbar { display: none; }

        /* Custom SweetAlert sesuai standar Pitocom */
        .swal2-popup { border-radius: 2.5rem !important; padding: 2.5rem !important; }
        .swal2-styled.swal2-confirm { background-color: #0f172a !important; border-radius: 1.2rem !important; padding: 1rem 2.5rem !important; font-weight: 800 !important; text-transform: uppercase !important; letter-spacing: 0.1em !important; font-size: 11px !important; }
        .swal2-styled.swal2-cancel { border-radius: 1.2rem !important; padding: 1rem 2.5rem !important; font-weight: 800 !important; text-transform: uppercase !important; letter-spacing: 0.1em !important; font-size: 11px !important; }
    </style>

    {{-- Navbar Admin --}}
    @include('admin.partials.nav')

    <div class="max-w-[1600px] mx-auto px-6 sm:px-10 py-10" 
         x-data="categoryManagement()" 
         x-init="initObserver()">
        
        {{-- Header Section --}}
        <div class="reveal-section flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12 text-left">
            <div>
                <h1 class="text-5xl font-black text-slate-900 tracking-tighter uppercase">Product <span class="text-sky-500">Categories.</span></h1>
                <p class="text-slate-500 mt-3 font-medium text-lg italic pl-1">Atur struktur kategori untuk mempermudah navigasi pelanggan.</p>
                <div class="h-1.5 w-24 bg-sky-500 mt-6 rounded-full text-left"></div>
            </div>
            
            <a href="{{ route('admin.categories.create') }}" 
               class="inline-flex items-center justify-center bg-slate-900 text-white px-10 py-5 rounded-[1.8rem] font-black uppercase tracking-[0.15em] shadow-2xl shadow-slate-200 hover:bg-sky-500 transition-all active:scale-95 text-xs group shrink-0">
                <svg class="w-5 h-5 mr-3 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                Deploy New Category
            </a>
        </div>

        {{-- Table Card --}}
        <div class="reveal-section bg-white rounded-[3.5rem] shadow-sm border border-slate-100 overflow-hidden mb-20" style="transition-delay: 200ms">
            <div class="overflow-x-auto text-left">
                <table class="w-full text-left table-fixed">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="w-72 px-10 py-7 text-[11px] font-black text-slate-400 uppercase tracking-[0.25em] text-left">Identity</th>
                            <th class="w-64 px-10 py-7 text-[11px] font-black text-slate-400 uppercase tracking-[0.25em] text-left">URL Slug</th>
                            <th class="w-64 px-10 py-7 text-[11px] font-black text-slate-400 uppercase tracking-[0.25em] text-left">Mapping</th>
                            <th class="w-40 px-10 py-7 text-[11px] font-black text-slate-400 uppercase tracking-[0.25em] text-right text-left">Ops Hub</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($categories as $category)
                            <tr x-show="!deletedCategories.includes({{ $category->id }})" 
                                x-transition:leave="transition ease-in duration-300 transform"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95 -translate-x-8"
                                class="hover:bg-slate-50/30 transition-all group text-left">
                                
                                {{-- Name & Initial --}}
                                <td class="px-10 py-6 text-left">
                                    <div class="flex items-center space-x-5 text-left">
                                        <div class="w-12 h-12 bg-slate-100 text-slate-400 rounded-2xl flex items-center justify-center font-black text-lg group-hover:bg-sky-500 group-hover:text-white transition-all duration-500 shadow-inner">
                                            {{ substr($category->name, 0, 1) }}
                                        </div>
                                        <span class="font-black text-slate-900 text-lg tracking-tight truncate text-left">{{ $category->name }}</span>
                                    </div>
                                </td>

                                {{-- Slug --}}
                                <td class="px-10 py-6 text-left">
                                    <code class="text-[10px] bg-slate-50 border border-slate-100 px-3 py-1.5 rounded-lg text-slate-400 font-bold tracking-wider text-left">
                                        /{{ $category->slug }}
                                    </code>
                                </td>

                                {{-- Parent --}}
                                <td class="px-10 py-6 text-left text-left">
                                    @if($category->parent)
                                        <span class="inline-flex items-center px-4 py-1.5 bg-sky-50 text-sky-600 text-[9px] font-black uppercase tracking-widest rounded-full border border-sky-100 shadow-sm text-left">
                                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                            {{ $category->parent->name }}
                                        </span>
                                    @else
                                        <span class="text-slate-300 text-[10px] font-black uppercase tracking-[0.2em] italic text-left">Root Master</span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-10 py-6 text-right text-left">
                                    <div class="flex items-center justify-end space-x-3 text-left">
                                        <a href="{{ route('admin.categories.edit', $category) }}" 
                                           class="p-3 text-slate-300 hover:text-sky-500 hover:bg-white hover:shadow-lg rounded-xl transition-all border border-slate-100 text-left">
                                            <svg class="w-5 h-5 text-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                        </a>
                                        
                                        <button type="button" @click="confirmDelete({{ $category->id }}, '{{ addslashes($category->name) }}')"
                                                class="p-3 text-slate-300 hover:text-rose-500 hover:bg-white hover:shadow-lg rounded-xl transition-all border border-slate-100 text-left">
                                            <svg class="w-5 h-5 text-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>

                                        <form id="delete-form-{{ $category->id }}" action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="hidden text-left">
                                            @csrf @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-10 py-32 text-center bg-slate-50/20 text-left">
                                    <p class="text-slate-400 font-black uppercase tracking-widest text-left">Structure Is Empty</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-10 py-10 bg-slate-50/30 border-t border-slate-50 text-left">
                {{ $categories->links() }}
            </div>
        </div>
    </div>

<script>
function categoryManagement() {
    return {
        deletedCategories: [],
        
        initObserver() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) entry.target.classList.add('visible');
                });
            }, { threshold: 0.1 });
            document.querySelectorAll('.reveal-section').forEach(el => observer.observe(el));
        },
        
        confirmDelete(categoryId, categoryName) {
            Swal.fire({
                title: 'Terminate Category?',
                text: `Kategori "${categoryName}" dan mapping terkait akan dihapus permanen.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0f172a',
                cancelButtonColor: '#f1f5f9',
                confirmButtonText: 'YA, HAPUS',
                cancelButtonText: 'BATAL',
                reverseButtons: true,
                customClass: {
                    title: 'font-black text-slate-900 uppercase tracking-tight',
                    htmlContainer: 'font-bold text-slate-500',
                    cancelButton: 'text-slate-400 font-black'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.deletedCategories.push(categoryId);
                    setTimeout(() => {
                        document.getElementById(`delete-form-${categoryId}`).submit();
                    }, 400);
                }
            })
        }
    }
}
</script>
@endsection