import {HTTP} from '../../core/plugins/http'
import CONSTANTS from '../../core/utils/constants';

export const listCarServicePrices  = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.get('/api/car_service_prices/list', opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};

export const saveCarServicePrices  = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/car_service_prices/store', opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};

// export const saveCarServicePrices = ({ commit }, opt) => {
//     console.log("saveCarServicePrices được gọi với dữ liệu:", opt);

//     return new Promise((resolve, reject) => {
//         HTTP.post('/api/car_service_prices/store', opt)
//             .then(response => {
//                 console.log("API Response:", response.data);
//                 return resolve(response.data);
//             })
//             .catch(error => {
//                 console.error("API Error:", error);
//                 return reject(error);
//             });
//     });
// };

export const deleteCurrencyRateId = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/website/currency-rate/delete',opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};

export const listCurrencyRate = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/website/list-currency-rate',opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};
