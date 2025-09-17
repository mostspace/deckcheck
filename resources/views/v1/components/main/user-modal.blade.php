<!-- User Profile Modal -->
<div id="user-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-[0px_8px_8px_-4px_rgba(16,24,40,0.03)] border border-[#e4e7ec] w-[400px] max-w-[90vw]">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-[#e4e7ec] flex items-center justify-between">
            <h3 class="text-lg font-semibold text-[#0f1728]">Account Selection</h3>
            <button id="close-modal" class="p-2 text-[#667084] hover:text-[#344053] hover:bg-[#f8f9fb] rounded-lg">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        <!-- Modal Content -->
        <div class="p-6">
            <!-- Current User Info -->
            <div class="mb-6">
                <div class="flex items-center p-4 bg-[#f9f5ff] rounded-lg border border-[#e4e7ec]">
                    <img src="{{ $user->profile_pic ? Storage::url($user->profile_pic) : asset('images/placeholders/user.png') }}" alt="User avatar" class="w-12 h-12 rounded-full mr-4">
                    <div>
                        <p class="text-sm font-medium text-[#0f1728]">{{ $user->first_name }} {{ $user->last_name }}</p>
                        <p class="text-xs text-[#475466]">{{ $user->email }}</p>
                    </div>
                </div>
            </div>

            {{-- Switch Active Vessel --}}
            <div class="mb-6">
                <h4 class="text-sm font-medium text-[#344053] mb-3">Switch Vessel Account</h4>
                <div class="space-y-2">

                    @if (in_array(auth()->user()->system_role, ['superadmin', 'staff', 'dev']))
                        {{-- System Users: Show all vessels --}}
                        @foreach (auth()->user()->getAccessibleVessels() as $vessel)
                            @php
                                $isActive = session('active_vessel_id') == $vessel->id || 
                                           (!session('active_vessel_id') && $loop->first);
                            @endphp

                            <form method="POST" action="{{ route('vessel.switch') }}">
                                @csrf
                                <input type="hidden" name="vessel_id" value="{{ $vessel->id }}">

                                <button type="submit"
                                    class="flex items-center w-full p-3 rounded-lg border 
                                    {{ $isActive ? 'bg-[#f8f9fb] border-[#6840c6]' : 'border-[#e4e7ec] hover:bg-[#f9f5ff]' }}">
                                    <img src="https://storage.googleapis.com/uxpilot-auth.appspot.com/09fb5435f6-45bae26d661aa26fc2fe.png"
                                        class="w-8 h-8 rounded-full mr-3" />

                                    <div class="flex-1 text-left">
                                        <p class="text-sm font-medium text-[#0f1728]">{{ $vessel->name }}</p>
                                        <p class="text-xs text-[#475466]">System Access</p>
                                    </div>

                                    @if ($isActive)
                                        <div class="w-3 h-3 bg-[#12b669] rounded-full"></div>
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
                                    class="flex items-center w-full p-3 rounded-lg border 
                                    {{ $b->is_primary ? 'bg-[#f8f9fb] border-[#6840c6]' : 'border-[#e4e7ec] hover:bg-[#f9f5ff]' }}">
                                    <img src="https://storage.googleapis.com/uxpilot-auth.appspot.com/09fb5435f6-45bae26d661aa26fc2fe.png"
                                        class="w-8 h-8 rounded-full mr-3" />

                                    <div class="flex-1 text-left">
                                        <p class="text-sm font-medium text-[#0f1728]">{{ $vessel->name }}</p>
                                        <p class="text-xs text-[#475466]">{{ $b->role }}</p>
                                    </div>

                                    @if ($b->is_primary)
                                        <div class="w-3 h-3 bg-[#12b669] rounded-full"></div>
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
                        class="w-full flex items-center px-4 py-3 text-sm text-[#344053] hover:bg-[#f8f9fb] rounded-lg cursor-pointer">
                        <i class="fa-solid fa-shield-halved w-4 h-4 mr-3 text-[#667084]"></i>
                        Admin Center
                    </a>
                @endif

                <a href="{{ route('profile.edit') }}"
                    class="w-full flex items-center px-4 py-3 text-sm text-[#344053] hover:bg-[#f8f9fb] rounded-lg cursor-pointer">
                    <i class="fa-solid fa-user w-4 h-4 mr-3 text-[#667084]"></i>
                    Profile Settings
                </a>

                <button class="w-full flex items-center px-4 py-3 text-sm text-[#344053] hover:bg-[#f8f9fb] rounded-lg cursor-pointer">
                    <i class="fa-solid fa-question-circle w-4 h-4 mr-3 text-[#667084]"></i>
                    Help Center
                </button>

                <div class="border-t border-[#e4e7ec] pt-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-3 text-sm text-[#f04438] hover:bg-[#fef3f2] rounded-lg">
                            <i class="fa-solid fa-sign-out-alt w-4 h-4 mr-3"></i>
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
