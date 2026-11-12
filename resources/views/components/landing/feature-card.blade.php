@props(['icon', 'title'])
<article class="feat rv">
 <div class="ico"><x-landing.icon :name="$icon" /></div>
 <h3>{{ $title }}</h3><p>{{ $slot }}</p>
</article>
