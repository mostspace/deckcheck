<!-- User Profile Modal -->
<div id="user-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="relative bg-white rounded-xl shadow-[0px_8px_8px_-4px_rgba(16,24,40,0.03)] border border-[#e4e7ec] w-[400px] max-w-[90vw] p-[2px]">
        <!-- Modal Header -->
        <div class="h-36 bg-cover bg-end rounded-lg shadow-soft overflow-hidden" style="background-image: url('../../assets/media/images/6ac92bbdf9b2cd246f135736834eb1bd89727377.png');"></div>
        <button id="close-modal" class="absolute w-10 h-10 right-4 top-4 p-2 rounded-full bg-white/80 hover:bg-white" aria-label="Close profile">
            <i class="fa-solid fa-times"></i>
        </button>

        <!-- Modal Content -->
        <div class="-mt-16 px-6 pb-6">
            <!-- Current User Info -->
            <img src="{{ $user->profile_pic ? Storage::url($user->profile_pic) : asset('images/placeholders/user.png') }}" alt="avatar" class="h-20 w-20 rounded-lg ring-2 ring-accent-300 object-cover bg-slate-100" />
            <div class="mt-3">
                <h3 class="text-md font-semibold text-slate-900">{{ $user->first_name }} {{ $user->last_name }}</h3>
                <p class="text-slate-500 text-sm">{{ $user->email }}</p>
            </div>

            {{-- Switch Active Vessel --}}
            <div class="mt-6">
                <h4 class="text-slate-700 text-md">Switch Vessel Account</h4>
                <div class="mt-3 space-y-2">

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
                                    class="flex items-center w-full gap-3 p-2 rounded-lg border 
                                    {{ $isActive ? 'bg-accent-200/40 border-accent-300' : 'border-transparent hover:bg-accent-200/20' }}">
                                    <img src="https://storage.googleapis.com/uxpilot-auth.appspot.com/09fb5435f6-45bae26d661aa26fc2fe.png"
                                        class="h-12 w-12 rounded-md object-cover" />

                                    <div class="flex-1 text-left">
                                        <p class="text-sm text-slate-900 text-md">{{ $vessel->name }}</p>
                                        <p class="text-slate-600 text-sm">System Access</p>
                                    </div>

                                    @if ($isActive)
                                        <div class="w-2 h-2 bg-[#12b669] rounded-full"></div>
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
            <div class="mt-6 rounded-lg border bg-slate-50 divide-y">

                @if (in_array($user->system_role, ['superadmin', 'staff']))
                    <a href="{{ route('admin.dashboard') }}"
                        class="w-full flex items-center px-4 py-3 text-sm text-[#344053] hover:bg-slate-200 transition-colors cursor-pointer rounded-t-lg">
                        <i class="fa-solid fa-shield-halved w-4 h-4 mr-3 text-[#667084]"></i>
                        Admin Center
                    </a>
                @endif

                <a href="{{ route('profile.edit') }}"
                    class="w-full flex items-center px-4 py-3 text-sm text-[#344053] hover:bg-slate-200 transition-colors cursor-pointer">
                    <i class="fa-solid fa-user w-4 h-4 mr-3 text-[#667084]"></i>
                    Profile Settings
                </a>

                <button class="w-full flex items-center px-4 py-3 text-sm text-[#344053] hover:bg-slate-200 transition-colors cursor-pointer">
                    <i class="fa-solid fa-question-circle w-4 h-4 mr-3 text-[#667084]"></i>
                    Help Center
                </button>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-3 text-sm text-[#f04438] hover:bg-[#fef3f2] rounded-b-lg">
                        <i class="fa-solid fa-sign-out-alt w-4 h-4 mr-3"></i>
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
