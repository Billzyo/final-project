/**
 * Receipt Debug Page JavaScript
 * Handles bulk actions, pagination, and filtering functionality
 */

/**
 * Toggle all checkboxes for bulk actions
 * @param {HTMLElement} checkbox - The master checkbox element
 */
function toggleAllCheckboxes(checkbox) {
    const checkboxes = document.querySelectorAll('.log-checkbox');
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
    updateBulkActions();
}

/**
 * Update the bulk actions UI based on selected checkboxes
 */
function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.log-checkbox:checked');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    const bulkResendBtn = document.getElementById('bulk-resend-btn');
    const selectedLogs = document.getElementById('selected-logs');
    
    if (checkboxes.length > 0) {
        bulkActions.style.display = 'flex';
        selectedCount.textContent = checkboxes.length + ' selected';
        bulkResendBtn.disabled = false;
        
        // Update hidden inputs for selected logs
        selectedLogs.innerHTML = '';
        checkboxes.forEach(checkbox => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'selected_logs[]';
            input.value = checkbox.value;
            selectedLogs.appendChild(input);
        });
    } else {
        bulkActions.style.display = 'none';
        bulkResendBtn.disabled = true;
    }
}

/**
 * Change the number of items per page and redirect
 * @param {string} value - The new per-page value
 */
function changePerPage(value) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', value);
    url.searchParams.set('page', '1'); // Reset to first page
    window.location.href = url.toString();
}

/**
 * Initialize the page when DOM is loaded
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize bulk actions state
    updateBulkActions();
    
    // Add event listeners to individual checkboxes
    const logCheckboxes = document.querySelectorAll('.log-checkbox');
    logCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });
    
    // Add event listener to header checkbox
    const headerCheckbox = document.getElementById('header-checkbox');
    if (headerCheckbox) {
        headerCheckbox.addEventListener('change', function() {
            toggleAllCheckboxes(this);
        });
    }
    
    // Add event listener to per-page selector
    const perPageSelect = document.getElementById('per_page');
    if (perPageSelect) {
        perPageSelect.addEventListener('change', function() {
            changePerPage(this.value);
        });
    }
    
    console.log('Receipt Debug JS initialized successfully');
});
