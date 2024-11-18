// URL API backend
const apiUrl = 'http://localhost/hotel_api/api.php';

// Fungsi untuk menampilkan data booking di halaman
function displayBookings(bookings) {
    const bookingList = document.getElementById('booking-list');
    bookingList.innerHTML = ''; // Kosongkan dulu list booking

    bookings.forEach(booking => {
        const bookingItem = document.createElement('div');
        bookingItem.classList.add('booking-item');
        
        bookingItem.innerHTML = `
            <p><strong>Nama Pemesan:</strong> ${booking.nama_pemesan}</p>
            <p><strong>Tanggal Check-in:</strong> ${booking.tanggal_checkin}</p>
            <p><strong>Tanggal Check-out:</strong> ${booking.tanggal_checkout}</p>
            <p><strong>Kamar:</strong> ${booking.kamar}</p>
        `;
        bookingList.appendChild(bookingItem);
    });
}

// Fungsi untuk mengambil data booking dari API
async function fetchBookings() {
    try {
        const response = await fetch(apiUrl);
        if (response.ok) {
            const bookings = await response.json();
            displayBookings(bookings);
        } else {
            console.error('Gagal mengambil data dari API');
        }
    } catch (error) {
        console.error('Terjadi kesalahan:', error);
    }
}

// Fungsi untuk scroll ke section tertentu di halaman
function scrollToSection(sectionId) {
    document.getElementById(sectionId).scrollIntoView({ behavior: "smooth" });
}

// Fungsi untuk menampilkan pesan pemesanan kamar
function bookRoom(roomType) {
    alert(`You selected the ${roomType}. Proceeding to booking!`);
}

// Fungsi untuk menangani form kontak
document.getElementById('contact-form').addEventListener('submit', function (e) {
    e.preventDefault();
    alert('Thank you for contacting us! We will get back to you shortly.');
    e.target.reset();
});

// Panggil fungsi fetchBookings saat halaman pertama kali dimuat
document.addEventListener('DOMContentLoaded', fetchBookings);
