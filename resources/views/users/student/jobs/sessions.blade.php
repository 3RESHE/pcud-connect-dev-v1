            <!-- Error/Success Messages (Outside Modal) -->
            @if (session('success'))
                <div
                    class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 flex items-start gap-3 animate-in fade-in slide-in-from-top-4 duration-300">
                    <svg class="w-5 h-5 text-green-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-green-800">Success!</h3>
                        <p class="text-sm text-green-700 mt-1">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div
                    class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 flex items-start gap-3 animate-in fade-in slide-in-from-top-4 duration-300">
                    <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-red-800">Error!</h3>
                        <p class="text-sm text-red-700 mt-1">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if (session('warning'))
                <div
                    class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4 flex items-start gap-3 animate-in fade-in slide-in-from-top-4 duration-300">
                    <svg class="w-5 h-5 text-yellow-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-yellow-800">Warning!</h3>
                        <p class="text-sm text-yellow-700 mt-1">{{ session('warning') }}</p>
                    </div>
                </div>
            @endif

            @if (session('info'))
                <div
                    class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start gap-3 animate-in fade-in slide-in-from-top-4 duration-300">
                    <svg class="w-5 h-5 text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0zm6 0a1 1 0 11-2 0 1 1 0 012 0zm-6 3a1 1 0 100-2 1 1 0 000 2zm6 0a1 1 0 100-2 1 1 0 000 2z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-blue-800">Info</h3>
                        <p class="text-sm text-blue-700 mt-1">{{ session('info') }}</p>
                    </div>
                </div>
            @endif

            <!-- Validation Errors Outside Modal -->
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-red-800 mb-2">Validation Errors:</h3>
                            <ul class="list-disc list-inside space-y-1 text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
