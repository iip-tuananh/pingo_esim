import {HTTP} from '../../core/plugins/http'
import CONSTANTS from '../../core/utils/constants';


export const addVoucher = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/voucher/create',opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};
export const listVouchers = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/voucher/list',opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};

export const deleteVoucher = ({commit}, opt) => {
    return new Promise((resolve, reject) => {
        HTTP.get('/api/voucher/delete/'+opt.id).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
}
export const detailVoucher = ({commit}, opt) => {
    return new Promise((resolve, reject) => {
        HTTP.get('/api/voucher/edit/'+ opt.id).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
}