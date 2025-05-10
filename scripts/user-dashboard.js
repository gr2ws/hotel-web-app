document.addEventListener('DOMContentLoaded', function () {
    // Initialize date pickers
    initializeDatePickers();
    // Load available rooms
    loadRooms();

    // Form submit handler
    document.getElementById('bookingForm').addEventListener('submit', handleBooking);

    function initializeDatePickers() {
        const dateInputs = document.querySelectorAll('.datepicker');
        dateInputs.forEach(input => {
            flatpickr(input, {
                dateFormat: "Y-m-d",
                minDate: "today",
                enableTime: false,
                altInput: true,
                altFormat: "F j, Y",
                onChange: function (selectedDates, dateStr, instance) {
                    if (instance.element.id === 'checkIn') {
                        const checkOutPicker = document.getElementById('checkOut')._flatpickr;
                        checkOutPicker.set('minDate', selectedDates[0]);
                    }
                }
            });
        });
    }

    async function loadRooms() {
        try {
            const response = await fetch('php/get_available_rooms.php');
            const rooms = await response.json();

            if (rooms.success) {
                displayRooms(rooms.data);
            } else {
                throw new Error(rooms.message);
            }
        } catch (error) {
            console.error('Error:', error);
            showError('Failed to load rooms');
        }
    }

    function displayRooms(rooms) {
        const tbody = document.getElementById('roomsTableBody');
        tbody.innerHTML = rooms.map(room => `
            <tr>
                <td>${room.room_number}</td>
                <td>${room.type}</td>
                <td>${room.capacity}</td>
                <td>₱${room.rate}</td>
                <td>
                    <span class="badge bg-${room.status === 'Available' ? 'success' : 'warning'}">
                        ${room.status}
                    </span>
                </td>
                <td class="text-end">
                    <button class="btn btn-sm btn-primary" onclick="selectRoom(${room.id})">
                        <i class="bi bi-calendar-plus"></i> Select
                    </button>
                </td>
            </tr>
        `).join('');
    }

    window.selectRoom = function (roomId) {
        document.getElementById('roomId').value = roomId;
        document.getElementById('checkIn').focus();
    }

    async function handleBooking(e) {
        e.preventDefault();
        const formData = new FormData(e.target);

        try {
            const response = await fetch('php/book_room.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.success) {
                showSuccess('Booking successful!');
                e.target.reset();
                loadRooms();
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            showError('Failed to book room');
        }
    }

    function showToast(message, type = 'success') {
        const toastEl = document.getElementById('liveToast');
        const toastBody = document.getElementById('toastMessage');

        // Set the toast color class
        toastEl.className = `toast align-items-center text-bg-${type} border-0`;

        // Update message
        toastBody.textContent = message;

        // Show the toast using Bootstrap's JS API
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    }

    function showSuccess(message) {
        showToast(message, 'success');
    }

    function showError(message) {
        showToast(message, 'danger');
    }

});

document.addEventListener('DOMContentLoaded', function () {
    // Tab switching functionality
    const navLinks = document.querySelectorAll('.nav-link[data-tab]');
    const contentSections = document.querySelectorAll('.content-section');

    function switchTab(tabId) {
        // Update navigation links
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('data-tab') === tabId) {
                link.classList.add('active');
            }
        });

        // Update content sections
        contentSections.forEach(section => {
            section.classList.add('d-none');
            if (section.id === `${tabId}-content`) {
                section.classList.remove('d-none');
            }
        });

        // Update URL hash without scrolling
        history.pushState(null, null, `#${tabId}`);
    }

    // Add click handlers to nav links
    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const tabId = link.getAttribute('data-tab');
            switchTab(tabId);
        });
    });

    // Handle browser back/forward buttons
    window.addEventListener('popstate', () => {
        const hash = window.location.hash.slice(1) || 'dashboard';
        switchTab(hash);
    });

    // Load initial tab based on URL hash or default to dashboard
    const initialTab = window.location.hash.slice(1) || 'dashboard';
    switchTab(initialTab);

    // Initialize forms
    initializeForms();
});

async function loadReservations() {
    try {
        const response = await fetch('php/get_user_reservations.php');
        const data = await response.json();

        if (data.success) {
            displayReservations(data.reservations);
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        console.error('Error loading reservations:', error);
        // Handle error display
    }
}

function displayReservations(reservations) {
    const tbody = document.getElementById('reservationsTableBody');
    if (!tbody) return;

    tbody.innerHTML = reservations.map(reservation => `
        <tr>
            <td>#${reservation.id}</td>
            <td>${reservation.room_type}</td>
            <td>${reservation.check_in}</td>
            <td>${reservation.check_out}</td>
            <td><span class="badge bg-${getStatusColor(reservation.status)}">${reservation.status}</span></td>
            <td class="text-end">
                <button class="btn btn-sm btn-outline-danger" onclick="cancelReservation(${reservation.id})">
                    <i class="bi bi-x-circle"></i> Cancel
                </button>
            </td>
        </tr>
    `).join('');
}

function getStatusColor(status) {
    switch (status.toLowerCase()) {
        case 'confirmed': return 'success';
        case 'pending': return 'warning';
        case 'cancelled': return 'danger';
        default: return 'secondary';
    }
}