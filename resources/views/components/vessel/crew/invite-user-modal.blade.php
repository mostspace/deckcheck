<div id="inviteUserModal" class="fixed inset-0 z-50 flex flex-col bg-white transform translate-x-full transition-transform duration-300 ease-in-out">
    {{-- HEADER --}}
    <header class="flex items-center justify-between px-6 py-4 border-b">
        <h2 class="text-2xl font-semibold text-[#0f1728]">
            Invite Crew Member
        </h2>

        {{-- Close --}}
        <button id="closeInviteModal" class="text-gray-500 hover:text-gray-800">
            <i class="fa-solid fa-xmark fa-lg"></i>
        </button>
    </header>

    {{-- FORM --}}
    <form id="inviteUserForm" action="{{ route('vessel.invitations.store', $vessel) }}" method="POST" class="flex-1 flex flex-col overflow-hidden">
        @csrf

        {{-- FORM BODY --}}
        <div class="flex-1 overflow-y-auto px-6 py-4 flex items-center justify-center">
            <div class="w-full max-w-2xl space-y-6">

                {{-- NAME ROW --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- First Name --}}
                    <div>
                        <label class="block text-sm font-medium text-[#344053] mb-1">First Name</label>
                        <input type="text" name="first_name" required placeholder="First name"
                            class="w-full bg-transparent border-b border-gray-300 focus:border-purple-500 focus:ring-0 py-2 text-gray-800 placeholder-gray-400">
                    </div>

                    {{-- Last Name --}}
                    <div>
                        <label class="block text-sm font-medium text-[#344053] mb-1">Last Name</label>
                        <input type="text" name="last_name" required placeholder="Last name"
                            class="w-full bg-transparent border-b border-gray-300 focus:border-purple-500 focus:ring-0 py-2 text-gray-800 placeholder-gray-400">
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-[#344053] mb-1">Email</label>
                    <input type="email" name="email" required placeholder="you@example.com"
                        class="w-full bg-transparent border-b border-gray-300 focus:border-purple-500 focus:ring-0 py-2 text-gray-800 placeholder-gray-400">
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-sm font-medium text-[#344053] mb-1">Phone Number</label>
                    <input type="text" name="phone" placeholder="Add Phone Number"
                        class="w-full bg-transparent border-b border-gray-300 focus:border-purple-500 focus:ring-0 py-2 text-gray-800 placeholder-gray-400">
                </div>

                {{-- Department --}}
                <div>
                    <label class="block text-sm font-medium text-[#344053] mb-1">Department</label>
                    <select name="department"
                        class="w-full bg-transparent border-b border-gray-300 focus:border-purple-500 focus:ring-0 py-2 text-gray-800">
                        <option value="">Select department</option>
                        <option value="bridge">Bridge</option>
                        <option value="interior">Interior</option>
                        <option value="exterior">Exterior</option>
                        <option value="galley">Galley</option>
                        <option value="engineering">Engineering</option>
                    </select>
                </div>

                {{-- Title --}}
                <div>
                    <label class="block text-sm font-medium text-[#344053] mb-1">Title</label>
                    <input type="text" name="role" placeholder="Add Title"
                        class="w-full bg-transparent border-b border-gray-300 focus:border-purple-500 focus:ring-0 py-2 text-gray-800 placeholder-gray-400">
                </div>

                {{-- Access Level --}}
                <div>
                    <label class="block text-sm font-medium text-[#344053] mb-1">Permissions</label>
                    <select name="access_level" required
                        class="w-full bg-transparent border-b border-gray-300 focus:border-purple-500 focus:ring-0 py-2 text-gray-800">
                        <option value="">Select access level</option>
                        <option value="crew">Crew</option>
                        <option value="admin">Admin</option>
                        <option value="viewer">Viewer</option>
                    </select>
                </div>

                @can('is-superadmin')
                    <div class="mt-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_test_user" class="form-checkbox text-indigo-600">
                            <span class="ml-2 text-sm text-gray-700">Mark as Test User</span>
                        </label>
                    </div>
                @endcan


            </div>
        </div>


        {{-- FOOTER --}}
        <footer class="flex-shrink-0 flex items-center justify-between px-6 py-4 border-t bg-white">
            <button type="button" id="cancelInviteModal"
                class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-100 transition">
                Cancel
            </button>

            <button type="submit" form="inviteUserForm" class="px-5 py-2 bg-purple-600 text-white rounded-lg text-sm hover:bg-purple-700 transition">
                Send Invitation
            </button>
        </footer>
    </form>
</div>
