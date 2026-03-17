// Live Search with debounce + filters
const searchInput = document.getElementById('liveSearch');
const filterSection = document.getElementById('filterSection');
const filterStrand  = document.getElementById('filterStrand');
const filterTags    = document.getElementById('filterTags');
function debounce(fn, delay) {
    let t;
    return function(...args) {
        clearTimeout(t);
        t = setTimeout(() => fn.apply(this, args), delay);
    }
}
function performSearch() {
    const query = searchInput ? searchInput.value.toLowerCase() : '';
    const sec   = filterSection ? filterSection.value.toLowerCase() : '';
    const str   = filterStrand ? filterStrand.value.toLowerCase() : '';
    const tagsQ = filterTags ? filterTags.value.toLowerCase().split(',').map(s=>s.trim()).filter(Boolean) : [];
    const rows = document.querySelectorAll('#manuscriptTable tr');
    rows.forEach(row => {
        if (!row.querySelectorAll('td').length) return;
        let text = row.innerText.toLowerCase();
        let show = true;
        if (query && !text.includes(query)) show = false;
        if (sec && !text.includes(sec)) show = false;
        if (str && !text.includes(str)) show = false;
        if (tagsQ.length) {
            const cellTags = row.children[6]?.innerText.toLowerCase() || '';
            if (!tagsQ.every(t => cellTags.includes(t))) show = false;
        }
        row.style.display = show ? '' : 'none';
    });
}
if (searchInput) {
    searchInput.addEventListener('input', debounce(performSearch, 180));
    document.addEventListener('keydown', (e) => {
        if (e.key === '/' && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
            e.preventDefault();
            searchInput.focus();
        }
    });
}
[filterSection, filterStrand, filterTags].forEach(el => {
    if (el) el.addEventListener('input', debounce(performSearch, 180));
});

// Row click opens file link (if present)
document.addEventListener('click', function(e) {
    const row = e.target.closest('#manuscriptTable tr');
    if (!row) return;
    if (e.target.closest('a') || e.target.closest('button') || e.target.closest('input')) return;
    const fileLink = row.querySelector('td a');
    if (fileLink) {
        window.open(fileLink.href, '_blank');
    }
});

// preview modal when clicking filename
document.addEventListener('click', function(e) {
    if (e.target.matches('.preview-link')) {
        e.preventDefault();
        const file = e.target.dataset.file;
        const body = document.getElementById('previewBody');
        if (body) {
            // simple iframe embed, pdf friendly
            body.innerHTML = '<iframe src="' + file + '" style="width:100%;height:100%;border:none"></iframe>';
            const modalEl = document.getElementById('previewModal');
            if (modalEl) {
                const bm = new bootstrap.Modal(modalEl);
                bm.show();
            }
        }
    }
});

// dark mode support
const darkToggle = document.getElementById('darkModeToggle');
function setDarkMode(on) {
    document.body.classList.toggle('dark-mode', on);
    if (darkToggle) {
        darkToggle.innerHTML = on ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
    }
    try { localStorage.setItem('darkMode', on ? '1' : '0'); } catch(e){}
}
if (darkToggle) {
    darkToggle.addEventListener('click', () => {
        setDarkMode(!document.body.classList.contains('dark-mode'));
    });
}
// initialize from storage
if (localStorage.getItem('darkMode') === '1') {
    setDarkMode(true);
}

// summary generator stub
const genButton = document.getElementById('generateSummary');
if (genButton) {
    genButton.addEventListener('click', () => {
        const textarea = genButton.closest('form')?.querySelector('textarea[name="summary"]');
        if (textarea) {
            textarea.value = 'This is where you input a short summary of you study';
        }
    });
}


// Simple client-side column sort (click header)
function getCellValue(row, idx) {
    const cell = row.children[idx];
    return cell ? cell.innerText.trim() : '';
}
function comparer(idx, asc) {
    return function(a, b) {
        const v1 = getCellValue(asc ? a : b, idx).toLowerCase();
        const v2 = getCellValue(asc ? b : a, idx).toLowerCase();
        const num1 = parseFloat(v1.replace(/[^0-9.-]/g, ''));
        const num2 = parseFloat(v2.replace(/[^0-9.-]/g, ''));
        if (!isNaN(num1) && !isNaN(num2)) return num1 - num2;
        return v1.localeCompare(v2);
    };
}

document.querySelectorAll('#manuscriptTable thead th').forEach((th, idx) => {
    // skip ACTIONS column (last)
    if (th.innerText.toLowerCase().includes('actions')) return;
    th.style.cursor = 'pointer';
    th.addEventListener('click', () => {
        const table = th.closest('table');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        if (!rows.length) return;
        const asc = !th.classList.contains('sort-asc');
        // reset classes
        table.querySelectorAll('th').forEach(h => h.classList.remove('sort-asc','sort-desc'));
        th.classList.add(asc ? 'sort-asc' : 'sort-desc');
        rows.sort(comparer(idx, asc));
        rows.forEach(r => tbody.appendChild(r));
    });
});
    
// Professional SweetAlert Delete
function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#004d2c',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `process.php?delete_id=${id}`;
        }
    })
}