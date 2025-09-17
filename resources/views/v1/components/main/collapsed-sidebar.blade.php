<aside id="collapsed-sidebar" class="bg-[#6840c6] border-r border-[#e4e7ec] w-16 flex-shrink-0 z-20 flex flex-col">
    <!-- Logo -->
    <div class="h-16 flex items-center justify-center border-b border-[#e4e7ec]">
        <div class="w-10 h-10 rounded-lg bg-[#6840c6] flex items-center justify-center text-white font-bold text-xl">D</div>
    </div>

    <!-- Navigation -->
    <nav class="mt-6 flex-1">
        <ul class="space-y-1 px-2">
            <li><span class="flex items-center justify-center p-2.5 text-sm font-medium rounded-lg bg-[#f9f5ff] text-[#6840c6] cursor-pointer"><i
                        class="fa-solid fa-chart-line w-5 h-5"></i></span></li>
            <li><span
                    class="flex items-center justify-center p-2.5 text-sm font-medium rounded-lg text-[#f9f5ff] hover:bg-[#f8f9fb] hover:text-[#79719a] cursor-pointer"><i
                        class="fa-solid fa-ship w-5 h-5"></i></span></li>
            <li><span
                    class="flex items-center justify-center p-2.5 text-sm font-medium rounded-lg text-[#f9f5ff] hover:bg-[#f8f9fb] hover:text-[#79719a] cursor-pointer"><i
                        class="fa-solid fa-wrench w-5 h-5"></i></span></li>
            <li><span
                    class="flex items-center justify-center p-2.5 text-sm font-medium rounded-lg text-[#f9f5ff] hover:bg-[#f8f9fb] hover:text-[#79719a] cursor-pointer"><i
                        class="fa-solid fa-boxes-stacked w-5 h-5"></i></span></li>
            <li><span
                    class="flex items-center justify-center p-2.5 text-sm font-medium rounded-lg text-[#f9f5ff] hover:bg-[#f8f9fb] hover:text-[#79719a] cursor-pointer"><i
                        class="fa-solid fa-file-lines w-5 h-5"></i></span></li>
            <li><span
                    class="flex items-center justify-center p-2.5 text-sm font-medium rounded-lg text-[#f9f5ff] hover:bg-[#f8f9fb] hover:text-[#79719a] cursor-pointer"><i
                        class="fa-solid fa-life-ring w-5 h-5"></i></span></li>
        </ul>

        <div class="my-6 border-t border-[#e4e7ec] mx-2"></div>

        <ul class="px-2">
            <li><span
                    class="flex items-center justify-center p-2.5 text-sm font-medium rounded-lg text-[#f9f5ff] hover:bg-[#f8f9fb] hover:text-[#79719a] cursor-pointer"><i
                        class="fa-solid fa-gear w-5 h-5"></i></span></li>
        </ul>

        <!-- User Avatar -->
        <div class="mt-6 border-t border-[#e4e7ec] mx-2 pt-4">
            <div class="flex justify-center">
                <button id="collapsed-user-menu-button">
                    <img src="{{ Storage::url($user->profile_pic) }}" alt="User avatar"
                        class="w-8 h-8 rounded-full cursor-pointer hover:ring-2 hover:ring-[#6840c6] hover:ring-offset-1">
                </button>
            </div>
        </div>
    </nav>

    <!-- Expand Button -->
    <div class="p-2 border-t border-[#e4e7ec]">
        <button id="expand-sidebar"
            class="w-full p-2 text-[#f9f5ff] hover:text-[#344053] hover:bg-[#f8f9fb] rounded-lg flex items-center justify-center">
            <i class="fa-solid fa-angles-right"></i>
        </button>
    </div>
</aside>
