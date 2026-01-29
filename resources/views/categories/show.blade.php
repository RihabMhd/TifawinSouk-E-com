<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $category->title }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('categories.edit', $category->id) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white">
                    Edit
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Category Details -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <dl class="grid grid-cols-1 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Title</dt>
                            <dd class="mt-1 text-lg text-gray-900 dark:text-gray-100">{{ $category->title }}</dd>
                        </div>
                        
                        @if($category->description)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</dt>
                            <dd class="mt-1 text-gray-700 dark:text-gray-300">{{ $category->description }}</dd>
                        </div>
                        @endif

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created by</dt>
                            <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $category->user->name }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created at</dt>
                            <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $category->created_at->format('F j, Y, g:i a') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Products in this Category -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        Products in this Category ({{ $category->products->count() }})
                    </h3>

                    @if($category->products->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($category->products as $product)
                        <a href="{{ route('products.show', $product->id) }}" class="group">
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <h4 class="font-medium text-gray-900 dark:text-gray-100 group-hover:text-gray-600 dark:group-hover:text-gray-300">
                                    {{ $product->title }}
                                </h4>
                                <p class="text-lg font-bold text-gray-900 dark:text-gray-100 mt-2">
                                    ${{ number_format($product->price, 2) }}
                                </p>
                                @if($product->description)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">
                                    {{ $product->description }}
                                </p>
                                @endif
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @else
                    <p class="text-gray-500 dark:text-gray-400">No products in this category yet.</p>
                    @endif
                </div>
            </div>

            <!-- Back Button -->
            <div>
                <a href="{{ route('categories.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                    Back to Categories
                </a>
            </div>
        </div>
    </div>
</x-app-layout>