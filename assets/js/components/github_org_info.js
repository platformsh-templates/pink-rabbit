import axios from 'axios';
import moment from 'moment';

/**
 * Dynamically loads some GitHub organization data.
 *
 * Hmm, this would be nicer with a front-end framework! :)
 */
export default function(wrapperEl) {
    axios.get(wrapperEl.dataset.url).then(response => {
        const organization = response.data.organization;

        const listHtml = response.data.repositories.map(repository => {
            return `
<div class="divTableRow sightingLink">
    <div class="divTableCell"><a class="text-white" href="${repository.url}" target="_blank">
            ${repository.name}
        </a>
    </div>
    <div class="divTableCell">
        <time class="table-content" datetime="${moment(repository.updatedAt).fromNow()}">${moment(repository.updatedAt).fromNow()}</time>
    </div>
</div>
            `;
        });

        const html = `
        <h3>${organization.name} Repos</h3>
        <small>${organization.repositoryCount} public repositories</small>
        <div class="divTable table table-striped table-dark table-borderless table-hover">
            <div class="divTableHeading">
                <div class="divTableRow bg-info">
                    <div class="divTableHead">Repo Name</div>
                    <div class="divTableHead">Updated</div>
                </div>
            </div>
            <div class="divTableBody">
                ${listHtml.join('')}
            </div>
        </div>
        `;

        wrapperEl.insertAdjacentHTML('beforeend', html);
    });
}
