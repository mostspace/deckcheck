// Equipment Manifest JavaScript functionality

document.addEventListener('DOMContentLoaded', function() {
    initializeSearchFilter();
    initializeColumnSort();
    initializeColumnEditModal();
});

// Search filtering logic
function initializeSearchFilter() {
    const searchInput = document.getElementById('equipment-search');
    const rows = document.querySelectorAll('#equipment-list tr');

    if (searchInput && rows.length > 0) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();

            rows.forEach(row => {
                const category = row.children[1]?.textContent.toLowerCase() || '';
                const match = category.includes(query);
                row.style.display = match ? '' : 'none';
            });
        });
    }
}

// Column sorting functionality
function initializeColumnSort() {
    const buttons = document.querySelectorAll('.sort-button');
    const tableBody = document.getElementById('equipment-list');
    let currentSortKey = null;
    let ascending = true;

    if (buttons.length === 0 || !tableBody) {
        return;
    }

    buttons.forEach(button => {
        button.addEventListener('click', () => {
            const sortKey = button.dataset.sortKey;
            ascending = (currentSortKey === sortKey) ? !ascending : true;
            currentSortKey = sortKey;

            const rows = Array.from(tableBody.querySelectorAll('tr'));

            rows.sort((a, b) => {
                let aVal = a.dataset[sortKey] || '';
                let bVal = b.dataset[sortKey] || '';

                aVal = aVal.toLowerCase();
                bVal = bVal.toLowerCase();

                return (aVal > bVal ? 1 : aVal < bVal ? -1 : 0) * (ascending ? 1 : -1);
            });

            rows.forEach(row => tableBody.appendChild(row));

            // Reset all sort icons
            buttons.forEach(btn => {
                const icon = btn.querySelector('svg');
                if (icon) {
                    icon.classList.remove('fa-arrow-up-short-wide', 'fa-arrow-down-wide-short');
                    icon.classList.add('fa-sort');
                    icon.classList.remove('text-[#6840c6]');
                    icon.classList.add('text-[#475466]');
                }
            });

            // Update clicked button's icon
            const icon = button.querySelector('svg');
            if (icon) {
                icon.classList.remove('fa-sort', 'text-[#475466]');
                icon.classList.add(
                    ascending ? 'fa-arrow-up-short-wide' : 'fa-arrow-down-wide-short',
                    'text-[#6840c6]'
                );
            } else {
                console.warn('Icon not found in clicked sort button:', button);
            }
        });
    });
}

// Column edit modal functionality
function initializeColumnEditModal() {
    function toggleColumnModal(show) {
        const modal = document.getElementById('column-modal');
        const overlay = document.getElementById('column-modal-overlay');
        
        if (modal && overlay) {
            modal.classList.toggle('hidden', !show);
            overlay.classList.toggle('hidden', !show);
        }
    }

    // Add event listeners to Edit Columns buttons
    document.querySelectorAll('button').forEach(btn => {
        if (btn.textContent.includes('Edit Columns')) {
            btn.addEventListener('click', () => toggleColumnModal(true));
        }
    });

    // Add event listener to modal overlay
    const overlay = document.getElementById('column-modal-overlay');
    if (overlay) {
        overlay.addEventListener('click', () => toggleColumnModal(false));
    }
}

// Export functions for potential external use
window.EquipmentManifest = {
    toggleColumnModal: function(show) {
        const modal = document.getElementById('column-modal');
        const overlay = document.getElementById('column-modal-overlay');
        
        if (modal && overlay) {
            modal.classList.toggle('hidden', !show);
            overlay.classList.toggle('hidden', !show);
        }
    }
};
