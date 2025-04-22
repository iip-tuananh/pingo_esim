import {HTTP} from '../../core/plugins/http'
import CONSTANTS from '../../core/utils/constants';


export const listProduct = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/product/list',opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};
export const deleteId = ({commit},opt) => {
    console.log(opt);
    return new Promise((resolve, reject) => {
        HTTP.get('/api/product/delete/'+ opt.id_item).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};

export const editId = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.get('/api/product/edit/'+ opt.id).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};

export const saveProduct = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/product/create',opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};

export const saveImportExcel = (_, formData) => { // Không cần `commit`
    return new Promise((resolve, reject) => {
        HTTP.post("/api/product/importExcel", formData, {
            headers: {
            "Content-Type": "multipart/form-data",
            },
        })
        .then(response => resolve(response.data))
        .catch(error => reject(error));
    });
};

export const addCateMultiple = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/product/add-cate-multiple',opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};

export const listProductOptions = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/product/options',opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};

export const deleteProductOptionId = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/product/delete-option',opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};

export const updatePriceMultiple = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/product/update-price-multiple',opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};

export const getOptionDetail = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.get('/api/product/option-detail/'+ opt.id).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};

export const updateOption = ({commit},opt) => {
    return new Promise((resolve, reject) => {
        HTTP.post('/api/product/update-option',opt).then(response => {
            return resolve(response.data);
        }).catch(error => {
            return reject(error);
        })
    });
};
