<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $product->title }}
            </h2>
            @auth
            @if(auth()->user()->isAdmin())
            <div class="flex gap-2">
                <a href="{{ route('products.edit', $product->id) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white">
                    Edit
                </a>
            </div>
            @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Product Image -->
                        <div class="aspect-square bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden">
                            @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->title }}"
                                 class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-32 h-32 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            @endif
                        </div>

                        <!-- Product Details -->
                        <div class="flex flex-col">
                            <div class="flex-1">
                                <div class="mb-4">
                                    <span class="inline-block px-3 py-1 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-full">
                                        {{ $product->category->title }}
                                    </span>
                                </div>

                                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                                    {{ $product->title }}
                                </h1>

                                <div class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-6">
                                    ${{ number_format($product->price, 2) }}
                                </div>

                                @if($product->description)
                                <div class="prose dark:prose-invert max-w-none mb-6">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Description</h3>
                                    <p class="text-gray-700 dark:text-gray-300">{{ $product->description }}</p>
                                </div>
                                @endif

                                <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
                                    <dl class="grid grid-cols-1 gap-4">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created by</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $product->user->name }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Listed on</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $product->created_at->format('F j, Y') }}</dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-4 mt-8">
                                <a href="{{ route('products.index') }}" 
                                   class="flex-1 text-center px-6 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    Back to Products
                                </a>
                                
                                @auth
                                @if(auth()->user()->isAdmin())
                                <form action="{{ route('products.destroy', $product->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold text-sm rounded-md">
                                        Delete Product
                                    </button>
                                </form>
                                @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            @if($relatedProducts->count() > 0)
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Related Products</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $relatedProduct)
                    <a href="{{ route('products.show', $relatedProduct->id) }}" class="group">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow duration-200">
                            <div class="aspect-square bg-gray-100 dark:bg-gray-700">
                                @if($relatedProduct->image)
                                <img src="{{ asset('storage/' . $relatedProduct->image) }}" 
                                     alt="{{ $relatedProduct->title }}"
                                     class="w-full h-full object-cover group-hover:opacity-90 transition-opacity">
                                @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100 group-hover:text-gray-600 dark:group-hover:text-gray-300">
                                    {{ $relatedProduct->title }}
                                </h3>
                                <p class="text-lg font-bold text-gray-900 dark:text-gray-100 mt-2">
                                    ${{ number_format($relatedProduct->price, 2) }}
                                </p>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>