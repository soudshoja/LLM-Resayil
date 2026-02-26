<div class="bg-gray-800 rounded-lg shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-700">
        <h3 class="text-lg font-semibold text-white">@lang('teams.team_members') ({{ $teamMembers->total() }})</h3>
    </div>

    @if($teamMembers->isEmpty())
        <!-- Empty State -->
        <div class="px-6 py-12 text-center">
            <div class="mb-4">
                <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-300">@lang('teams.no_members_yet')</h3>
            <p class="mt-2 text-sm text-gray-500">@lang('teams.add_member_description')</p>
        </div>
    @else
        <!-- Team Members Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-700">
                <thead class="bg-gray-900">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">@lang('teams.name')</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">@lang('teams.email')</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">@lang('teams.role')</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">@lang('teams.added_by')</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">@lang('teams.joined_at')</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">@lang('teams.actions')</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-800 divide-y divide-gray-700">
                    @foreach($teamMembers as $member)
                        <tr class="hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 bg-gray-700 rounded-full flex items-center justify-center">
                                        <span class="text-gray-300 text-sm font-medium">{{ strtoupper(substr($member['member']['name'] ?? '?', 0, 1)) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-white">{{ $member['member']['name'] ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">ID: {{ Str::limit($member['member']['id'] ?? '', 8) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-300">{{ $member['member']['email'] ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $member['member']['phone'] ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @include('teams.components.role-badge', ['role' => $member['role']])
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-300">{{ $member['added_by']['name'] ?? 'System' }}</div>
                                <div class="text-xs text-gray-500">{{ $member['added_by']['email'] ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-300">
                                    {{ $member['joined_at'] ? \Carbon\Carbon::parse($member['joined_at'])->format('M d, Y') : 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if(auth()->id() === $member['added_by']['id'] ?? null)
                                    <button
                                        onclick="confirmDelete('{{ $member['id'] }}')"
                                        class="text-red-400 hover:text-red-300 transition-colors"
                                    >
                                        @lang('actions.remove')
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-700">
            {{ $teamMembers->links() }}
        </div>
    @endif
</div>

<script>
    function confirmDelete(memberId) {
        if (confirm('@lang('teams.confirm_remove')')) {
            document.getElementById(`delete-form-${memberId}`).submit();
        }
    }
</script>
