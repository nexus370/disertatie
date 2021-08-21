@props(['cart'])

<nav class=" bg-orange-600 py-2 px-4 hidden lg:flex lg:items-center lg:justify-between">
	<div class="w-9"></div>
	<ul class=" w-full flex items-start justify-center gap-x-20">
		<li class="w-24">
			<a class="text-white text-lg pb-0.5 border-b-2 border-orange-600 hover:border-black hover:text-black  {{ request()->is('/') ? '!border-black' : '' }}"
				href="{{ route('home') }}">
				Acasa
			</a>
		</li>
		<li class="w-24">
			<a class="text-white text-lg pb-0.5 border-b-2 border-orange-600 hover:border-black hover:text-black  {{ request()->is('meniu') || request()->is('meniu/*') ? '!border-black ' : '' }}"
				href="{{ route('menu.index') }}">
				Meniu
			</a>
		</li>
		<li class="relative w-40">
			<a class="block h-40 w-40 absolute top-1/2 left-1/2 z-[1] -translate-x-1/2 -translate-y-1/2 "
				href="{{ route('home') }}">
				<img class="w-full h-full rounded-full shadow-md" src="{{ asset('/storage/logo.png')}}" />
			</a>
		</li>

		<li class="w-24">
			<a class="text-white text-lg pb-0.5 border-b-2 border-orange-600 hover:border-black hover:text-black {{ request()->is('about') ? '!border-black' : '' }}"
				href="{{ route('about-us')}}">
				Despre noi
			</a>
		</li>
		<li class="w-24">
			<a class="text-white text-lg pb-0.5 border-b-2 border-orange-600 hover:border-black hover:text-black {{ request()->is('contanct') ? '!border-black' : '' }}"
				href="{{ route('contact') }}">
				Contanct
			</a>
		</li>
	</ul>

	<x-desktop-cart :cart="$cart"></x-desktop-cart>
</nav>