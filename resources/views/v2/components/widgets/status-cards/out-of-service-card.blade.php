<div class="bg-white rounded-lg border border-[#e4e7ec] p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-[#667084]">Inoperable</p>
            <p class="text-2xl font-semibold text-[#344053]">{{ $inoperableCount ?? 0 }}</p>
        </div>
        <div class="w-12 h-12 bg-[#f2f3f6] rounded-lg flex items-center justify-center">
            <i class="text-[#344053] text-xl" data-fa-i2svg=""><svg class="svg-inline--fa fa-triangle-exclamation" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="triangle-exclamation" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480H40c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24V296c0 13.3 10.7 24 24 24s24-10.7 24-24V184c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z"></path></svg></i>
        </div>
    </div>
</div>