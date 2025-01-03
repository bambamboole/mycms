<div class=" xl:w-1/2 mx-auto flex justify-around pt-6 mb-8">
    @foreach(\Bambamboole\MyCms\Facades\MyCms::getMenuItems('header') as $item)
        <a class="flex uppercase font-bold text-grey-darker" href="{{$item->url}}">
            @svg('menu-icon-left', 'self-center') <span class=self-center">{{$item->title}}</span> @svg('menu-icon-right', '-mt-1 self-center')
        </a>
    @endforeach
</div>
<div class="flex items-stretch pt-8">
    <div class="self-center border-t-2 border-slate flex-1">&nbsp;</div>
    <div class="flex-1 flex justify-center -mt-4"><a class="leading-xl" aria-label="Home" href="{{ url('/') }}">@svg('logo')</a>
    </div>
    <div class="self-center border-t-2 border-slate flex-1">&nbsp;</div>
</div>
<div class="absolute top-0 right-0 bg-red-light shadow p-3">
    <a href="/rss" class="text-white" aria-label="RSS">@svg('rss', 'fill-current h-7 w-7')<p
            class="m-0 p-0 text-xs tracking-xl">RSS</p></a>
</div>
