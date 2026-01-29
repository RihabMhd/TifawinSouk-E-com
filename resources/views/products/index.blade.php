<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Products') }}
            </h2>
            @auth
            @if(auth()->user()->isAdmin())
            <a href="{{ route('products.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                {{ __('Add Product') }}
            </a>
            @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
            <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800">
                <p class="text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                <p class="text-sm text-red-800 dark:text-red-200">{{ session('error') }}</p>
            </div>
            @endif

            <!-- Products Grid -->
            @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($products as $product)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow duration-200">
                    <!-- Product Image -->
                    <div class="aspect-square bg-gray-100 dark:bg-gray-700">
                        @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->title }}"
                             class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        @endif
                    </div>

                    <!-- Product Info -->
                    <div class="p-4">
                        <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100 mb-1">
                            {{ $product->title }}
                        </h3>
                        
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                            {{ $product->category->title }}
                        </p>

                        @if($product->description)
                        <p class="text-sm text-gray-700 dark:text-gray-300 mb-3 line-clamp-2">
                            {{ $product->description }}
                        </p>
                        @endif

                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-gray-900 dark:text-gray-100">
                                ${{ number_format($product->price, 2) }}
                            </span>

                            <a href="{{ route('products.show', $product->id) }}" 
                               class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                                View Details →
                            </a>
                        </div>

                        <!-- Admin Actions -->
                        @auth
                        @if(auth()->user()->isAdmin())
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex gap-2">
                            <a href="{{ route('products.edit', $product->id) }}" 
                               class="flex-1 text-center px-3 py-2 text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-200 dark:hover:bg-gray-600">
                                Edit
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this product?');"
                                  class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full px-3 py-2 text-xs bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded hover:bg-red-200 dark:hover:bg-red-900/50">
                                    Delete
                                </button>
                            </form>
                        </div>
                        @endif
                        @endauth
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>
            @else
            <!-- Empty State -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No products</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new product.</p>
                    @auth
                    @if(auth()->user()->isAdmin())
                    <div class="mt-6">
                        <a href="{{ route('products.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white">
                            Add Product
                        </a>
                    </div>
                    @endif
                    @endauth
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>