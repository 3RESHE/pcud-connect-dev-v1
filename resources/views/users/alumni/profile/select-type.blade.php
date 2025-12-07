@extends('layouts.alumni')

@section('title', 'Select Profile Type - PCU-DASMA Connect')

@section('content')
<div class="w-full bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen py-8 sm:py-12 lg:py-16 flex items-center justify-center">
    <div class="w-full max-w-4xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">

        <!-- Header -->
        <div class="text-center mb-12 sm:mb-16">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-3 sm:mb-4">
                Tell us about yourself
            </h1>
            <p class="text-base sm:text-lg text-gray-600 max-w-2xl mx-auto">
                Choose your profile type to get started with your alumni journey
            </p>
        </div>

        <!-- Toast Container -->
        <div id="toastContainer" class="fixed bottom-4 right-4 z-50 max-w-xs mx-2 sm:max-w-sm"></div>

        <!-- Form -->
        <form id="typeForm" class="space-y-6">
            @csrf

            <!-- Options Container -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

                <!-- Fresh Graduate Option -->
                <div class="option-card group cursor-pointer transition-all transform hover:scale-105"
                    onclick="selectType('fresh_grad', this)">
                    <label class="block h-full cursor-pointer">
                        <input type="radio" name="user_type" value="fresh_grad" class="hidden peer" required>
                        <div class="relative h-full bg-white border-2 border-gray-200 rounded-xl p-6 sm:p-8
                                    transition-all peer-checked:border-green-500 peer-checked:bg-green-50
                                    peer-checked:shadow-lg hover:border-green-300 hover:shadow-md">

                            <!-- Icon -->
                            <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5m0 7l9 5 9-5m0-7l-9-5-9 5"></path>
                                </svg>
                            </div>

                            <!-- Title -->
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900 text-center mb-2">
                                Fresh Graduate
                            </h3>

                            <!-- Description -->
                            <p class="text-xs sm:text-sm text-gray-600 text-center mb-6 leading-relaxed">
                                You've recently completed your studies and are starting your career journey
                            </p>

                            <!-- Features -->
                            <div class="space-y-2">
                                <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-700">
                                    <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Academic info only</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-700">
                                    <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Quick setup</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-700">
                                    <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Update later</span>
                                </div>
                            </div>

                            <!-- Checkmark -->
                            <div class="absolute top-4 right-4 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-opacity">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                    </label>
                </div>

                <!-- Experienced Professional Option -->
                <div class="option-card group cursor-pointer transition-all transform hover:scale-105"
                    onclick="selectType('experienced', this)">
                    <label class="block h-full cursor-pointer">
                        <input type="radio" name="user_type" value="experienced" class="hidden peer" required>
                        <div class="relative h-full bg-white border-2 border-gray-200 rounded-xl p-6 sm:p-8
                                    transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50
                                    peer-checked:shadow-lg hover:border-blue-300 hover:shadow-md">

                            <!-- Icon -->
                            <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-4 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4m0 2v4m0 0h4m0 0V4m0 4L4 18"></path>
                                </svg>
                            </div>

                            <!-- Title -->
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900 text-center mb-2">
                                Experienced Professional
                            </h3>

                            <!-- Description -->
                            <p class="text-xs sm:text-sm text-gray-600 text-center mb-6 leading-relaxed">
                                You have professional work experience and established career background
                            </p>

                            <!-- Features -->
                            <div class="space-y-2">
                                <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-700">
                                    <svg class="w-4 h-4 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Full profile</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-700">
                                    <svg class="w-4 h-4 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Career details</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-700">
                                    <svg class="w-4 h-4 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Show achievements</span>
                                </div>
                            </div>

                            <!-- Checkmark -->
                            <div class="absolute top-4 right-4 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-opacity">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                    </label>
                </div>

            </div>

            <!-- Form Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center sm:justify-end">
                <a href="{{ route('alumni.dashboard') }}"
                    class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-base font-medium whitespace-nowrap text-center">
                    Skip for Now
                </a>
                <button type="submit" id="submitBtn" disabled
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition text-base font-medium whitespace-nowrap">
                    Continue to Profile
                </button>
            </div>
        </form>

    </div>
</div>

<script>
// Enable submit button when option is selected
function selectType(value, element) {
    document.querySelector(`input[value="${value}"]`).checked = true;
    document.getElementById('submitBtn').disabled = false;
}

// Form submission
document.getElementById('typeForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Processing...';

    const userType = document.querySelector('input[name="user_type"]:checked').value;

    try {
        const response = await fetch('{{ route("alumni.profile.set-type") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                user_type: userType
            })
        });

        const data = await response.json();

        if (response.ok && data.success) {
            showToast('✅ ' + data.message, 'success');
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1000);
        } else {
            showToast('❌ ' + (data.message || 'Failed to save'), 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('❌ Network error: ' + error.message, 'error');
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    }
});

function showToast(message, type = 'info') {
    const container = document.getElementById('toastContainer');
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';

    const toast = document.createElement('div');
    toast.className = `${bgColor} text-white px-4 py-3 rounded-lg shadow-lg mb-2 flex items-center justify-between gap-2 text-sm`;
    toast.innerHTML = `
        <span class="break-words">${message}</span>
        <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200 flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;

    if (container) {
        container.appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    }
}
</script>
@endsection
