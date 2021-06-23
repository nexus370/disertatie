<div id="mobileCartBtn" class="relative p-2 rounded bg-trueGray-800 shadow-sm lg:hidden">
    <svg class="fill-current text-white" xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 0 24 24"
        width="16px">
        <path d="M0 0h24v24H0V0z" fill="none" />
        <path
            d="M15.55 13c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.94-2H1v2h2l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h12v-2H7l1.1-2h7.45zM6.16 6h12.15l-2.76 5H8.53L6.16 6zM7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z" />
    </svg>

    <div
        class="absolute -top-1 -right-3 bg-white text-orange-700 px-1.5 py-0.5 rounded-full text-xxs font-semibold cart-quantity">
        {{ $cartItemsCount }}
    </div>
</div>

@push('scripts')
    <script>
        const mobileCartBtn = document.getElementById('mobileCartBtn');
        const closeCart = document.getElementById('closeCart');

        mobileCartBtn.addEventListener("click", function() {
            const cart = document.getElementById('mobileCart');

            console.log(cart)

            if (cart.classList.contains('hidden')) {
                cart.classList.remove('hidden')
            } else {
                cart.classList.add('hidden')
            }
        })

        closeCart.addEventListener("click", function() {
            const cart = document.getElementById('mobileCart');
            cart.classList.add('hidden')
        })
    </script>
@endpush
