import httpClient from './httpClient'

const END_POINT = '/orders'

const fetchProductsByName = (name) => httpClient.get(`${END_POINT}/products/name/${name}`);
const fetchProductById = (id) => httpClient.get(`${END_POINT}/products/id/${id}`);

const downloadOrders = (query) => httpClient.get(`${END_POINT}`, {params: query});
const downloadOrder = (id) => httpClient.get(`${END_POINT}/${id}`);
const storeOrder = (order) => httpClient.post(`${END_POINT}`, order);

export {
    downloadOrders,
    downloadOrder,
    fetchProductsByName,
    fetchProductById,
    storeOrder
}
