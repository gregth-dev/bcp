"use strict";

let flashMessage = document.querySelector('.flashMessage');
if (flashMessage)
    setTimeout(() => {
        flashMessage.classList.add('displayNone')
    }, 3000);