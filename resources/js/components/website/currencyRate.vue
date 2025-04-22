<template>
	<div class="row">
		<div class="col-md-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title mt-0">Danh sách giá quy đổi</h4>
					<p class="card-description">Bảng giá tự động cập nhật hàng tuần</p>

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

							<!-- <el-dropdown @command="handleCommand">
								<el-button
									class="btn btn-success"
									style="
										float: right;
										font-size: 14px;
										margin: 8px;
										margin-right: 0px;
										border-radius: 6px;
									"
								>
									Hành động
									<i class="el-icon-arrow-down el-icon--right"></i>
								</el-button>
								<el-dropdown-menu slot="dropdown">
									<el-dropdown-item command="updatePriceMultiple">
										Chỉnh sửa giá
									</el-dropdown-item>
								</el-dropdown-menu>
							</el-dropdown> -->
						</div>
					</div>
					<div class="row collapse-filter" v-if="activeCollapse">
						<!-- <div class="col-md-3">
							<div class="form-group">
								<input
									type="text"
									placeholder="Đơn vị tiêu chuẩn"
									v-model="filter.base_currency"
									@input="searchProduct()"
									class="form-control"
								/>
							</div>
						</div> -->
						<div class="col-md-3">
							<div class="form-group">
								<input
									type="text"
									placeholder="Đơn vị quy đổi"
									v-model="filter.currency"
									@input="searchProduct()"
									class="form-control"
								/>
							</div>
						</div>
					</div>
					<vs-table stripe :data="list" max-items="10" pagination>
						<template slot="thead">
							<vs-th>
								<input type="checkbox" @change="checkAll" />
							</vs-th>
							<vs-th>Đơn vị tiêu chuẩn</vs-th>
							<vs-th>Đơn vị quy đổi</vs-th>
							<vs-th>Tỷ giá</vs-th>
							<vs-th>Ngày cập nhật</vs-th>
							<!-- <vs-th style="width: 10%">Hành động</vs-th> -->
						</template>
						<template slot-scope="{ data }">
							<vs-tr :key="indextr" v-for="(tr, indextr) in data">
								<vs-td>
									<input
										type="checkbox"
										:value="tr.id"
										@change="checkItem(tr.id)"
										:checked="tr.checked"
									/>
								</vs-td>
								<vs-td>{{ tr.base_currency }}</vs-td>
								<vs-td>{{ tr.currency }}</vs-td>
								<vs-td>
									{{ formatPriceCurrency(tr.rate, tr.currency) }}
								</vs-td>
								<vs-td>{{ formatDate(tr.updated_at) }}</vs-td>
								<!-- <vs-td>
									<vs-button
										vs-type="gradient"
										size="lagre"
										color="success"
										icon="edit"
										@click="editOption(tr.id)"
									></vs-button>
								</vs-td> -->
							</vs-tr>
						</template>
					</vs-table>
				</div>
			</div>
		</div>
		<vs-popup style="width: 100%" :title="titleOption" :active.sync="popupActivo">
			<EditCurrencyRate
				@closePopup="closePop($event)"
				ref="editCurrencyRateComponent"
				@updateList="updateList"
			/>
		</vs-popup>
	</div>
</template>

<script>
import Swal from 'sweetalert2';
import { mapActions } from 'vuex';
import moment from 'moment';
import EditCurrencyRate from '../../components/layouts/modal/category/editCurrencyRate.vue';
export default {
	data() {
		return {
			list: [],
			filter: {
				base_currency: '',
				currency: '',
			},
			objDel: {
				id_item: '',
			},
			popupActivo: false,
			arrayItemChecked: [],
			activeCollapse: false,
			titleOption: 'Thêm mới giá quy đổi',
		};
	},
	components: {
		EditCurrencyRate,
	},
	computed: {},
	watch: {
		popupActiveUpdatePriceMultiple(newVal) {
			if (newVal) {
				this.$refs.updatePriceMultipleComponent.callResetPopup();
			}
		},
	},
	methods: {
		...mapActions(['listCurrencyRate', 'deleteCurrencyRateId', 'loadings']),
		closePop(event) {
			this.popupActivo = event;
		},
		updateList() {
			this.popupActiveUpdatePriceMultiple = false;
			this.popupActiveEditOption = false;
			this.arrayItemChecked = [];
			this.getListCurrencyRate();
		},
		getListCurrencyRate() {
			this.loadings(true);
			this.listCurrencyRate(this.filter)
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
				console.log(this.filter);
				this.listCurrencyRate(this.filter).then(response => {
					this.list = response.data;
				});
			}, 800);
		},
		destroy() {
			this.deleteCurrencyRateId(this.objDel).then(response => {
				this.getListCurrencyRate();
				this.$notify.success('Xóa giá quy đổi thành công');
			});
		},
		confirmDestroy(id) {
			this.objDel.id_item = id;
			this.$vs.dialog({
				type: 'confirm',
				color: 'danger',
				title: `Bạn có chắc chắn muốn xóa giá quy đổi này?`,
				text: 'Xóa giá quy đổi này',
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
		handleCommand(command) {
			if (command === 'updatePriceMultiple') {
				if (this.arrayItemChecked.length == 0) {
					this.$notify.error('Vui lòng chọn giá quy đổi để cập nhật giá');
					return;
				}
				this.popupActiveUpdatePriceMultiple = true;
			}
		},
		formatPriceCurrency(price, type) {
			if (price == null) {
				return '0';
			}
			const parsedPrice = Number(price);
			if (type) {
				return parsedPrice.toLocaleString('vi-VN', {
					style: 'currency',
					currency: type,
					minimumFractionDigits: 0,
					maximumFractionDigits: 6,
				});
			}
		},
		formatDate(date) {
			return moment(date).format('DD/MM/YYYY HH:mm');
		},
		editOption(id) {
			this.popupActiveEditOption = true;
			this.$refs.editOptionComponent.callResetPopup(id);
			this.titleOption = 'Chỉnh sửa giá quy đổi';
		},
		createOption() {
			this.popupActiveEditOption = true;
			this.titleOption = 'Thêm mới giá quy đổi';
		},
	},
	mounted() {
		this.getListCurrencyRate();
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
</style>
