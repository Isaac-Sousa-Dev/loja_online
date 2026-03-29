import './bootstrap';
import './utils/toast.js';
import Alpine from 'alpinejs';
import AOS from 'aos';
import 'aos/dist/aos.css';

AOS.init({
    once: true,
    duration: 800,
    easing: 'ease-out-cubic',
    offset: 48,
    disable: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
});

import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

window.toastr = toastr;

window.Alpine = Alpine;
Alpine.start();

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    encrypted: true
});

window.Echo.channel('sales-team-created')
    .listen('SalesTeamCreated', (e) => {
        console.log(e.salesTeam);
        addSalesTeam(e.salesTeam);
    });

function addSalesTeam(seller) {
    let divSellers = document.getElementById('div-atendimento');
    let newSeller = document.createElement('div');
    newSeller.innerHTML = `
        <div id="div-seller-${seller.id}" class="bg-gray-100 px-2 py-2 space-y-2 rounded-md flex flex-col justify-center">
            <div class="flex space-x-2 items-center justify-center">
                <div class="text-sm">${seller.name}</div>
            </div>
            <div class="flex justify-center bg-green-500 rounded-md px-2 py-1 items-center space-x-1 shadow-md shadow-green-300 cursor-pointer">
                <ion-icon name="logo-whatsapp" class="text-white font-semibold"></ion-icon>
                <span class="text-sm text-white font-semibold">Conversar</span>
            </div>
        </div>
    `;
    divSellers.appendChild(newSeller);
}


window.Echo.channel('sales-team-deleted')
    .listen('SalesTeamDeleted', (e) => {
        removeVendedor(e.salesTeamDeleted.id);
    });

function removeVendedor(sellerId) {
    // Lógica para remover o vendedor da interface (pode ser removido da lista, ocultado, etc.)
    document.getElementById(`div-seller-${sellerId}`).remove();
}









