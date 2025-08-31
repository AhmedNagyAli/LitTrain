<div
    x-show="nameModal"
    class="fixed inset-0 flex items-center justify-center bg-opacity-50 z-50"
>
    <!-- Modal Card -->
    <div
        class="bg-gray-100 w-full max-w-md rounded-2xl p-6
               shadow-2xl shadow-gray-700/50 transform transition-all"
    >
        <h2 class="text-lg font-semibold mb-4">Update Name</h2>
        <form method="POST" action="{{ route('user.profile.update.name') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name', auth()->user()->name) }}"
                    class="mt-1 block w-full border-gray-300 rounded-lg
                           shadow-sm focus:ring-blue-500 focus:border-blue-500"
                >
            </div>
            <div class="flex justify-end gap-2">
                <button
                    type="button"
                    @click="nameModal=false"
                    class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                >
                    Save
                </button>
            </div>
        </form>
    </div>
</div>
