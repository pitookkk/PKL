<div class="mb-8 bg-white shadow-sm rounded-2xl border border-slate-100 p-4">
    <div class="flex items-center space-x-6 text-sm font-bold">
        <a href="{{ route('admin.dashboard') }}" class="text-slate-400 hover:text-sky-500 transition-colors">&larr; Back to Dashboard</a>
        
        <span class="h-4 w-px bg-slate-200"></span>

        <a href="{{ route('admin.crm.index') }}" 
           class="{{ request()->routeIs('admin.crm.index') ? 'text-sky-500' : 'text-slate-500 hover:text-sky-500' }} transition-colors">
           Customers
        </a>
        <a href="{{ route('admin.crm.tickets-board') }}" 
           class="{{ request()->routeIs('admin.crm.tickets-board') ? 'text-sky-500' : 'text-slate-500 hover:text-sky-500' }} transition-colors">
           Tickets Board
        </a>
        <a href="{{ route('admin.crm.broadcast.form') }}" 
           class="{{ request()->routeIs('admin.crm.broadcast.form') ? 'text-sky-500' : 'text-slate-500 hover:text-sky-500' }} transition-colors">
           Broadcast
        </a>
    </div>
</div>
