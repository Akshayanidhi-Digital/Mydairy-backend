// 'use strict';

// // modal variables
// const modal = document.querySelector('[data-modal]');
// const modalCloseBtn = document.querySelector('[data-modal-close]');
// const modalCloseOverlay = document.querySelector('[data-modal-overlay]');

// // modal function
// const modalCloseFunc = function () { modal.classList.add('closed') }

// // modal eventListener
// modalCloseOverlay.addEventListener('click', modalCloseFunc);
// modalCloseBtn.addEventListener('click', modalCloseFunc);





// // notification toast variables
// const notificationToast = document.querySelector('[data-toast]');
// const toastCloseBtn = document.querySelector('[data-toast-close]');

// // // notification toast eventListener
// toastCloseBtn.addEventListener('click', function () {
//   notificationToast.classList.add('closed');
// });





// // mobile menu variables
// const mobileMenuOpenBtn = document.querySelectorAll('[data-mobile-menu-open-btn]');
// const mobileMenu = document.querySelectorAll('[data-mobile-menu]');
// const mobileMenuCloseBtn = document.querySelectorAll('[data-mobile-menu-close-btn]');
// const overlay = document.querySelector('[data-overlay]');

// for (let i = 0; i < mobileMenuOpenBtn.length; i++) {

//   // mobile menu function
//   const mobileMenuCloseFunc = function () {
//     mobileMenu[i].classList.remove('active');
//     overlay.classList.remove('active');
//   }

//   mobileMenuOpenBtn[i].addEventListener('click', function () {
//     mobileMenu[i].classList.add('active');
//     overlay.classList.add('active');
//   });

//   mobileMenuCloseBtn[i].addEventListener('click', mobileMenuCloseFunc);
//   overlay.addEventListener('click', mobileMenuCloseFunc);

// }





// // accordion variables
// const accordionBtn = document.querySelectorAll('[data-accordion-btn]');
// const accordion = document.querySelectorAll('[data-accordion]');

// for (let i = 0; i < accordionBtn.length; i++) {

//   accordionBtn[i].addEventListener('click', function () {

//     const clickedBtn = this.nextElementSibling.classList.contains('active');

//     for (let i = 0; i < accordion.length; i++) {

//       if (clickedBtn) break;

//       if (accordion[i].classList.contains('active')) {

//         accordion[i].classList.remove('active');
//         accordionBtn[i].classList.remove('active');

//       }

//     }

//     this.nextElementSibling.classList.toggle('active');
//     this.classList.toggle('active');

//   });

// }

'use strict';

$(document).ready(function () {
  // modal variables
  const $modal = $('[data-modal]');
  const $modalCloseBtn = $('[data-modal-close]');
  const $modalCloseOverlay = $('[data-modal-overlay]');

  // modal function
  const modalCloseFunc = function () { $modal.addClass('closed'); }

  // modal eventListener
  if ($modalCloseOverlay.length) {
    $modalCloseOverlay.on('click', modalCloseFunc);
  }
  if ($modalCloseBtn.length) {
    $modalCloseBtn.on('click', modalCloseFunc);
  }

  // notification toast variables
  const $notificationToast = $('[data-toast]');
  const $toastCloseBtn = $('[data-toast-close]');

  // notification toast eventListener
  if ($toastCloseBtn.length) {
    $toastCloseBtn.on('click', function () {
      $notificationToast.addClass('closed');
    });
  }

  // mobile menu variables
  const $mobileMenuOpenBtn = $('[data-mobile-menu-open-btn]');
  const $mobileMenu = $('[data-mobile-menu]');
  const $mobileMenuCloseBtn = $('[data-mobile-menu-close-btn]');
  const $overlay = $('[data-overlay]');

  $mobileMenuOpenBtn.each(function (index) {
    const $menu = $mobileMenu.eq(index);
    const $closeBtn = $mobileMenuCloseBtn.eq(index);

    // mobile menu function
    const mobileMenuCloseFunc = function () {
      $menu.removeClass('active');
      $overlay.removeClass('active');
    }

    $(this).on('click', function () {
      $menu.addClass('active');
      $overlay.addClass('active');
    });

    if ($closeBtn.length) {
      $closeBtn.on('click', mobileMenuCloseFunc);
    }

    if ($overlay.length) {
      $overlay.on('click', mobileMenuCloseFunc);
    }
  });

  // accordion variables
  const $accordionBtn = $('[data-accordion-btn]');
  const $accordion = $('[data-accordion]');

  $accordionBtn.each(function () {
    $(this).on('click', function () {
      const $thisAccordion = $(this).next('[data-accordion]');
      const clickedBtn = $thisAccordion.hasClass('active');

      if (!clickedBtn) {
        $accordion.removeClass('active');
        $accordionBtn.removeClass('active');
      }

      $thisAccordion.toggleClass('active');
      $(this).toggleClass('active');
    });
  });
});

