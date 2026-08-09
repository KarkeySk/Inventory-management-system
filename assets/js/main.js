// ========================================
// AUTO-HIDE ALERTS
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.display = 'none';
        }, 5000);
    });
    
    // Setup autocomplete search
    setupAutocomplete();
});

// ========================================
// AUTOCOMPLETE SEARCH
// ========================================
function setupAutocomplete() {
    const searchBox = document.getElementById('liveSearchBox');
    
    if(!searchBox) return;
    
    // Create suggestions container
    const suggestionsDiv = document.createElement('div');
    suggestionsDiv.id = 'suggestions';
    suggestionsDiv.style.cssText = `
        position: absolute;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.12);
        max-height: 300px;
        overflow-y: auto;
        width: 100%;
        z-index: 1000;
        display: none;
        margin-top: 5px;
    `;
    
    searchBox.parentElement.style.position = 'relative';
    searchBox.parentElement.appendChild(suggestionsDiv);
    
    // Listen for input
    searchBox.addEventListener('input', function() {
        const term = this.value;
        
        // Hide suggestions if less than 2 characters
        if(term.length < 2) {
            suggestionsDiv.style.display = 'none';
            performLiveSearch(term); // Still search to show all
            return;
        }
        
        // Fetch autocomplete suggestions
        const xhr = new XMLHttpRequest();
        
        xhr.onload = function() {
            if(xhr.status == 200) {
                const suggestions = JSON.parse(xhr.responseText);
                displaySuggestions(suggestions, suggestionsDiv, searchBox);
            }
        };
        
        xhr.open('GET', 'ajax/autocomplete.php?term=' + encodeURIComponent(term), true);
        xhr.send();
        
        // Also perform live search
        performLiveSearch(term);
    });
    
    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if(e.target !== searchBox) {
            suggestionsDiv.style.display = 'none';
        }
    });
}

// Display autocomplete suggestions
function displaySuggestions(suggestions, container, inputBox) {
    // If no suggestions, hide
    if(suggestions.length == 0) {
        container.style.display = 'none';
        return;
    }
    
    // Clear previous suggestions
    container.innerHTML = '';
    container.style.display = 'block';
    
    // Add each suggestion
    suggestions.forEach(function(item) {
        const div = document.createElement('div');
        div.textContent = item;
        div.style.cssText = `
            padding: 12px 16px;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
            transition: background 0.2s;
        `;
        
        // Hover effect
        div.onmouseover = function() {
            this.style.background = '#f9fafb';
        };
        div.onmouseout = function() {
            this.style.background = 'white';
        };
        
        // Click to select
        div.onclick = function() {
            inputBox.value = item;
            container.style.display = 'none';
            performLiveSearch(item);
        };
        
        container.appendChild(div);
    });
}

// ========================================
// LIVE SEARCH - Fetch data without reload
// ========================================
function performLiveSearch(searchText) {
    // Create AJAX request
    const xhr = new XMLHttpRequest();
    
    // What happens when response comes back
    xhr.onload = function() {
        if(xhr.status == 200) {
            // Update table WITHOUT page reload
            document.getElementById('productTable').innerHTML = xhr.responseText;
        }
    };
    
    // Send request to server
    xhr.open('GET', 'ajax/search.php?search=' + encodeURIComponent(searchText), true);
    xhr.send();
}

// ========================================
// DELETE PRODUCT - Without page reload
// ========================================
function deleteProduct(productId) {
    // Ask user to confirm
    if(!confirm('Are you sure you want to delete this product?')) {
        return;
    }
    
    // Show loading message
    showMessage('Deleting...', 'info');
    
    // Create AJAX request
    const xhr = new XMLHttpRequest();
    
    // What happens when response comes back
    xhr.onload = function() {
        if(xhr.status == 200) {
            const response = xhr.responseText;
            
            // Check if successful
            if(response.includes('success:')) {
                // Show success message
                const message = response.replace('success:', '');
                showMessage(message, 'success');
                
                // Reload products without page reload
                setTimeout(function() {
                    performLiveSearch(''); // Fetch all products
                }, 1000);
            } else {
                // Show error message
                const message = response.replace('error:', '');
                showMessage(message, 'error');
            }
        }
    };
    
    // Prepare data to send
    const formData = new FormData();
    formData.append('id', productId);
    
    // Send request to server
    xhr.open('POST', 'ajax/delete.php', true);
    xhr.send(formData);
}

// ========================================
// SHOW MESSAGE
// ========================================
function showMessage(text, type) {
    // Remove old message if exists
    const oldMsg = document.getElementById('ajaxMessage');
    if(oldMsg) oldMsg.remove();
    
    // Create new message
    const msg = document.createElement('div');
    msg.id = 'ajaxMessage';
    msg.className = 'message message-' + type + ' show';
    msg.textContent = text;
    
    // Add to page
    const mainContent = document.querySelector('.main-content');
    if(mainContent) {
        mainContent.insertBefore(msg, mainContent.firstChild);
    }
    
    // Hide after 3 seconds
    setTimeout(function() {
        msg.classList.remove('show');
        setTimeout(function() {
            msg.remove();
        }, 300);
    }, 3000);
}

// ========================================
// CONFIRM DELETE (for non-AJAX delete links)
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    // Confirm delete for regular delete links
    document.querySelectorAll('.btn-delete').forEach(btn => {
        // Only add confirm if it's a link (not button with onclick)
        if(btn.tagName === 'A' && !btn.hasAttribute('onclick')) {
            btn.addEventListener('click', function(e) {
                if(!confirm('Are you sure you want to delete this product?')) {
                    e.preventDefault();
                }
            });
        }
    });
});