<template>
	<div class="row">
		<div class="col-md-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title mt-0">Danh sách voucher</h4>
					<p class="card-description">Thêm mới hoặc chỉnh sửa voucher</p>

					<div class="row">
						<div class="col-md-12">
							<button
								class="btn btn-primary"
								style="
									float: right;
									font-size: 14px;
									margin: 8px 0 8px 8px;
									border-radius: 6px;
								"
								@click="activeCollapse = !activeCollapse"
							>
								<i class="fa fa-filter"></i>
								Bộ lọc
							</button>

							<div class="nav-link" @click="createVoucher">
                                <vs-button type="gradient" style="float: right">Thêm mới</vs-button>
                            </div>
						</div>
					</div>
					<div class="row collapse-filter" v-if="activeCollapse">
						<div class="col-md-3">
							<div class="form-group">
								<input
									type="text"
									placeholder="Tên voucher"
									v-model="filter.keyword"
									@input="searchProduct()"
									class="form-control"
								/>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<input
									type="text"
									placeholder="Mã voucher"
									v-model="filter.title"
									class="form-control"
									@input="searchProduct()"
								/>
							</div>
						</div>
					</div>
					<vs-table stripe :data="list" max-items="10" pagination>
						<template slot="thead">
							<!-- <vs-th>
								<input type="checkbox" @change="checkAll" />
							</vs-th> -->
							<vs-th>Mã voucher</vs-th>
							<vs-th>Tên voucher</vs-th>
							<vs-th>Giá trị</vs-th>
							<vs-th>Trạng thái</vs-th>
							<vs-th>Số lượng còn lại</vs-th>
							<vs-th>Ngày bắt đầu</vs-th>
							<vs-th>Ngày kết thúc</vs-th>
							<vs-th style="width: 10%">Hành động</vs-th>
						</template>
						<template slot-scope="{ data }">
							<vs-tr :key="indextr" v-for="(tr, indextr) in data">
								<!-- <vs-td>
									<input
										type="checkbox"
										:value="tr.id"
										@change="checkItem(tr.id)"
										:checked="tr.checked"
									/>
								</vs-td> -->
								<vs-td>{{ tr.code }}</vs-td>
								<vs-td>{{ tr.name }}</vs-td>
								<vs-td>{{ formatPrice(tr.value) }}</vs-td>
								<vs-td><div v-html="getStatus(tr.status)"></div></vs-td>
								<vs-td>{{ tr.quantity }}</vs-td>
								<vs-td>{{ tr.from_date }}</vs-td>
								<vs-td>{{ tr.to_date }}</vs-td>
								<vs-td>
									<vs-button
										vs-type="gradient"
										size="lagre"
										color="success"
										icon="edit"
										@click="editVoucher(tr.id)"
									></vs-button>
									<vs-button
										vs-type="gradient"
										size="lagre"
										color="red"
										icon="delete_forever"
										@click="confirmDestroy(tr.id)"
									></vs-button>
								</vs-td>
							</vs-tr>
						</template>
					</vs-table>
				</div>
			</div>
		</div>
		<vs-popup
			style="width: 100%;"
			:title="titleOption"
			:active.sync="popupActivo"
            class="my-custom-popup"
		>
			<CreateEditVoucher
				@closePopup="closePop($event)"
				ref="editVoucherComponent"
				@updateList="updateList"
			/>
		</vs-popup>
	</div>
</template>

<script>
import Swal from 'sweetalert2';
import { mapActions } from 'vuex';
import CreateEditVoucher from '../../components/layouts/modal/category/createEditVoucher.vue';
export default {
	data() {
		return {
			list: [],
			filter: {
				keyword: '',
				title: '',
			},
			objDel: {
				id_item: '',
			},
			popupActivo: false,
			arrayItemChecked: [],
			activeCollapse: false,
			titleOption: 'Thêm mới option',
		};
	},
	components: {
		CreateEditVoucher,
	},
	computed: {},
	watch: {

    },
	methods: {
		...mapActions(['listVouchers', 'deleteVoucherId', 'loadings']),
		closePop(event) {
			this.popupActivo = event;
		},
		updateList() {
			this.popupActivo = false;
			this.arrayItemChecked = [];
			this.getListVouchers();
		},
		getListVouchers() {
			this.loadings(true);
            let product_id = this.$route.params.product_id
            this.filter.product_id = product_id
			this.listVouchers(this.filter)
				.then(response => {
					this.loadings(false);
					this.list = response.data;
				})
				.catch(err => {
					this.loadings(false);
					this.list = err.data;
				});
		},
		searchProduct() {
			if (this.timer) {
				clearTimeout(this.timer);
				this.timer = null;
			}
			this.timer = setTimeout(() => {
				this.listProductOptions(this.filter).then(response => {
					this.list = response.data;
				});
			}, 800);
		},
		destroy() {
			this.deleteVoucherId(this.objDel).then(response => {
				this.getListVouchers();
				this.$notify.success('Xóa voucher thành công');
			});
		},
		confirmDestroy(id) {
			this.objDel.id_item = id;
			this.$vs.dialog({
				type: 'confirm',
				color: 'danger',
				title: `Bạn có chắc chắn muốn xóa voucher này?`,
				text: 'Xóa voucher này',
				accept: this.destroy,
			});
		},
		checkAll(event) {
			this.list.forEach(item => {
				item.checked = event.target.checked;
				if (item.checked) {
					this.arrayItemChecked.push(item.id);
				} else {
					this.arrayItemChecked = this.arrayItemChecked.filter(id => id !== item.id);
				}
			});
			this.list = [...this.list];
		},
		checkItem(id) {
			this.list = this.list.map(item => {
				if (item.id === id) {
					item.checked = !item.checked;
					if (item.checked) {
						this.arrayItemChecked.push(item.id);
					} else {
						this.arrayItemChecked = this.arrayItemChecked.filter(id => id !== item.id);
					}
				}
				return item;
			});
		},
		formatPrice(price, type = 'vnd') {
			if (price == null) {
				return '0';
			}
			const parsedPrice = Number(price);
			if (type == 'usd') {
				return parsedPrice.toLocaleString('vi-VN', { style: 'currency', currency: 'USD' });
			}
			return parsedPrice.toLocaleString('vi-VN', {
				style: 'currency',
				currency: 'VND',
				minimumFractionDigits: 0,
				maximumFractionDigits: 0,
			});
		},
        getStatus(status) {
            return status == 1 ? '<span class="badge badge-success">Hoạt động</span>' : '<span class="badge badge-danger">Ngưng hoạt động</span>'
        },
		editVoucher(id) {
			this.popupActivo = true;
			this.$refs.editVoucherComponent.callResetPopup(id);
			this.titleOption = 'Chỉnh sửa voucher';
		},
		createVoucher() {
			this.popupActivo = true;
            this.$refs.editVoucherComponent.callResetPopup(null);
			this.titleOption = 'Thêm mới voucher';
		},
	},
	mounted() {
		this.getListVouchers();
	},
};
</script>
<style scoped>
.el-dropdown {
	display: block;
}
.el-dropdown-menu {
	position: absolute;
	top: 160px !important;
	right: 150px !important;
	left: auto !important;
	z-index: 1000;
}
.badge-pill {
	font-size: 14px;
	padding: 0;
	border-radius: 5px;
	background-color: #007bff;
	color: #fff;
	border: none;
	width: 32px;
	height: 30px;
	text-align: center;
	line-height: 2.2;
	cursor: pointer;
}
.collapse-filter {
	margin: 0px;
	background-color: #f5f5f5;
	padding: 10px;
	border-radius: 5px;
}
.form-group {
	margin-bottom: 12px;
}
.form-group input {
	border-radius: 5px;
	background-color: #fff;
	border: 1px solid #ccc;
	padding: 5px 10px;
	height: 38px;
}
.form-group input::placeholder {
	color: #929292;
}
.btn-filter {
	border-radius: 5px;
	border: 1px solid #ccc;
	padding: 5px 10px;
	height: 38px;
	margin: 0;
	margin-right: 4px;
}
.vs-table--thead {
	height: 40px !important;
}
.vs-table--thead th {
	background-color: #f5f5f5 !important;
	height: 40px !important;
}
.con-vs-popup .vs-popup {
	width: 800px !important;
}
</style>
