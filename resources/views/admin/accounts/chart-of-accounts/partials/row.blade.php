@php
    $bgClass =
        $depth === 0 ? 'bg-white-light/20 dark:bg-dark/10' : ($depth === 1 ? 'bg-white-light/10 dark:bg-dark/5' : '');
    $fontClass =
        $depth === 0
            ? 'font-bold text-primary text-xs uppercase'
            : ($depth === 1
                ? 'font-semibold text-sm'
                : 'text-sm');
@endphp

<tr class="{{ $bgClass }} {{ $fontClass }} group transition-all duration-300">
    <td class="font-mono text-[10px] w-24">{{ $account->code }}</td>
    <td class="flex items-center">
        <!-- Indentation -->
        @for ($i = 0; $i < $depth; $i++)
            <div class="h-4 w-5 border-l border-gray-300 dark:border-gray-600 ltr:ml-2 rtl:mr-2"></div>
        @endfor

        @if ($depth > 0)
            <div class="h-4 w-3 border-b border-gray-300 dark:border-gray-600 ltr:mr-2 rtl:ml-2"></div>
        @endif

        <span class="{{ $account->is_default ? 'text-blue-600 dark:text-blue-400' : '' }}">
            {{ $account->name }}
        </span>
    </td>
    <td>
        <span class="badge badge-outline-secondary text-[10px] uppercase font-bold px-2 whitespace-nowrap">
            {{ $account->type }}
        </span>
    </td>
    <td>
        @if ($account->is_active)
            <span class="badge badge-outline-success font-bold text-[10px]">Active</span>
        @else
            <span class="badge badge-outline-danger font-bold text-[10px]">Disabled</span>
        @endif
    </td>
    <td class="text-center">
        <div class="relative inline-block text-left" x-data="tableDropdown" @click.outside="close">
            <button type="button" @click="toggle" x-ref="btn" class="flex h-8 w-8 items-center justify-center rounded-full text-gray-500 hover:text-primary hover:bg-gray-100 dark:hover:bg-[#1b2e4b] dark:text-gray-400 focus:outline-none transition" title="Actions">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                    <circle cx="12" cy="5" r="2"/>
                    <circle cx="12" cy="12" r="2"/>
                    <circle cx="12" cy="19" r="2"/>
                </svg>
            </button>
            <div x-show="open" x-cloak x-ref="menu"
                x-transition:enter="transition ease-out duration-100" 
                x-transition:enter-start="transform opacity-0 scale-95" 
                x-transition:enter-end="transform opacity-100 scale-100" 
                x-transition:leave="transition ease-in duration-75" 
                x-transition:leave-start="transform opacity-100 scale-100" 
                x-transition:leave-end="transform opacity-0 scale-95"
                class="table-dropdown-menu z-50 w-36 origin-top-right rounded-lg bg-white p-1 shadow-lg ring-1 ring-black/5 dark:bg-[#1b2e4b] dark:ring-gray-700 text-left font-normal">
                
                <a href="{{ route('admin.chart-of-accounts.edit', $account) }}" class="flex items-center gap-2 rounded-md px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-[#121e32]">
                    <svg class="h-3.5 w-3.5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Edit
                </a>

                <form action="{{ route('admin.chart-of-accounts.status', $account) }}" method="POST">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-2 rounded-md px-3 py-2 text-xs font-medium {{ $account->is_active ? 'text-warning hover:bg-amber-50 dark:hover:bg-[#121e32]' : 'text-success hover:bg-emerald-50 dark:hover:bg-[#121e32]' }}">
                        @if($account->is_active)
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                            Disable
                        @else
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                            Activate
                        @endif
                    </button>
                </form>

                @if (!$account->is_default)
                    <form action="{{ route('admin.chart-of-accounts.destroy', $account) }}" method="POST"
                        onsubmit="return confirm('Deleting this head is irreversible. Continue?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex w-full items-center gap-2 rounded-md px-3 py-2 text-xs font-medium text-danger hover:bg-red-50 dark:hover:bg-[#121e32]">
                            <svg class="h-3.5 w-3.5 text-danger" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                            Delete
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </td>
</tr>

@foreach ($account->children as $child)
    @include('admin.accounts.chart-of-accounts.partials.row', ['account' => $child, 'depth' => $depth + 1])
@endforeach
