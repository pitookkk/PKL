@extends('layouts.app')

@section('title', 'Manage Reviews - Pitocom Admin')

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

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="reviewManagement()">
    
    {{-- Header Section --}}
    <div class="mb-10 text-left animate-fade-in-up">
        <h1 class="text-4xl font-black text-slate-900 tracking-tight">Manage Product <span class="text-sky-500">Reviews</span></h1>
        <p class="text-slate-500 mt-2 font-medium text-lg italic">Pantau dan kelola ulasan pelanggan untuk menjaga kualitas layanan Pitocom.</p>
        <div class="h-1.5 w-24 bg-sky-500 mt-4 rounded-full"></div>
    </div>

    {{-- Table Container --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm overflow-hidden border border-slate-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Product Info</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Customer</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Rating</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Comment</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Posted Date</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($reviews as $review)
                        {{-- Exit Animation Alpine.js --}}
                        <tr x-show="!deletedReviews.includes({{ $review->id }})" 
                            x-transition:leave="transition ease-in duration-300 transform"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95 -translate-x-4"
                            class="hover:bg-slate-50/50 transition-colors">
                            
                            {{-- Product --}}
                            <td class="px-8 py-6 whitespace-nowrap text-left">
                                <div class="text-sm font-black text-slate-900">{{ Str::limit($review->product->name, 30) }}</div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter italic">ID: #{{ $review->product->id }}</div>
                            </td>

                            {{-- User --}}
                            <td class="px-8 py-6 whitespace-nowrap text-left">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-lg bg-slate-900 flex items-center justify-center font-black text-white text-xs mr-3 shadow-sm">
                                        {{ substr($review->user->name, 0, 1) }}
                                    </div>
                                    <div class="text-sm font-bold text-slate-700">{{ $review->user->name }}</div>
                                </div>
                            </td>

                            {{-- Rating --}}
                            <td class="px-8 py-6 whitespace-nowrap text-left">
                                <div class="flex items-center bg-amber-50 px-3 py-1 rounded-full border border-amber-100 w-fit shadow-sm">
                                    <span class="text-sm font-black text-amber-600 mr-1">{{ $review->rating }}</span>
                                    <svg class="w-3.5 h-3.5 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.959a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.446a1 1 0 00-.364 1.118l1.287 3.959c.3.921-.755 1.688-1.54 1.118l-3.368-2.446a1 1 0 00-1.175 0l-3.368 2.446c-.784.57-1.838-.197-1.539-1.118l1.287-3.959a1 1 0 00-.364-1.118L2.05 9.386c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69L9.049 2.927z"/></svg>
                                </div>
                            </td>

                            {{-- Comment --}}
                            <td class="px-8 py-6 text-left">
                                <p class="text-[13px] text-slate-500 font-medium leading-relaxed italic">"{{ Str::limit($review->comment, 60) }}"</p>
                            </td>

                            {{-- Date --}}
                            <td class="px-8 py-6 whitespace-nowrap text-left text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                                {{ $review->created_at->format('d M, Y') }}
                            </td>

                            {{-- Actions --}}
                            <td class="px-8 py-6 whitespace-nowrap text-right">
                                <button type="button" @click="confirmDelete({{ $review->id }}, '{{ addslashes($review->user->name) }}')"
                                        class="inline-flex items-center px-6 py-2 bg-slate-50 text-rose-400 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all shadow-sm active:scale-95">
                                    Delete
                                </button>

                                <form id="delete-form-{{ $review->id }}" action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center text-slate-400 font-bold italic bg-slate-50/20">
                                <svg class="w-12 h-12 mx-auto text-slate-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                Belum ada ulasan masuk untuk saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    {{-- Pagination bergaya Pitocom --}}
    <div class="mt-10">
        {{ $reviews->links() }}
    </div>
</div>

<script>
function reviewManagement() {
    return {
        deletedReviews: [],
        
        confirmDelete(reviewId, userName) {
            Swal.fire({
                title: 'Hapus Ulasan?',
                text: `Anda akan menghapus ulasan dari "${userName}" secara permanen.`,
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
                    // Trigger Alpine.js Exit Animation
                    this.deletedReviews.push(reviewId);
                    
                    // Delay for animation duration
                    setTimeout(() => {
                        document.getElementById(`delete-form-${reviewId}`).submit();
                    }, 400);
                }
            })
        }
    }
}
</script>
@endsectionba