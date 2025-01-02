<div class="border-t-2 border-slate">
    <div class="flex justify-center pt-8">
        @if($gitHubLink = \Bambamboole\MyCms\Facades\MyCms::getSocialSettings()->github_link)
            <a href="{{$gitHubLink}}" target="_blank" rel="noopener" aria-label="Github"
               class="p-4 inline-block text-grey-dark hover:text-blue">
                @svg('github', 'fill-current h-8 w-8')
            </a>
        @endif
        @if($xLink = \Bambamboole\MyCms\Facades\MyCms::getSocialSettings()->x_link)
            <a href="{{$xLink}}" target="_blank" rel="noopener" aria-label="X"
               class="p-4 inline-block text-grey-dark hover:text-blue">
                @svg('x', 'fill-current h-8 w-8')
            </a>
        @endif
        @if($linkedInLink = \Bambamboole\MyCms\Facades\MyCms::getSocialSettings()->linked_in_link)
            <a href="{{$linkedInLink}}" target="_blank" rel="noopener" aria-label="LinkedIn"
               class="p-4 inline-block text-grey-dark hover:text-blue">
                @svg('linkedin', 'fill-current h-8 w-8')
            </a>
        @endif
    </div>
    <div class="text-center pt-8 text-grey text-sm tracking-wide">
        @foreach(\Datlechin\FilamentMenuBuilder\Models\Menu::location('footer')->menuItems as $item)
            <a class="text-grey hover:text-blue" aria-label="Legal Notice"
               href="{{ $item->url }}">{{ $item->title }}</a>&nbsp;&nbsp;|&nbsp;&nbsp;
        @endforeach
        <span>&copy; {{date('Y')}}&nbsp;{{ \Bambamboole\MyCms\Facades\MyCms::getGeneralSettings()->site_name }}</span>
    </div>
</div>
