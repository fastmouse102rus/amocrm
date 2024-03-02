import './scss/main.scss'
import { MaskInput } from "maska"

document.addEventListener('DOMContentLoaded', function(event) {
    new MaskInput("[data-maska]");

    const modalBtn = document.querySelectorAll('.btn-model--js');
    const modal = document.querySelectorAll('.modal');
    const modalClose = document.querySelectorAll('.modal-close--js');


    modalBtn.forEach((btn) => {
        btn.addEventListener('click', (item) => {
            const event = item.target;
            const modalData = event.dataset.id;
            const modalSel = document.getElementById(modalData);

            modal.forEach((modalItem) => {
                modalItem.classList.remove('active');
            });

            modalSel.classList.add('active');
        });
    });

    modalClose.forEach((btn) => {
        btn.addEventListener('click', (item) => {
            const event = item.target;
            const modalData = event.dataset.id;
            const modalSel = document.getElementById(modalData);

            modalSel.classList.remove('active');
        });
    });

    modal.forEach((btn) => {
        btn.addEventListener('click', (item) => {
            const event = item.target;
            if (event.classList.contains('modal--js')) {
                event.classList.remove('active');
            }
        });
    });

    const ajaxSend = async (formData, url) => {
        const response = await fetch(url, {
            method: "POST",
            body: formData
        });
        if (!response.ok) {
            throw new Error(`Ошибка по адресу ${url}, статус ошибки ${response.status}`);
        }
        return await response.text();
    };


    const modalForm = document.querySelector('.modal-form--js');

    modalForm.addEventListener('submit' ,(event) => {
        event.preventDefault();
        const form = event.target;
        const phone = form.querySelector('input[name="phone"]');
        const phoneValue = phone.value;
        const formData = new FormData(form);


        if (phoneValue.length !== 16) {
            phone.classList.add('error');
            return false;
        }else {
            phone.classList.remove('error');
        }

        ajaxSend(formData, '/salesgenerator/mail.php')
            .then((response) => {
                document.querySelector('#modal-file').classList.remove('active');
                document.querySelector('#modal-ok').classList.add('active');
                modalForm.reset();
            })
            .catch((err) => console.error(err));
        });


});
