import httpClient from './httpClient';

const END_POINT = 'dashboard/roles';

const downloadRoles = () => httpClient.get(`${END_POINT}`);

export {
    downloadRoles
}