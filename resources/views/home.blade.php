<x-app-layout>

    <x-hero />

    <section id="auctions" class="py-20">
        <div class="max-w-7xl mx-auto px-6">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">

                @foreach($products as $product)
                    <x-auction-card :product="$product" />
                @endforeach

            </div>

        </div>
    </section>

</x-app-layout>

