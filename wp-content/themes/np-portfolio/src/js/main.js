/**
 * Startpunt voor Webpack.
 * Importeert het lettertype, de styling en alleen het Bootstrap-onderdeel dat we gebruiken.
 */
import '@fontsource-variable/bricolage-grotesque';
import '../scss/main.scss';
import 'bootstrap/js/dist/collapse';

// Menuknop: label aanpassen zodat schermlezers horen of het menu open of dicht gaat.
const toggler = document.querySelector('.navbar-toggler');
const menu = document.getElementById('hoofdmenu');

if (toggler && menu) {
  menu.addEventListener('shown.bs.collapse', () => toggler.setAttribute('aria-label', 'Menu sluiten'));
  menu.addEventListener('hidden.bs.collapse', () => toggler.setAttribute('aria-label', 'Menu openen'));
}

// Contactformulier: na versturen de focus op de melding zetten,
// zodat toetsenbord- en schermlezergebruikers direct horen wat er gebeurde.
// Pas na 'load', anders zet de browser de focus terug bij het springen naar #contactformulier.
const notice = document.querySelector('.form-notice');
if (notice) {
  window.addEventListener('load', () => notice.focus());
}
