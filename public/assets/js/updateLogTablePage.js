/**
 * Makes an Ajax Request
 *
 * @param url
 * @param callback
 */
function makeAjaxRequest(url, callback) {
    console.log('Fetching URL:', url);
    const xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    callback(null, response);
                } catch (e) {
                    console.error('JSON parse error:', e, xhr.responseText);
                    callback(new Error('JSON parse error'));
                }
            } else {
                console.error('AJAX error:', xhr.status, xhr.statusText);
                callback(new Error('HTTP ' + xhr.status));
            }
        }
    };
    xhr.send();
}

/**
 * Update the table Contents
 *
 * @param tableBody
 * @param emptyLogs
 * @param data
 * @param currentPage
 * @param headers
 */
function updateTable(tableBody, emptyLogs, data, currentPage, headers) {
    tableBody.innerHTML = '';

    if (data.length > 0) {
        emptyLogs.style.display = 'none';

        data.forEach(row => {
            const tableRow = document.createElement('tr');

            headers.forEach(header => {
                const tableHeader = document.createElement('td');

                tableHeader.textContent = row[header] || '';
                tableRow.appendChild(tableHeader);
            });

            tableBody.appendChild(tableRow);
        });
    } else {
        emptyLogs.textContent = 'No logs found for page ' + currentPage + '.';
        emptyLogs.style.display = 'block';
    }
}

/**
 * Update the pagination contents
 *
 * @param pagination
 * @param currentPage
 * @param totalPages
 * @param data
 * @param handlePageClick
 */
function updatePagination(pagination, currentPage, totalPages, data, handlePageClick) {
    pagination.innerHTML = '';
    const prev = document.createElement('a');
    prev.textContent = 'Previous';
    prev.setAttribute('data-page', String(currentPage - 1));

    prev.className = currentPage <= 1 ? 'disabled' : '';
    prev.addEventListener('click', handlePageClick);
    pagination.appendChild(prev);

    for (let i = 1; i <= totalPages; i++) {
        const htmlAnchorElement = document.createElement('a');

        htmlAnchorElement.textContent = String(i);
        htmlAnchorElement.setAttribute('data-page', String(i));
        htmlAnchorElement.className = i === currentPage ? 'active' : '';
        htmlAnchorElement.addEventListener('click', handlePageClick);
        pagination.appendChild(htmlAnchorElement);
    }

    const nextHtmlAnchorElement = document.createElement('a');

    nextHtmlAnchorElement.textContent = 'Next';
    nextHtmlAnchorElement.setAttribute('data-page', String(currentPage + 1));
    nextHtmlAnchorElement.className = (currentPage >= totalPages || data.length === 0) ? 'disabled' : '';
    nextHtmlAnchorElement.addEventListener('click', handlePageClick);
    pagination.appendChild(nextHtmlAnchorElement);
}

/**
 * Event handler for page button click
 *
 * @param event
 */
function handlePageClick(event) {
    event.preventDefault();

    const page = parseInt(event.target.getAttribute('data-page'));

    if (!isNaN(page) && page > 0) {
        fetchPage(page);
        history.pushState({ page }, '', '?page=' + page);
    }
}

/**
 * Fetches and gets teh state of the page number
 *
 * @param event
 * @param fetchPage
 */
function handlePageStateAndFetching(event, fetchPage) {
    const page = event.state ? event.state.page : 1;
    fetchPage(page);
}

/**
 * Fetches the data for a page
 *
 * @param page
 */
function fetchPage(page) {
    const headers = ['username', 'date', 'action'];

    makeAjaxRequest('paginationApi.php?page=' + page, function(error, response) {
        if (error) {
            document.getElementById('empty-logs').textContent = 'Error loading data';
            document.getElementById('empty-logs').style.display = 'block';

            return;
        }

        const data = response.data || [];
        const totalPages = response.total_pages || 0;
        const currentPage = response.current_page || 1;

        const tableBody = document.getElementById('log-table-body');
        const emptyLogs = document.getElementById('empty-logs');
        const pagination = document.getElementById('pagination');

        updateTable(tableBody, emptyLogs, data, currentPage, headers);
        updatePagination(pagination, currentPage, totalPages, data, handlePageClick);
    });
}

/**
 * Event Listener for log table changes
 */
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#pagination a').forEach(link => {
        link.addEventListener('click', handlePageClick);
    });

    window.addEventListener('popstate', function(event) {
        handlePageStateAndFetching(event, fetchPage);
    });
});