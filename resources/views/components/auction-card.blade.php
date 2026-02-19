    @props(['product'])

<div class="bg-white rounded-3xl p-5 shadow-sm hover:shadow-xl transition">

    <img src="{{ $product->image ?? 'https://via.placeholder.com/400' }}"
         class="w-full h-64 object-cover rounded-2xl mb-6">

    <h3 class="text-xl font-bold mb-2">
        {{ $product->title }}
    </h3>

    <p class="text-slate-400 text-sm mb-4">
        {{ $product->description }}
    </p>

    <div class="flex justify-between items-center">
        <span class="text-2xl font-black text-indigo-600">
            ${{ $product->price }}
        </span>

        <a href="{{ route('products.show', $product) }}"
           class="bg-indigo-600 text-white px-5 py-2 rounded-xl text-sm font-bold">
            View
        </a>
    </div>

</div>
