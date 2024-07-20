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
    const $modal = document.getElementById('modal');

    if ($modal) {
        $modal.addEventListener('show.bs.modal', e => {
            const $title = e.relatedTarget.getAttribute('data-title');
            $modal.querySelector('h5.modal-title').innerHTML = $title;

            $modal.querySelector('#message-user').addEventListener('click', _ => {
                const $task = e.relatedTarget.getAttribute('data-task');
                const $message = document.getElementById("message").value;

                fetch('/message/' + $task, {
                    method: 'POST',
                    body: $message,
                }).then(_ => document.getElementById('dismiss-message').click());
            }, {once: true});
        });
    }
})();
