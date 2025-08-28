@extends('layouts.user')

@section('content')
<h1 class="text-2xl font-bold mb-6">My Profile</h1>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Name -->
    <div class="bg-white shadow rounded-lg p-4 relative">
        <h2 class="text-gray-600 text-sm font-medium">Name</h2>
        <p class="text-gray-900 font-semibold">{{ $user->name }}</p>
        <button @click="nameModal = true"
            class="absolute top-2 right-2 text-indigo-600 hover:text-indigo-800 text-sm">
            Edit
        </button>
    </div>

    <!-- Email -->
    <div class="bg-white shadow rounded-lg p-4 relative">
        <h2 class="text-gray-600 text-sm font-medium">Email</h2>
        <p class="text-gray-900 font-semibold">{{ $user->email }}</p>
        <button @click="emailModal = true"
            class="absolute top-2 right-2 text-indigo-600 hover:text-indigo-800 text-sm">
            Edit
        </button>
    </div>

    <!-- Phone -->
    <div class="bg-white shadow rounded-lg p-4 relative">
        <h2 class="text-gray-600 text-sm font-medium">Phone</h2>
        <p class="text-gray-900 font-semibold">{{ $user->phone ?? 'Not set' }}</p>
        <button @click="phoneModal = true"
            class="absolute top-2 right-2 text-indigo-600 hover:text-indigo-800 text-sm">
            Edit
        </button>
    </div>

    <!-- Bio -->
    <div class="bg-white shadow rounded-lg p-4 relative">
        <h2 class="text-gray-600 text-sm font-medium">Bio</h2>
        <p class="text-gray-900 font-semibold">{{ $user->bio ?? 'Not set' }}</p>
        <button @click="bioModal = true"
            class="absolute top-2 right-2 text-indigo-600 hover:text-indigo-800 text-sm">
            Edit
        </button>
    </div>

    <!-- Date of Birth -->
    <div class="bg-white shadow rounded-lg p-4 relative">
        <h2 class="text-gray-600 text-sm font-medium">Date of Birth</h2>
        <p class="text-gray-900 font-semibold">{{ $user->date_of_birth?->format('Y-m-d') ?? 'Not set' }}</p>
        <button @click="dobModal = true"
            class="absolute top-2 right-2 text-indigo-600 hover:text-indigo-800 text-sm">
            Edit
        </button>
    </div>

    <!-- Language -->
    <div class="bg-white shadow rounded-lg p-4 relative">
        <h2 class="text-gray-600 text-sm font-medium">Language</h2>
        <p class="text-gray-900 font-semibold">{{ $user->language?->name ?? 'Not set' }}</p>
        <button @click="languageModal = true"
            class="absolute top-2 right-2 text-indigo-600 hover:text-indigo-800 text-sm">
            Edit
        </button>
    </div>

    <!-- Avatar -->
    <div class="bg-white shadow rounded-lg p-4 relative">
        <h2 class="text-gray-600 text-sm font-medium">Avatar</h2>
        <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}"
             class="w-20 h-20 rounded-full border mt-2">
        <button @click="avatarModal = true"
            class="absolute top-2 right-2 text-indigo-600 hover:text-indigo-800 text-sm">
            Edit
        </button>
    </div>
</div>


<!-- Modals -->
<div
    x-data="{
        nameModal:false, emailModal:false, phoneModal:false, bioModal:false, dobModal:false, languageModal:false, avatarModal:false,
        field:'', value:''
    }">

    <!-- Generic Modal Component -->
    <template x-for="modal in ['name','email','phone','bio','dob','language','avatar']" :key="modal">
        <div
            x-show="eval(modal+'Modal')"
            class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50"
            style="display:none;">
            <div class="bg-white p-6 rounded-lg shadow-md w-96">
                <h2 class="text-lg font-bold mb-4">Update <span x-text="modal"></span></h2>

                <!-- Input -->
                <input x-model="value" type="text" class="w-full border px-3 py-2 rounded mb-4">

                <div class="flex justify-end space-x-2">
                    <button @click="eval(modal+'Modal = false')" class="px-4 py-2 bg-gray-200 rounded">Cancel</button>
                    <button @click="updateField(modal)" class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
                </div>
            </div>
        </div>
    </template>
</div>


<script>
async function updateField(field) {
    const inputValue = document.querySelector('[x-data]').__x.$data.value;
    const res = await fetch("{{ route('user.profile.update') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ field: field, value: inputValue })
    });

    const result = await res.json();
    if (result.success) {
        alert(result.message);
        window.location.reload();
    } else {
        alert("Error updating field");
    }
}
</script>
@endsection
