import './styles/app.css';
import sightings_list from "./js/components/sightings_list";
import github_org_info from "./js/components/github_org_info";

const sightingsListEl = document.querySelector('.js-sightings-list');
if (sightingsListEl) {
    sightings_list(sightingsListEl);
}

const organizationInfoEl = document.querySelector('.js-github-organization');
if (organizationInfoEl) {
    github_org_info(organizationInfoEl);
}

import './bootstrap';
