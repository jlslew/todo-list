/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import 'bootstrap/dist/css/bootstrap.min.css';
import * as bootstrap from 'bootstrap';

import './styles/app.css';

(_ => {
    const $modalMessage = document.getElementById('modal-message');

    if ($modalMessage) {
        $modalMessage.addEventListener('show.bs.modal', e => {
            const $title = e.relatedTarget.getAttribute('data-title');
            $modalMessage.querySelector('h5.modal-title').innerHTML = $title;

            $modalMessage.querySelector('#message-user').addEventListener('click', _ => {
                const $task = e.relatedTarget.getAttribute('data-task');
                const $message = document.getElementById("message").value;

                fetch('/message/' + $task, {
                    method: 'POST',
                    body: $message,
                }).then(_ => document.getElementById('dismiss-message').click());
            }, {once: true});
        });
    }

    const $modalMessages = document.getElementById('modal-messages');

    if ($modalMessages) {
        $modalMessages.addEventListener('show.bs.modal', _ => {
            const $body = $modalMessages.querySelector('.modal-body');
            $body.innerHTML = '';

            fetch('/messages').then(response => response.json()).then(data => {
                if (data.length) {
                    const $ul = document.createElement('ul');
                    $ul.classList.add('list-group');

                    $body.appendChild($ul);

                    data.forEach(message => {
                        const $item = document.createElement('li');
                        $item.classList.add('list-group-item');

                        $item.innerHTML = message.content;
                        $ul.appendChild($item);
                    });
                } else {
                    const $p = document.createElement('p');
                    $p.innerHTML = $body.getAttribute('data-message');

                    $body.appendChild($p);
                }
            });
        });
    }
})();
