<!-- Contact Modal -->
<div id="contactModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
        <!-- Header -->
        <div class="sticky top-0 bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 flex items-center justify-between">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Send Message to Applicant
            </h2>
            <button type="button" class="close-modal text-white hover:text-gray-200 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Body -->
        <form id="contactForm" class="p-6 space-y-4">
            @csrf

            <!-- Hidden Application ID -->
            <input type="hidden" id="applicationId" name="application_id">

            <!-- Subject Input -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Subject <span class="text-red-500">*</span>
                </label>
                <input type="text" name="subject" placeholder="e.g., Interview Availability" maxlength="255" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                <p class="text-xs text-gray-500 mt-1">Keep it professional and clear</p>
            </div>

            <!-- Message Textarea -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Message <span class="text-red-500">*</span>
                </label>
                <textarea name="message" placeholder="Write your message here..." required minlength="10" maxlength="2000" rows="6"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition resize-none"></textarea>
                <div class="flex items-center justify-between mt-2">
                    <p class="text-xs text-gray-500">Minimum 10 characters, maximum 2000</p>
                    <span id="charCount" class="text-xs text-gray-500 font-medium">0/2000</span>
                </div>
            </div>

            <!-- Quick Templates -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Quick Templates</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <button type="button" class="template-btn text-left px-3 py-2 border border-gray-300 rounded-lg hover:bg-blue-50 transition text-sm font-medium text-gray-700"
                            data-template="Hi! We're interested in discussing your application further. Are you available for a call this week?">
                        📞 Schedule Call
                    </button>
                    <button type="button" class="template-btn text-left px-3 py-2 border border-gray-300 rounded-lg hover:bg-blue-50 transition text-sm font-medium text-gray-700"
                            data-template="Thank you for your interest! We'd like to know more about your experience in this area.">
                        ❓ Request Info
                    </button>
                    <button type="button" class="template-btn text-left px-3 py-2 border border-gray-300 rounded-lg hover:bg-blue-50 transition text-sm font-medium text-gray-700"
                            data-template="Great application! We'd like to move forward with you. Please confirm your availability for an interview.">
                        ✅ Interview Invitation
                    </button>
                    <button type="button" class="template-btn text-left px-3 py-2 border border-gray-300 rounded-lg hover:bg-blue-50 transition text-sm font-medium text-gray-700"
                            data-template="Thank you for applying! We're still reviewing applications and will get back to you soon.">
                        ⏳ Under Review
                    </button>
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-900 flex items-start gap-2">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <span><strong>Note:</strong> The application status will automatically change to "Contacted" once sent.</span>
                </p>
            </div>

            <!-- Footer -->
            <div class="flex gap-3 pt-4 border-t">
                <button type="button" class="flex-1 close-modal px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit" id="submitBtn" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    Send Message
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('contactModal');
    const contactForm = document.getElementById('contactForm');
    const charCountSpan = document.getElementById('charCount');
    const messageInput = contactForm.querySelector('textarea[name="message"]');
    const templateBtns = document.querySelectorAll('.template-btn');
    const closeButtons = document.querySelectorAll('.close-modal');
    const applicationIdInput = document.getElementById('applicationId');

    // Close modal
    closeButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            modal.classList.add('hidden');
            contactForm.reset();
            charCountSpan.textContent = '0/2000';
        });
    });

    // Close on outside click
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
            contactForm.reset();
            charCountSpan.textContent = '0/2000';
        }
    });

    // Character counter
    messageInput.addEventListener('input', () => {
        charCountSpan.textContent = `${messageInput.value.length}/2000`;
    });

    // Template buttons
    templateBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            messageInput.value = btn.dataset.template;
            charCountSpan.textContent = `${messageInput.value.length}/2000`;
            messageInput.focus();
        });
    });

    // Form submission
    contactForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const applicationId = applicationIdInput.value;

        if (!applicationId) {
            console.error('No application ID found');
            showToast('❌ Error: Application ID not found', 'error');
            return;
        }

        const subject = contactForm.querySelector('input[name="subject"]').value;
        const message = contactForm.querySelector('textarea[name="message"]').value;

        // Validation
        if (!subject || !message) {
            showToast('❌ Please fill in all fields', 'error');
            return;
        }

        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg> Sending...';

        try {
            console.log('Sending email to application:', applicationId);

            const response = await fetch(`/partner/applications/${applicationId}/contact`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    subject: subject,
                    message: message
                })
            });

            console.log('Response status:', response.status);

            const data = await response.json();
            console.log('Response data:', data);

            if (data.success) {
                showToast('✓ Email sent successfully to ' + (data.applicant_email || 'applicant'), 'success');
                modal.classList.add('hidden');
                contactForm.reset();
                charCountSpan.textContent = '0/2000';
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast('❌ ' + (data.message || 'Failed to send email'), 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('❌ An error occurred: ' + error.message, 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg> Send Message';
        }
    });
});

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 shadow-lg
        ${type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'}`;
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s ease-out';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>
