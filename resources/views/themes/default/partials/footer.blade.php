<div class="border-t-2 border-slate">
    <div class="flex justify-center pt-8">
        @if($gitHubLink = app(\Bambamboole\MyCms\Settings\SocialSettings::class)->getGitHubProfileLink())
            <a href="{{$gitHubLink}}" target="_blank" rel="noopener" aria-label="Github"
               class="p-4 inline-block text-grey-dark hover:text-blue">
                @svg('github', 'fill-current h-8 w-8')
            </a>
        @endif
        @if($xLink = app(\Bambamboole\MyCms\Settings\SocialSettings::class)->getXProfileLink())
            <a href="{{$xLink}}" target="_blank" rel="noopener" aria-label="X"
               class="p-4 inline-block text-grey-dark hover:text-blue">
                @svg('x', 'fill-current h-8 w-8')
            </a>
        @endif
        @if($linkedInLink = app(\Bambamboole\MyCms\Settings\SocialSettings::class)->getLinkedInProfileLink())
            <a href="{{$linkedInLink}}" target="_blank" rel="noopener" aria-label="LinkedIn"
               class="p-4 inline-block text-grey-dark hover:text-blue">
                @svg('linkedin', 'fill-current h-8 w-8')
            </a>
        @endif
    </div>
    <div class="text-center pt-8 text-grey text-sm tracking-wide">
        @foreach(\Bambamboole\MyCms\Facades\MyCms::getMenuItems('footer') as $item)
            <a class="text-grey hover:text-blue" aria-label="Legal Notice"
               href="{{ $item->url }}">{{ $item->title }}</a>&nbsp;&nbsp;|&nbsp;&nbsp;
        @endforeach
        <span>&copy; {{date('Y')}}&nbsp;{{ app(\Bambamboole\MyCms\Settings\GeneralSettings::class)->site_name }}</span>
    </div>
</div>
