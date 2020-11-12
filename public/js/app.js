"use strict";

let flashMessage = document.querySelector('.flashMessage');
if (flashMessage)
    setTimeout(() => {
        flashMessage.classList.add('displayNone')
    }, 5000);

let closeEl = document.querySelector('#close');
if (closeEl) {
    closeEl.onclick = function () {
        this.parentNode.parentNode.parentNode
            .removeChild(this.parentNode.parentNode);
        return false;
    };
}




$(document).ready(function () {
    $(".dropdown-trigger").dropdown({ coverTrigger: false, hover: true });
    $('.sidenav').sidenav();
});

