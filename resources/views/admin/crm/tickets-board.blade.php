@extends('layouts.app')

@section('title', 'Service Tickets Hub - Pitocom Admin')

@section('content')
    {{-- Navbar Admin --}}
    @include('admin.partials.nav')

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

    /* Custom Scrollbar untuk Kolom Kanban */
    .kanban-column::-webkit-scrollbar { width: 4px; }
    .kanban-column::-webkit-scrollbar-track { background: transparent; }
    .kanban-column::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>
    
<div class="max-w-[1600px] mx-auto px-6 sm:px-10 py-10" 
     x-data="ticketsBoard()" 
     x-init="initObserver(); tickets = JSON.parse($el.getAttribute('data-tickets'))"
     data-tickets="{{ json_encode($tickets) }}">
    
    {{-- CRM Navigation --}}
    <div class="reveal-section mb-10">
        @include('admin.crm.partials.navbar')
    </div>

    {{-- Header Section --}}
    <div class="reveal-section mb-12 text-left">
        <h1 class="text-4xl font-black text-slate-900 tracking-tighter uppercase">Service <span class="text-sky-500">Tickets.</span></h1>
        <p class="text-slate-400 mt-2 font-bold text-xs uppercase tracking-widest pl-1">Geser dan lepas tiket untuk memperbarui status perbaikan secara real-time.</p>
        <div class="h-1 w-20 bg-sky-500 mt-5 rounded-full text-left"></div>
    </div>

    {{-- Kanban Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 items-start">
        
        @php
            $columns = [
                'open' => ['label' => 'New Request', 'bg' => 'bg-slate-50', 'accent' => 'bg-sky-500', 'text' => 'text-slate-500'],
                'processing' => ['label' => 'In Progress', 'bg' => 'bg-amber-50/50', 'accent' => 'bg-amber-500', 'text' => 'text-amber-600'],
                'finished' => ['label' => 'Quality Check', 'bg' => 'bg-emerald-50/50', 'accent' => 'bg-emerald-500', 'text' => 'text-emerald-600'],
                'closed' => ['label' => 'Archive', 'bg' => 'bg-rose-50/30', 'accent' => 'bg-rose-400', 'text' => 'text-rose-400'],
            ];
        @endphp

        @foreach($columns as $status => $style)
            <div class="reveal-section {{ $style['bg'] }} p-6 rounded-[2.5rem] border border-slate-100 flex flex-col h-full min-h-[700px] transition-all duration-500" 
                 @drop.prevent="handleDrop($event, '{{ $status }}')" 
                 @dragover.prevent="dragOverColumn = '{{ $status }}'"
                 @dragleave="dragOverColumn = null"
                 :class="{ 'ring-4 ring-sky-500/10 scale-[1.02] bg-white': dragOverColumn === '{{ $status }}' }">
                
                {{-- Column Header --}}
                <div class="flex items-center justify-between mb-8 px-2">
                    <div class="flex items-center text-left">
                        <div class="w-2.5 h-2.5 rounded-full {{ $style['accent'] }} mr-3 animate-pulse shadow-sm"></div>
                        <h2 class="text-[11px] font-black {{ $style['text'] }} uppercase tracking-[0.2em]">{{ $style['label'] }}</h2>
                    </div>
                    <span class="bg-white px-3 py-1 rounded-xl text-[10px] font-black text-slate-400 shadow-sm border border-slate-50" x-text="filteredTickets('{{ $status }}').length"></span>
                </div>

                {{-- Ticket Container --}}
                <div class="space-y-5 flex-grow kanban-column overflow-y-auto pr-1">
                    <template x-for="ticket in filteredTickets('{{ $status }}')" :key="ticket.id">
                        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-50 cursor-grab active:cursor-grabbing hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 group relative overflow-hidden" 
                             draggable="true" 
                             @dragstart="handleDragStart($event, ticket.id)">
                            
                            <div class="absolute top-0 left-0 w-1 h-full {{ $style['accent'] }} opacity-20"></div>

                            {{-- Priority & Meta --}}
                            <div class="flex items-center justify-between mb-4">
                                <span class="px-3 py-1 text-[8px] font-black rounded-lg uppercase tracking-widest shadow-inner"
                                      :class="{
                                        'bg-rose-50 text-rose-500': ticket.priority === 'high',
                                        'bg-amber-50 text-amber-500': ticket.priority === 'medium',
                                        'bg-emerald-50 text-emerald-500': ticket.priority === 'low'
                                      }" x-text="ticket.priority"></span>
                                <span class="text-[9px] font-black text-slate-300 uppercase tracking-tighter" x-text="'#' + ticket.ticket_number"></span>
                            </div>

                            {{-- Content --}}
                            <p class="font-black text-slate-800 text-sm leading-tight mb-4 group-hover:text-sky-600 transition-colors text-left" x-text="ticket.subject"></p>
                            
                            <div class="flex items-center pt-4 border-t border-slate-50 text-left">
                                <div class="w-8 h-8 rounded-xl bg-slate-900 flex items-center justify-center mr-3 shadow-lg group-hover:bg-sky-500 transition-colors">
                                    <span class="text-[10px] font-black text-white uppercase" x-text="ticket.user.name.charAt(0)"></span>
                                </div>
                                <div class="text-left">
                                    <p class="text-[10px] font-black text-slate-700 leading-none" x-text="ticket.user.name"></p>
                                    <p class="text-[9px] font-bold text-slate-300 mt-1" x-text="formatDate(ticket.created_at)"></p>
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- Empty State Placeholder --}}
                    <div x-show="filteredTickets('{{ $status }}').length === 0" 
                         class="h-full border-2 border-dashed border-slate-100 rounded-[2.5rem] flex items-center justify-center p-10 opacity-30 grayscale">
                         <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">No Activity</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    function ticketsBoard() {
        return {
            tickets: [],
            dragOverColumn: null,
            
            initObserver() {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('visible'); });
                }, { threshold: 0.1 });
                document.querySelectorAll('.reveal-section').forEach(el => observer.observe(el));
            },

            filteredTickets(status) {
                return this.tickets.filter(ticket => ticket.status === status);
            },

            formatDate(dateStr) {
                return new Date(dateStr).toLocaleDateString('id-ID', {day:'numeric', month:'short', year:'numeric'});
            },

            handleDragStart(event, ticketId) {
                event.dataTransfer.setData('ticketId', ticketId);
                // Menambahkan sedikit transparansi saat di-drag
                event.target.style.opacity = '0.5';
            },

            handleDrop(event, newStatus) {
                this.dragOverColumn = null;
                const ticketId = event.dataTransfer.getData('ticketId');
                const ticket = this.tickets.find(t => t.id == ticketId);

                if (ticket && ticket.status !== newStatus) {
                    const oldStatus = ticket.status;
                    ticket.status = newStatus;

                    fetch(`/admin/crm/tickets/${ticketId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ status: newStatus })
                    })
                    .then(response => {
                        if (!response.ok) {
                            ticket.status = oldStatus;
                            Swal.fire({ icon: 'error', title: 'Update Failed', text: 'Gagal memindahkan tiket.', borderRadius: '2rem' });
                        }
                    })
                    .catch(error => {
                        ticket.status = oldStatus;
                        console.error('Error:', error);
                    });
                }
                
                // Reset opacity setelah drop
                const draggedElement = document.querySelector('[style*="opacity: 0.5"]');
                if (draggedElement) draggedElement.style.opacity = '1';
            }
        }
    }
</script>
@endsection