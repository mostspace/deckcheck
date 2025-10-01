<!-- User Profile Modal -->
<div id="user-modal" class="fixed inset-0 z-50 flex hidden items-center justify-center bg-black bg-opacity-50">
    <div
        class="relative w-[400px] max-w-[90vw] rounded-xl border border-[#e4e7ec] bg-white p-[2px] shadow-[0px_8px_8px_-4px_rgba(16,24,40,0.03)]">
        <!-- Modal Header -->
        <div class="bg-end shadow-soft h-36 overflow-hidden rounded-lg bg-cover"
            style="background-image: url('../../assets/media/images/6ac92bbdf9b2cd246f135736834eb1bd89727377.png');">
        </div>
        <button id="close-modal"
            class="absolute right-4 top-4 h-9 w-9 rounded-full bg-transparent p-1 text-white transition-all duration-300 hover:bg-black/40"
            aria-label="Close profile">
            <i class="fa-solid fa-times mt-1 text-lg text-lg"></i>
        </button>

        <!-- Modal Content -->
        <div class="-mt-16 px-6 pb-6">
            <!-- Current User Info -->
            <img src="{{ $user->profile_pic ? Storage::url($user->profile_pic) : asset('images/placeholders/user.png') }}"
                alt="avatar" class="h-20 w-20 rounded-lg bg-slate-100 object-cover ring-2 ring-accent-300" />
            <div class="mt-3">
                <h3 class="text-md font-semibold text-slate-900">{{ $user->first_name }} {{ $user->last_name }}</h3>
                <p class="text-sm text-slate-500">{{ $user->email }}</p>
            </div>

            {{-- Switch Active Vessel --}}
            <div class="mt-6">
                <h4 class="text-md text-slate-700">Switch Vessel Account</h4>
                <div class="mt-3 space-y-2">

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
                                    class="{{ $isActive ? 'bg-accent-200/40 border-accent-300' : 'border-transparent hover:bg-accent-200/20' }} flex w-full items-center gap-3 rounded-lg border p-2">
                                    <img src="https://storage.googleapis.com/uxpilot-auth.appspot.com/09fb5435f6-45bae26d661aa26fc2fe.png"
                                        class="h-12 w-12 rounded-md object-cover" />

                                    <div class="flex-1 text-left">
                                        <p class="text-md text-sm text-slate-900">{{ $vessel->name }}</p>
                                        <p class="text-sm text-slate-600">System Access</p>
                                    </div>

                                    @if ($isActive)
                                        <div class="h-3 w-3 rounded-full border-[2px] border-white bg-[#8BB31A]"></div>
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
            <div class="mt-6 divide-y rounded-lg border bg-[#F8F8F6]">
                @if (in_array($user->system_role, ['superadmin', 'staff']))
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center gap-3 rounded-t-lg p-4 transition-colors hover:bg-slate-100">
                        <span class="text-slate-500">
                            <img src="./assets/media/icons/shield-check.svg" class="h-5 w-5" alt="Admin" />
                        </span>
                        <span class="flex-1 text-sm font-medium">Admin Center</span>
                        <img src="./assets/media/icons/arrow-long-right.svg" class="h-4 w-4" alt="Arrow" />
                    </a>
                @endif

                <a href="{{ route('profile.edit') }}"
                    class="flex items-center gap-3 p-4 transition-colors hover:bg-slate-100">
                    <span class="text-slate-500">
                        <img src="./assets/media/icons/user-circle.svg" class="h-5 w-5" alt="Admin" />
                    </span>
                    <span class="flex-1 text-sm font-medium">Profile Settings</span>
                    <img src="./assets/media/icons/arrow-long-right.svg" class="h-4 w-4" alt="Arrow" />
                </a>

                <a href="javascript:void(0)" class="flex items-center gap-3 p-4 transition-colors hover:bg-slate-100">
                    <span class="text-slate-500">
                        <img src="./assets/media/icons/help-center-circle.svg" class="h-5 w-5" alt="Admin" />
                    </span>
                    <span class="flex-1 text-sm font-medium">Help Center</span>
                    <img src="./assets/media/icons/arrow-long-right.svg" class="h-4 w-4" alt="Arrow" />
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex w-full items-center justify-between gap-3 rounded-b-lg p-4 transition-colors hover:bg-slate-100">
                        <span class="flex items-center gap-3">
                            <img src="./assets/media/icons/sign-out.svg" class="h-5 w-5 text-slate-500"
                                alt="Admin" />
                            <span class="flex-1 text-sm font-medium">Sign Out</span>
                        </span>
                        <img src="./assets/media/icons/arrow-long-right.svg" class="h-4 w-4" alt="Arrow" />
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
