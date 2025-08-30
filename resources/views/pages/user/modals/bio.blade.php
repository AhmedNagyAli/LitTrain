<div x-show="bioModal" class="fixed inset-0 flex items-center justify-center bg-opacity-50 z-50">
    <div class="bg-grau-100 w-full max-w-md rounded-lg shadow-lg p-6">
        <h2 class="text-lg font-semibold mb-4">Update Bio</h2>
        <form method="POST" action="{{ route('user.profile.update.bio') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Bio</label>
                <textarea name="bio" rows="4"
                          class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('bio', auth()->user()->bio) }}</textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" @click="bioModal=false" class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save</button>
            </div>
        </form>
    </div>
</div>
