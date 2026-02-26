@php
    $isAdmin = $role === 'admin';
@endphp

<span class="
    inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
    @if($isAdmin)
        bg-gradient-to-r from-amber-400 to-amber-600 text-white shadow-sm
    @else
        bg-gray-700 text-gray-300
    @endif
">
    @if($isAdmin)
        <span class="mr-1">👑</span>
    @endif
    @lang($isAdmin ? 'roles.admin' : 'roles.member')
</span>
