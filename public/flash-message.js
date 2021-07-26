import Swal from "sweetalert2";

console.log('salut je suis là');

// Configuration de base des messages flash
const FlashSuccess = Swal.mixin({
    toast: true,
    icon: 'success',
    position: 'bottom-end',
    showConfirmButton: false,
    timer: 5000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    },
});

// Transforme tous les élément .notification en SweetAlert (notification)
document.querySelectorAll('.notification').forEach((element) => {
    const options = element.dataset;
    options.html = element.innerHTML;

    FlashSuccess.fire(options);
});

// Affiche une alerte "Sauvegarde en cours ..." automatiquement pour
// chaque soumission de formulaire
document.querySelectorAll('form:not(.form-signin)').forEach((element) => {
    element.addEventListener('submit', (event) => {
        const text = event.target.dataset.text ?? 'Sauvegarde en cours ...';

        Swal.fire({
            allowOutsideClick: false,
            html: `<p class="h3">${text}</p>`,
            padding: '2rem',
            didOpen: () => {
                Swal.showLoading();
            },
        });
    });
});
