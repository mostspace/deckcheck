<!-- User Profile Modal -->
<div id="user-modal" class="fixed inset-0 z-50 flex hidden items-center justify-center bg-black bg-opacity-50">
    <div
        class="w-[400px] max-w-[90vw] rounded-xl border border-[#e4e7ec] bg-white shadow-[0px_8px_8px_-4px_rgba(16,24,40,0.03)]">
        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b border-[#e4e7ec] px-6 py-4">
            <h3 class="text-lg font-semibold text-[#0f1728]">Account Selection</h3>
            <button id="close-modal" class="rounded-lg p-2 text-[#667084] hover:bg-[#f8f9fb] hover:text-[#344053]">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        <!-- Modal Content -->
        <div class="p-6">
            <!-- Current User Info -->
            <div class="mb-6">
                <div class="flex items-center rounded-lg border border-[#e4e7ec] bg-[#f9f5ff] p-4">
                    <img src="{{ $user->profile_pic ? Storage::url($user->profile_pic) : asset('images/placeholders/user.png') }}"
                        alt="User avatar" class="mr-4 h-12 w-12 rounded-full">
                    <div>
                        <p class="text-sm font-medium text-[#0f1728]">{{ $user->first_name }} {{ $user->last_name }}</p>
                        <p class="text-xs text-[#475466]">{{ $user->email }}</p>
                    </div>
                </div>
            </div>

            {{-- Switch Active Vessel --}}
            <div class="mb-6">
                <h4 class="mb-3 text-sm font-medium text-[#344053]">Switch Vessel Account</h4>
                <div class="space-y-2">

                    @if (in_array(auth()->user()->system_role, ['superadmin', 'staff', 'dev']))
                        {{-- System Users: Show all vessels --}}
                        @foreach (auth()->user()->getAccessibleVessels() as $vessel)
                            @php
                                $isActive =
                                    session('active_vessel_id') == $vessel->id ||
                                    (!session('active_vessel_id') && $loop->first);
                            @endphp

                            <form method="POST" action="{{ route('vessel.switch') }}">
                                @csrf
                                <input type="hidden" name="vessel_id" value="{{ $vessel->id }}">

                                <button type="submit"
                                    class="{{ $isActive ? 'bg-[#f8f9fb] border-[#6840c6]' : 'border-[#e4e7ec] hover:bg-[#f9f5ff]' }} flex w-full items-center rounded-lg border p-3">
                                    <img src="https://storage.googleapis.com/uxpilot-auth.appspot.com/09fb5435f6-45bae26d661aa26fc2fe.png"
                                        class="mr-3 h-8 w-8 rounded-full" />

                                    <div class="flex-1 text-left">
                                        <p class="text-sm font-medium text-[#0f1728]">{{ $vessel->name }}</p>
                                        <p class="text-xs text-[#475466]">System Access</p>
                                    </div>

                                    @if ($isActive)
                                        <div class="h-3 w-3 rounded-full bg-[#12b669]"></div>
                                    @endif
                                </button>
                            </form>
                        @endforeach
                    @else
                        {{-- Regular Users: Show only boarded vessels --}}
                        @foreach (auth()->user()->vessels as $vessel)
                            @php
                                $b = $vessel->pivot;
                            @endphp

                            <form method="POST" action="{{ route('vessel.switch') }}">
                                @csrf
                                <input type="hidden" name="boarding_id" value="{{ $b->id }}">

                                <button type="submit"
                                    class="{{ $b->is_primary ? 'bg-[#f8f9fb] border-[#6840c6]' : 'border-[#e4e7ec] hover:bg-[#f9f5ff]' }} flex w-full items-center rounded-lg border p-3">
                                    <img src="https://storage.googleapis.com/uxpilot-auth.appspot.com/09fb5435f6-45bae26d661aa26fc2fe.png"
                                        class="mr-3 h-8 w-8 rounded-full" />

                                    <div class="flex-1 text-left">
                                        <p class="text-sm font-medium text-[#0f1728]">{{ $vessel->name }}</p>
                                        <p class="text-xs text-[#475466]">{{ $b->role }}</p>
                                    </div>

                                    @if ($b->is_primary)
                                        <div class="h-3 w-3 rounded-full bg-[#12b669]"></div>
                                    @endif
                                </button>
                            </form>
                        @endforeach
                    @endif

                </div>
            </div>

            <!-- Quick Actions -->
            <div class="space-y-2">

                @if (in_array($user->system_role, ['superadmin', 'staff']))
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex w-full cursor-pointer items-center rounded-lg px-4 py-3 text-sm text-[#344053] hover:bg-[#f8f9fb]">
                        <i class="fa-solid fa-shield-halved mr-3 h-4 w-4 text-[#667084]"></i>
                        Admin Center
                    </a>
                @endif

                <a href="{{ route('profile.edit') }}"
                    class="flex w-full cursor-pointer items-center rounded-lg px-4 py-3 text-sm text-[#344053] hover:bg-[#f8f9fb]">
                    <i class="fa-solid fa-user mr-3 h-4 w-4 text-[#667084]"></i>
                    Profile Settings
                </a>

                <button
                    class="flex w-full cursor-pointer items-center rounded-lg px-4 py-3 text-sm text-[#344053] hover:bg-[#f8f9fb]">
                    <i class="fa-solid fa-question-circle mr-3 h-4 w-4 text-[#667084]"></i>
                    Help Center
                </button>

                <div class="border-t border-[#e4e7ec] pt-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex w-full items-center rounded-lg px-4 py-3 text-sm text-[#f04438] hover:bg-[#fef3f2]">
                            <i class="fa-solid fa-sign-out-alt mr-3 h-4 w-4"></i>
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
