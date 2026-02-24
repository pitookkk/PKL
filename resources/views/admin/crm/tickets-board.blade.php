@extends('layouts.app')

@section('title', 'Service Tickets Board - Pitocom Admin')

@section('content')
    {{-- Load Font Premium --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>

    {{-- Navbar Admin --}}
    @include('admin.partials.nav')
    
<div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-10" 
     x-data="ticketsBoard()" 
     x-init="tickets = JSON.parse($el.getAttribute('data-tickets'))"
     data-tickets="{{ json_encode($tickets) }}">
    
    {{-- CRM Navigation --}}
    <div class="mb-10">
        @include('admin.crm.partials.navbar')
    </div>

    {{-- Header Section --}}
    <div class="mb-10 text-left animate-fade-in-up">
        <h1 class="text-4xl font-black text-slate-900 tracking-tight">Service <span class="text-sky-500">Tickets</span> Board</h1>
        <p class="text-slate-500 mt-2 font-medium text-lg">Geser dan lepas tiket untuk memperbarui status perbaikan secara real-time.</p>
        <div class="h-1.5 w-24 bg-sky-500 mt-4 rounded-full"></div>
    </div>

    {{-- Kanban Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        
        @php
            $columns = [
                'open' => [
                    'label' => 'Tiket Baru',
                    'bg' => 'bg-slate-50', 
                    'text' => 'text-slate-500',
                    'accent' => 'bg-sky-500'
                ],
                'processing' => [
                    'label' => 'Sedang Dikerjakan',
                    'bg' => 'bg-amber-50/50', 
                    'text' => 'text-amber-600',
                    'accent' => 'bg-amber-500'
                ],
                'finished' => [
                    'label' => 'Selesai Perbaikan',
                    'bg' => 'bg-emerald-50/50', 
                    'text' => 'text-emerald-600',
                    'accent' => 'bg-emerald-500'
                ],
                'closed' => [
                    'label' => 'Arsip Tiket',
                    'bg' => 'bg-rose-50/30', 
                    'text' => 'text-rose-400',
                    'accent' => 'bg-rose-400'
                ],
            ];
        @endphp

        @foreach($columns as $status => $style)
            <div class="{{ $style['bg'] }} p-6 rounded-[2rem] border border-slate-100 flex flex-col h-full min-h-[600px]" 
                 @drop.prevent="handleDrop($event, '{{ $status }}')" 
                 @dragover.prevent>
                
                {{-- Column Header --}}
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <div class="w-2.5 h-2.5 rounded-full {{ $style['accent'] }} mr-3 shadow-sm shadow-white animate-pulse"></div>
                        <h2 class="text-xs font-black {{ $style['text'] }} uppercase tracking-[0.2em]">{{ $style['label'] }}</h2>
                    </div>
                    <span class="bg-white/80 px-2.5 py-1 rounded-lg text-[10px] font-black text-slate-400 shadow-sm" x-text="filteredTickets('{{ $status }}').length"></span>
                </div>

                {{-- Ticket Container --}}
                <div class="space-y-4 flex-grow overflow-y-auto pr-1">
                    <template x-for="ticket in filteredTickets('{{ $status }}')" :key="ticket.id">
                        <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-slate-50 cursor-grab active:cursor-grabbing hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group" 
                             draggable="true" 
                             @dragstart="handleDragStart($event, ticket.id)">
                            
                            {{-- Priority Badge --}}
                            <div class="mb-3">
                                <span class="px-3 py-1 text-[9px] font-black rounded-full uppercase tracking-tighter shadow-sm"
                                      :class="{
                                        'bg-rose-100 text-rose-600': ticket.priority === 'high',
                                        'bg-amber-100 text-amber-600': ticket.priority === 'medium',
                                        'bg-emerald-100 text-emerald-600': ticket.priority === 'low'
                                      }"
                                      x-text="ticket.priority + ' Priority'"></span>
                            </div>

                            {{-- Ticket Content --}}
                            <p class="font-black text-slate-800 text-sm leading-tight mb-2 group-hover:text-sky-600 transition-colors" x-text="ticket.subject"></p>
                            
                            <div class="flex items-center mb-4">
                                <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center mr-2">
                                    <span class="text-[10px] font-black text-slate-400" x-text="ticket.user.name.charAt(0)"></span>
                                </div>
                                <p class="text-xs font-bold text-slate-400 tracking-tight" x-text="ticket.user.name"></p>
                            </div>

                            {{-- Footer Info --}}
                            <div class="pt-4 border-t border-slate-50 flex items-center justify-between">
                                <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest" x-text="'#' + ticket.ticket_number"></span>
                                <span class="text-[9px] font-bold text-slate-300" x-text="new Date(ticket.created_at).toLocaleDateString('id-ID', {day:'numeric', month:'short'})"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        @endforeach

    </div>
</div>

<script>
    function ticketsBoard() {
        return {
            tickets: [],
            
            filteredTickets(status) {
                return this.tickets.filter(ticket => ticket.status === status);
            },

            handleDragStart(event, ticketId) {
                event.dataTransfer.setData('ticketId', ticketId);
            },

            handleDrop(event, newStatus) {
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
                            throw new Error('Gagal memperbarui status tiket.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Success:', data.message);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        ticket.status = oldStatus;
                    });
                }
            }
        }
    }
</script>
@endsection