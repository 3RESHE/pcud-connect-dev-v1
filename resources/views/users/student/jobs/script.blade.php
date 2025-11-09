    <script>
        let withdrawFormElement = null;

        function applyForJob() {
            document.getElementById('applicationModal').classList.remove('hidden');
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function closeApplicationModal() {
            document.getElementById('applicationModal').classList.add('hidden');
            document.getElementById('applicationForm').reset();
        }

        function openWithdrawModal(applicationId) {
            withdrawFormElement = document.querySelector(`form[data-application-id="${applicationId}"]`);
            document.getElementById('withdrawConfirmModal').classList.remove('hidden');
        }

        function closeWithdrawModal() {
            document.getElementById('withdrawConfirmModal').classList.add('hidden');
            withdrawFormElement = null;
        }

        function submitWithdraw() {
            if (withdrawFormElement) {
                withdrawFormElement.submit();
            }
        }

        function toggleDescription() {
            const container = document.getElementById('descriptionContainer');
            const toggle = document.getElementById('descriptionToggle');
            const icon = document.getElementById('descriptionIcon');

            if (container.style.maxHeight === '400px') {
                container.style.maxHeight = 'none';
                toggle.textContent = 'Read Less';
                icon.style.transform = 'rotate(180deg)';
            } else {
                container.style.maxHeight = '400px';
                toggle.textContent = 'Read More';
                icon.style.transform = 'rotate(0deg)';
            }
        }

        function toggleRequirements() {
            const container = document.getElementById('requirementsContainer');
            const toggle = document.getElementById('requirementsToggle');
            const icon = document.getElementById('requirementsIcon');

            if (container.style.maxHeight === '400px') {
                container.style.maxHeight = 'none';
                toggle.textContent = 'Read Less';
                icon.style.transform = 'rotate(180deg)';
            } else {
                container.style.maxHeight = '400px';
                toggle.textContent = 'Read More';
                icon.style.transform = 'rotate(0deg)';
            }
        }

        function toggleBenefits() {
            const container = document.getElementById('benefitsContainer');
            const toggle = document.getElementById('benefitsToggle');
            const icon = document.getElementById('benefitsIcon');

            if (container.style.maxHeight === '400px') {
                container.style.maxHeight = 'none';
                toggle.textContent = 'Read Less';
                icon.style.transform = 'rotate(180deg)';
            } else {
                container.style.maxHeight = '400px';
                toggle.textContent = 'Read More';
                icon.style.transform = 'rotate(0deg)';
            }
        }

        function toggleCompanyDesc() {
            const container = document.getElementById('companyDescContainer');
            const toggle = document.getElementById('companyDescToggle');
            const icon = document.getElementById('companyDescIcon');

            if (container.style.maxHeight === '200px') {
                container.style.maxHeight = 'none';
                toggle.textContent = 'Read Less';
                icon.style.transform = 'rotate(180deg)';
            } else {
                container.style.maxHeight = '200px';
                toggle.textContent = 'Read More';
                icon.style.transform = 'rotate(0deg)';
            }
        }

        function shareJob(platform) {
            const jobTitle = "{{ $job->title }}";
            const jobUrl = window.location.href;
            const shareText = `Check out this job opportunity: ${jobTitle}`;

            let url;
            if (platform === 'facebook') {
                url = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(jobUrl)}`;
            } else if (platform === 'twitter') {
                url =
                    `https://twitter.com/intent/tweet?text=${encodeURIComponent(shareText)}&url=${encodeURIComponent(jobUrl)}`;
            } else if (platform === 'linkedin') {
                url = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(jobUrl)}`;
            }

            if (url) {
                window.open(url, '_blank', 'width=600,height=400');
            }
        }

        // Close modal when clicking outside
        document.getElementById('applicationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeApplicationModal();
            }
        });

        document.getElementById('withdrawConfirmModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeWithdrawModal();
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeApplicationModal();
                closeWithdrawModal();
            }
        });
    </script>
