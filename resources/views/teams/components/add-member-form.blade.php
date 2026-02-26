<div class="bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
    <h3 class="text-lg font-semibold text-white mb-4">@lang('teams.add_member')</h3>

    <form id="addMemberForm" wire:submit.prevent="addMember">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <!-- Email/Phone Input -->
            <div class="col-span-2">
                <label for="member_email" class="block text-sm font-medium text-gray-400 mb-1">
                    @lang('teams.member_email_or_phone')
                </label>
                <input
                    type="text"
                    id="member_email"
                    name="member_email"
                    wire:model="member_email"
                    class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                    placeholder="@lang('teams.enter_email_or_phone')"
                    required
                >
                @error('member_email')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role Selector -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-400 mb-1">
                    @lang('teams.role')
                </label>
                <select
                    id="role"
                    name="role"
                    wire:model="role"
                    class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                >
                    <option value="member">@lang('roles.member')</option>
                    <option value="admin">@lang('roles.admin')</option>
                </select>
                @error('role')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Submit and Cancel Buttons -->
        <div class="mt-6 flex items-center gap-3">
            <button
                type="submit"
                class="px-6 py-2 bg-gradient-to-r from-amber-400 to-amber-600 hover:from-amber-500 hover:to-amber-700 text-white font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 focus:ring-offset-gray-900 transition-colors"
            >
                @lang('teams.add_member')
            </button>
            <button
                type="button"
                wire:click="cancel"
                class="px-6 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:ring-offset-gray-900 transition-colors"
            >
                @lang('actions.cancel')
            </button>
        </div>
    </form>
</div>
