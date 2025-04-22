<template>
<div>
    <h3 class="page-title">Quản lý bảng giá dịch vụ</h3>
    <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
        <div class="card-body">
            <div class="row" v-for="(item, key) in objData" :key="key">
                <div class="col-md-8">
                    <div class="d-flex" style="gap: 10px; align-items: flex-end;">
                        <div class="form-group">
                            <label style="font-weight: 500; font-size: 15px; color: #000;">Loại xe</label>
                            <vs-input v-model="item.name" type="text" size="default" placeholder="Tên loại xe" class="w-100"/>
                        </div>
                        <button v-if="key != 0" class="btn btn-danger" @click="removeObjCarServicePrices(key)" style="padding: 8px 10px;">
                            <i class="fa fa-trash"> Xóa</i>
                        </button>
                    </div>
                    <div class="form-group">
                        <label>Nội dung</label>
                        <TinyMce
                        v-model="item.description"
                        :height="300"
                        />
                    </div>
                </div>
                <!-- <div class="col-md-8">
                </div> -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Ảnh sản phẩm</label>
                        <ImageMulti v-model="item.images" :title="'loai-xe'"/>
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <tbody v-for="(detail, detailKey) in item.details" :key="detailKey">
                            <!-- Hàng chứa Khu vực -->
                            <tr style="background-color: #f5f5f5;">
                                <td colspan="6">
                                    <div class="form-group d-flex align-items-center" style="gap: 10px;">
                                        <label style="font-weight: 500; font-size: 14px; color: #000;">Khu vực</label>
                                        <vs-select v-model="detail.province_id">
                                            <vs-select-item v-for="province in provinces" :key="province.id" :value="province.id" :text="province._name" />
                                        </vs-select>
                                        <button v-if="detailKey == 0" class="btn btn-success" @click="addObjCarServicePricesDetail(key, detailKey)" style="padding: 8px 10px;">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                        <button v-if="detailKey != 0" class="btn btn-danger" @click="removeObjCarServicePricesDetail(key, detailKey)" style="padding: 8px 10px;">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Tiêu đề danh sách giá -->
                            <tr style="background-color: #f5f5f5;">
                                <td style="vertical-align: middle; text-align: center;" rowspan="2">
                                    <span @click="addObjCarServicePricesPrice(key, detailKey)" style="cursor: pointer;">
                                        <i class="fa fa-plus" style="font-size: 16px; color: #18ce0f;"></i>
                                    </span>
                                </td>
                                <td style="vertical-align: middle; text-align: center;" rowspan="2">Điểm đi</td>
                                <td style="vertical-align: middle; text-align: center;" rowspan="2">Điểm đến</td>
                                <td style="vertical-align: middle; text-align: center;" colspan="3">Khoảng giá</td>
                            </tr>
                            <tr style="background-color: #f5f5f5;">
                                <td style="vertical-align: middle; text-align: center;">Giá nhỏ nhất</td>
                                <td style="vertical-align: middle; text-align: center;">Giá lớn nhất</td>
                                <td style="vertical-align: middle; text-align: center;">Giá 2 chiều</td>
                            </tr>

                            <!-- Lặp danh sách giá (Phải để TRONG TBODY CỦA DETAIL) -->
                            <tr v-for="(price, priceKey) in detail.list_prices" :key="`${key}-${detailKey}-${priceKey}`">
                                <td style="vertical-align: middle; text-align: center;">
                                    <span @click="removeObjCarServicePricesPrice(key, detailKey, priceKey)" style="cursor: pointer;">
                                        <i class="fa fa-minus" style="font-size: 16px; color: #FF3636;"></i>
                                    </span>
                                </td>
                                <td>
                                    <vs-input v-model="price.place_from" type="text" size="default" placeholder="Điểm đi" class="w-100"/>
                                </td>
                                <td>
                                    <vs-input v-model="price.place_to" type="text" size="default" placeholder="Điểm đến" class="w-100"/>
                                </td>
                                <td>
                                    <vs-input v-model="price.price_min" type="number" size="default" class="w-100"/>
                                </td>
                                <td>
                                    <vs-input v-model="price.price_max" type="number" size="default" class="w-100"/>
                                </td>
                                <td>
                                    <vs-input v-model="price.price_2_way" type="number" size="default" class="w-100"/>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr style="border: 0.5px solid #04040426; width: 100%" />
            </div>
            <vs-button color="primary" @click="saveData">Lưu bảng giá</vs-button>
            <vs-button color="success" @click="addObjCarServicePrices">Thêm loại xe</vs-button>
        </div>
        </div>
    </div>
    </div>
    <!-- content-wrapper ends -->
</div>
</template>


<script>
import { mapActions } from "vuex";
import { required } from "vuelidate/lib/validators";
import TinyMce from "../_common/tinymce.vue";
import ImageMulti from "../_common/upload_image_multi";

export default {
name: "product",
data() {
    return {
        objData: [
            {
                name: "",
                images: [],
                description: "",
                details: [
                    {
                        province_id: "",
                        province_name: "",
                        list_prices: [
                            {
                                place_from: "",
                                place_to: "",
                                price_min: 0,
                                price_max: 0,
                                price_2_way: 0,
                            },
                        ],
                    },
                ],
            },
        ],
        loading: false,
        provinces: [],
    };
},
components: {
    TinyMce,
    ImageMulti,
},
computed: {},
watch: {},
methods: {
    ...mapActions(["saveCarServicePrices", "loadings", "listCarServicePrices", "listProvince"]),
    saveData() {
        console.log(this.objData);
        this.loadings(true);
        this.saveCarServicePrices({ data: this.objData })
            .then((response) => {
            this.loadings(false);
            this.$success(response.message);
            })
            .catch((error) => {
            this.loadings(false);
            this.$error(error.message);
            });
    },

    addObjCarServicePrices() {
        this.objData.push({
            name: "",
            images: [],
            description: "",
            details: [
                {
                    province_id: "",
                    province_name: "",
                    list_prices: [
                        {
                            place_from: "",
                            place_to: "",
                            price_min: 0,
                            price_max: 0,
                            price_2_way: 0,
                        },
                    ],
                },
            ],
        });
    },
    removeObjCarServicePrices(i) {
    this.objData.splice(i, 1);
    },

    addObjCarServicePricesDetail(i) {
        this.objData[i].details.push({
            province_id: "",
            province_name: "",
            list_prices: [
                {
                    place_from: "",
                    place_to: "",
                    price_min: 0,
                    price_max: 0,
                    price_2_way: 0,
                },
            ],
        });
    },
    removeObjCarServicePricesDetail(i, j) {
        this.objData[i].details.splice(j, 1);
    },

    addObjCarServicePricesPrice(i, j) {
        this.objData[i].details[j].list_prices.push({
            place_from: "",
            place_to: "",
            price_min: 0,
            price_max: 0,
            price_2_way: 0,
        });
    },
    removeObjCarServicePricesPrice(i, j, k) {
        this.objData[i].details[j].list_prices.splice(k, 1);
    },
    listData() {
    this.loadings(true);
    this.listCarServicePrices()
        .then((response) => {
        this.loadings(false);
        this.objData = response.data;
        })
        .catch((error) => {
        this.loadings(false);
        });
    },

    getProvinces() {
        this.listProvince()
        .then((response) => {
            this.provinces = response.data;
            this.loading = false;
        })
        .catch((error) => {
            this.loading = false;
        });
    },
},
mounted() {
    this.listData();

    this.getProvinces();
},
};
</script>
<style scoped>
    table.table-bordered td{
        padding: 5px 7px!important;
    }
</style>
