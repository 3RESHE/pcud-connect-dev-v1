// Image upload handler
function handleImageUpload(event) {
    const file = event.target.files[0];
    if (file) {
        // Check file size (10MB max)
        if (file.size > 10 * 1024 * 1024) {
            alert('File size must be less than 10MB');
            event.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById("imagePreview");
            const img = document.getElementById("previewImg");
            img.src = e.target.result;
            preview.classList.remove("hidden");
        };
        reader.readAsDataURL(file);
    }
}

// Remove image
function removeImage() {
    document.getElementById('featured_image').value = '';
    document.getElementById('imagePreview').classList.add('hidden');
    document.getElementById('previewImg').src = '';
}

// Text formatting
function formatText(command) {
    const textarea = document.getElementById("content");
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end);

    if (!selectedText) {
        alert('Please select text to format');
        return;
    }

    let replacement = selectedText;

    switch(command) {
        case 'bold':
            replacement = `**${selectedText}**`;
            break;
        case 'italic':
            replacement = `*${selectedText}*`;
            break;
    }

    textarea.setRangeText(replacement, start, end, 'end');
    textarea.focus();
    updateWordCount();
}

function insertHeading() {
    const textarea = document.getElementById("content");
    const start = textarea.selectionStart;
    textarea.setRangeText("## Heading\n\n", start, start, 'end');
    textarea.focus();
    textarea.setSelectionRange(start + 3, start + 10);
    updateWordCount();
}

function insertList() {
    const textarea = document.getElementById("content");
    const start = textarea.selectionStart;
    const listText = "• Item 1\n• Item 2\n• Item 3\n\n";
    textarea.setRangeText(listText, start, start, 'end');
    textarea.focus();
    textarea.setSelectionRange(start + 2, start + 8);
    updateWordCount();
}

function insertQuote() {
    const textarea = document.getElementById("content");
    const start = textarea.selectionStart;
    textarea.setRangeText("> Quote text\n\n", start, start, 'end');
    textarea.focus();
    textarea.setSelectionRange(start + 2, start + 12);
    updateWordCount();
}

// Word count
function updateWordCount() {
    const content = document.getElementById("content").value;
    const words = content.trim().split(/\s+/).filter(word => word.length > 0).length;
    document.getElementById("wordCount").textContent = `${words} words`;
}

// Preview article
function previewArticle() {
    const title = document.getElementById("title").value;
    const category = document.getElementById("category");
    const categoryText = category.options[category.selectedIndex].text;
    const summary = document.getElementById("summary").value;
    const content = document.getElementById("content").value;
    const tags = document.getElementById("tags").value;
    const partnership = document.getElementById("partnership_with").value;
    const eventDate = document.getElementById("event_date").value;

    if (!title.trim() || !category.value || !summary.trim() || !content.trim() || !tags.trim() || !eventDate) {
        alert("Please fill in all required fields to preview the article.");
        return;
    }

    document.getElementById("previewTitle").textContent = title;
    document.getElementById("previewCategory").textContent = categoryText;
    document.getElementById("previewSummary").textContent = summary;
    document.getElementById("previewTags").textContent = tags || "None";
    document.getElementById("previewPartnership").textContent = partnership || "None";

    // Format event date
    const dateObj = new Date(eventDate);
    document.getElementById("previewEventDate").textContent = dateObj.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });

    // Format content
    let formattedContent = content
        .replace(/\n/g, "<br>")
        .replace(/\*\*(.*?)\*\*/g, "<strong>$1</strong>")
        .replace(/\*(.*?)\*/g, "<em>$1</em>")
        .replace(/^## (.*)$/gm, "<h2 class='text-xl font-bold mt-4 mb-2'>$1</h2>")
        .replace(/^• (.*)$/gm, "<li class='ml-4'>$1</li>")
        .replace(/^> (.*)$/gm, "<blockquote class='border-l-4 border-gray-300 pl-4 italic'>$1</blockquote>");

    document.getElementById("previewContentBody").innerHTML = formattedContent;

    // Preview image
    const previewImg = document.getElementById("previewModalImg");
    const formImg = document.getElementById("previewImg");
    const previewImgContainer = document.getElementById("previewImageContainer");

    if (formImg.src && !formImg.src.includes("data:image/svg+xml")) {
        previewImg.src = formImg.src;
        previewImgContainer.classList.remove("hidden");
    } else {
        previewImgContainer.classList.add("hidden");
    }

    document.getElementById("previewModal").classList.remove("hidden");
}

function closePreviewModal() {
    document.getElementById("previewModal").classList.add("hidden");
}

// Auto-update word count
document.addEventListener('DOMContentLoaded', function() {
    const contentArea = document.getElementById("content");
    if (contentArea) {
        contentArea.addEventListener("input", updateWordCount);
        updateWordCount();
    }

    // Close modal with ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closePreviewModal();
        }
    });
});
