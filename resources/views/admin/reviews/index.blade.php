@extends('layouts.app')

@section('title', 'Manage Reviews - Pitocom Admin')

@section('content')
    {{-- Load SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        
        .reveal-section {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .reveal-section.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Drag Scroll Styling */
        .drag-container {
            cursor: grab;
            user-select: none;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .drag-container:active {
            cursor: grabbing;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Custom SweetAlert Pitocom Style */
        .swal2-popup { border-radius: 2.5rem !important; padding: 2.5rem !important; }
        .swal2-styled.swal2-confirm { background-color: #0f172a !important; border-radius: 1.2rem !important; padding: 1rem 2.5rem !important; font-weight: 800 !important; text-transform: uppercase !important; letter-spacing: 0.1em !important; font-size: 11px !important; }
        .swal2-styled.swal2-cancel { border-radius: 1.2rem !important; padding: 1rem 2.5rem !important; font-weight: 800 !important; text-transform: uppercase !important; letter-spacing: 0.1em !important; font-size: 11px !important; }
    </style>

    {{-- Navbar Admin --}}
    @include('admin.partials.nav')

<div class="max-w-[1600px] mx-auto px-6 sm:px-10 py-10" x-data="reviewManagement()" x-init="initObserver()">
    
    {{-- Header Section --}}
    <div class="reveal-section mb-12 text-left">
        <h1 class="text-5xl font-black text-slate-900 tracking-tighter uppercase text-left">Manage Product <span class="text-sky-500">Reviews.</span></h1>
        <p class="text-slate-500 mt-3 font-medium text-lg italic pl-1 text-left">Pantau dan kelola ulasan pelanggan untuk menjaga kualitas layanan Pitocom.</p>
        <div class="h-1.5 w-24 bg-sky-500 mt-6 rounded-full text-left"></div>
    </div>

    {{-- Table Container with Drag to Side --}}
    <div class="reveal-section bg-white rounded-[3.5rem] shadow-sm border border-slate-100 overflow-hidden mb-20 text-left">
        
        {{-- Draggable Wrapper --}}
        <div class="drag-container no-scrollbar text-left" 
             @mousedown="startDragging($event)" 
             @mousemove="drag($event)" 
             @mouseup="stopDragging()" 
             @mouseleave="stopDragging()"
             x-ref="scrollContainer">
            
            <table class="min-w-full divide-y divide-slate-50 table-fixed text-left">
                <thead class="bg-slate-50/50 text-left">
                    <tr>
                        <th class="w-64 px-8 py-7 text-[11px] font-black text-slate-400 uppercase tracking-[0.25em] text-left">Hardware Identity</th>
                        <th class="w-48 px-8 py-7 text-[11px] font-black text-slate-400 uppercase tracking-[0.25em] text-left">Customer</th>
                        <th class="w-28 px-8 py-7 text-[11px] font-black text-slate-400 uppercase tracking-[0.25em] text-center text-left">Rating</th>
                        <th class="w-72 px-8 py-7 text-[11px] font-black text-slate-400 uppercase tracking-[0.25em] text-left">Public Comment</th>
                        <th class="w-40 px-8 py-7 text-[11px] font-black text-slate-400 uppercase tracking-[0.25em] text-left">Timestamp</th>
                        <th class="w-36 px-8 py-7 text-[11px] font-black text-slate-400 uppercase tracking-[0.25em] text-right text-left">Ops Hub</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 bg-white">
                    @forelse ($reviews as $review)
                        <tr x-show="!deletedReviews.includes({{ $review->id }})" 
                            x-transition:leave="transition ease-in duration-400 transform"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95 -translate-x-12"
                            class="hover:bg-slate-50/30 transition-all group text-left">
                            
                            <td class="px-8 py-6 text-left">
                                <div class="text-sm font-black text-slate-900 tracking-tight truncate text-left" title="{{ $review->product->name }}">{{ $review->product->name }}</div>
                                <div class="text-[9px] text-slate-400 font-black uppercase tracking-widest mt-1 text-left">Ref: #PRD-{{ $review->product->id }}</div>
                            </td>

                            <td class="px-8 py-6 text-left">
                                <div class="flex items-center text-left">
                                    <div class="h-9 w-9 rounded-xl bg-slate-900 flex items-center justify-center font-black text-white text-xs mr-3 shadow-lg group-hover:bg-sky-500 transition-colors shrink-0">
                                        {{ substr($review->user->name, 0, 1) }}
                                    </div>
                                    <div class="truncate text-left">
                                        <div class="text-xs font-black text-slate-700 truncate text-left">{{ $review->user->name }}</div>
                                        <div class="text-[7px] text-slate-400 font-bold uppercase tracking-tighter text-left">Buyer</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-8 py-6 text-center">
                                <div class="inline-flex items-center bg-amber-50 px-3 py-1 rounded-full border border-amber-100 shadow-sm">
                                    <span class="text-[10px] font-black text-amber-600 mr-1">{{ $review->rating }}</span>
                                    <svg class="w-3 h-3 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.959a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.446a1 1 0 00-.364 1.118l1.287 3.959c.3.921-.755 1.688-1.54 1.118l-3.368-2.446a1 1 0 00-1.175 0l-3.368 2.446c-.784.57-1.838-.197-1.539-1.118l1.287-3.959a1 1 0 00-.364-1.118L2.05 9.386c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69L9.049 2.927z"/></svg>
                                </div>
                            </td>

                            <td class="px-8 py-6 text-left">
                                <p class="text-[12px] text-slate-500 font-medium leading-relaxed italic group-hover:text-slate-700 transition-colors truncate max-w-[220px] text-left" title="{{ $review->comment }}">"{{ $review->comment }}"</p>
                            </td>

                            <td class="px-8 py-6 whitespace-nowrap text-left">
                                <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] text-left">
                                    {{ $review->created_at->format('d M, Y') }}
                                </div>
                            </td>

                            <td class="px-8 py-6 whitespace-nowrap text-right">
                                <div class="flex justify-end items-center">
                                    <button type="button" @click="confirmTerminate({{ $review->id }}, '{{ addslashes($review->user->name) }}')"
                                            class="w-24 py-2 bg-rose-50 text-rose-400 rounded-xl font-black text-[8px] uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all border border-rose-100 shadow-sm active:scale-90 flex justify-center items-center">
                                        Terminate
                                    </button>
                                </div>

                                <form id="delete-form-{{ $review->id }}" action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-10 py-40 text-center bg-slate-50/20">
                                <div class="text-6xl mb-6 grayscale opacity-20">💬</div>
                                <p class="text-slate-300 font-black uppercase tracking-[0.3em] text-xs">Public reviews feed is empty</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-10 py-10 bg-slate-50/30 border-t border-slate-50 text-left">
            {{ $reviews->links() }}
        </div>
    </div>
</div>

<script>
function reviewManagement() {
    return {
        deletedReviews: [],
        isDragging: false,
        startX: 0,
        scrollLeft: 0,

        initObserver() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('visible'); });
            }, { threshold: 0.1 });
            document.querySelectorAll('.reveal-section').forEach(el => observer.observe(el));
        },

        // Horizontal Drag Logic
        startDragging(e) {
            this.isDragging = true;
            this.startX = e.pageX - this.$refs.scrollContainer.offsetLeft;
            this.scrollLeft = this.$refs.scrollContainer.scrollLeft;
        },
        drag(e) {
            if (!this.isDragging) return;
            e.preventDefault();
            const x = e.pageX - this.$refs.scrollContainer.offsetLeft;
            const walk = (x - this.startX) * 2; // Scroll speed
            this.$refs.scrollContainer.scrollLeft = this.scrollLeft - walk;
        },
        stopDragging() {
            this.isDragging = false;
        },

        confirmTerminate(reviewId, userName) {
            Swal.fire({
                title: 'Terminate Review?',
                text: `Ulasan dari pelanggan "${userName}" akan dihapus permanen dari sistem.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0f172a',
                cancelButtonColor: '#f1f5f9',
                confirmButtonText: 'YA, TERMINATE',
                cancelButtonText: 'BATAL',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    this.deletedReviews.push(reviewId);
                    setTimeout(() => { document.getElementById(`delete-form-${reviewId}`).submit(); }, 400);
                }
            })
        }
    }
}
</script>
@endsection