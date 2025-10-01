<aside id="collapsed-sidebar" class="z-20 flex w-16 flex-shrink-0 flex-col border-r border-[#e4e7ec] bg-[#6840c6]">
    <!-- Logo -->
    <div class="flex h-16 items-center justify-center border-b border-[#e4e7ec]">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#6840c6] text-xl font-bold text-white">D
        </div>
    </div>

    <!-- Navigation -->
    <nav class="mt-6 flex-1">
        <ul class="space-y-1 px-2">
            <li><span
                    class="flex cursor-pointer items-center justify-center rounded-lg bg-[#f9f5ff] p-2.5 text-sm font-medium text-[#6840c6]"><i
                        class="fa-solid fa-chart-line h-5 w-5"></i></span></li>
            <li><span
                    class="flex cursor-pointer items-center justify-center rounded-lg p-2.5 text-sm font-medium text-[#f9f5ff] hover:bg-[#f8f9fb] hover:text-[#79719a]"><i
                        class="fa-solid fa-ship h-5 w-5"></i></span></li>
            <li><span
                    class="flex cursor-pointer items-center justify-center rounded-lg p-2.5 text-sm font-medium text-[#f9f5ff] hover:bg-[#f8f9fb] hover:text-[#79719a]"><i
                        class="fa-solid fa-wrench h-5 w-5"></i></span></li>
            <li><span
                    class="flex cursor-pointer items-center justify-center rounded-lg p-2.5 text-sm font-medium text-[#f9f5ff] hover:bg-[#f8f9fb] hover:text-[#79719a]"><i
                        class="fa-solid fa-boxes-stacked h-5 w-5"></i></span></li>
            <li><span
                    class="flex cursor-pointer items-center justify-center rounded-lg p-2.5 text-sm font-medium text-[#f9f5ff] hover:bg-[#f8f9fb] hover:text-[#79719a]"><i
                        class="fa-solid fa-file-lines h-5 w-5"></i></span></li>
            <li><span
                    class="flex cursor-pointer items-center justify-center rounded-lg p-2.5 text-sm font-medium text-[#f9f5ff] hover:bg-[#f8f9fb] hover:text-[#79719a]"><i
                        class="fa-solid fa-life-ring h-5 w-5"></i></span></li>
        </ul>

        <div class="mx-2 my-6 border-t border-[#e4e7ec]"></div>

        <ul class="px-2">
            <li><span
                    class="flex cursor-pointer items-center justify-center rounded-lg p-2.5 text-sm font-medium text-[#f9f5ff] hover:bg-[#f8f9fb] hover:text-[#79719a]"><i
                        class="fa-solid fa-gear h-5 w-5"></i></span></li>
        </ul>

        <!-- User Avatar -->
        <div class="mx-2 mt-6 border-t border-[#e4e7ec] pt-4">
            <div class="flex justify-center">
                <button id="collapsed-user-menu-button">
                    <img src="{{ Storage::url($user->profile_pic) }}" alt="User avatar"
                        class="h-8 w-8 cursor-pointer rounded-full hover:ring-2 hover:ring-[#6840c6] hover:ring-offset-1">
                </button>
            </div>
        </div>
    </nav>

    <!-- Expand Button -->
    <div class="border-t border-[#e4e7ec] p-2">
        <button id="expand-sidebar"
            class="flex w-full items-center justify-center rounded-lg p-2 text-[#f9f5ff] hover:bg-[#f8f9fb] hover:text-[#344053]">
            <i class="fa-solid fa-angles-right"></i>
        </button>
    </div>
</aside>
