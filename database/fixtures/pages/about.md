---
title: About
slug: /
---

{.text-center}
# Hi,<br>my name is Manu. 

![This is me, Manuel.](/images/me_crop.jpg){.block .mx-auto .h-48 .rounded-full .border-grey-darker .border-4 .mb-8}

{.text-center}
## A web developer and Laravel enthusiast based in Germany.

{.text-center .text-grey-darker .mb-6}
I have over 12 years of experience in web development.
Until now, I have gathered experience for example in the following fields

@blade
<ul class="xl:w-2/3 mx-auto flex justify-center flex-wrap">
@foreach(\App\Models\Experience::ordered()->get() as $experience)
<li class="py-1 px-2 mr-1 mb-1 bg-grey-darkest text-white">{{$experience->name}}</li>
@endforeach
</ul>
@endblade
