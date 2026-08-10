@auth

<a
    href="{{ route('checkout.show', $mealPlan) }}"
    class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-gray-900 text-white font-semibold hover:bg-gray-800"
>
    Choose this plan
</a>

@else

<a
    href="{{ route('login') }}"
    class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-gray-900 text-white font-semibold"
>
    Login to subscribe
</a>

@endauth