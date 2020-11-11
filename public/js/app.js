"use strict";

let flashMessage = document.querySelector('.flashMessage');
if (flashMessage)
    setTimeout(() => {
        flashMessage.classList.add('displayNone')
    }, 5000);

window.onload = function () {
    document.getElementById('close').onclick = function () {
        this.parentNode.parentNode.parentNode
            .removeChild(this.parentNode.parentNode);
        return false;
    };
};