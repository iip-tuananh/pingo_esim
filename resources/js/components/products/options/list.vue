<template>
	<div class="row">
		<div class="col-md-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title mt-0">Danh sách options</h4>
					<p class="card-description">Thêm mới hoặc chỉnh sửa option</p>

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

							<el-dropdown @command="handleCommand">
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
									<!-- <el-dropdown-item command="delete">Xoá</el-dropdown-item>
							<el-dropdown-item command="view">Xem chi tiết</el-dropdown-item> -->
								</el-dropdown-menu>
							</el-dropdown>
							<!-- <button
                                class="btn btn-success"
                                style="
                                    float: right;
                                    font-size: 14px;
                                    margin: 8px 0 8px 8px;
                                    border-radius: 6px;
                                "
                                @click="popupActivo = true"
                            >
                                Import excel
                            </button> -->
							<div class="nav-link" @click="createOption">
                                <vs-button type="gradient" style="float: right">Thêm mới</vs-button>
                            </div>
						</div>
					</div>
					<div class="row collapse-filter" v-if="activeCollapse">
						<div class="col-md-3">
							<div class="form-group">
								<input
									type="text"
									placeholder="Tên option"
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
									placeholder="Title"
									v-model="filter.title"
									class="form-control"
									@input="searchProduct()"
								/>
							</div>
						</div>
					</div>
					<vs-table stripe :data="list" max-items="10" pagination>
						<template slot="thead">
							<vs-th>
								<input type="checkbox" @change="checkAll" />
							</vs-th>
							<vs-th>Tên option</vs-th>
							<vs-th>Sku (Mã option)</vs-th>
							<vs-th>Title</vs-th>
							<vs-th>Giá USD</vs-th>
							<vs-th>Giá VNĐ</vs-th>
							<vs-th style="width: 10%">Hành động</vs-th>
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
								<vs-td>{{ tr.name }}</vs-td>
								<vs-td>{{ tr.sku }}</vs-td>
								<vs-td>
									{{
										tr.product != null
											? JSON.parse(tr.product.name)[0].content
											: '--Trống--'
									}}
								</vs-td>
								<vs-td>
									{{ formatPrice(tr.price_usd, 'usd') }}
								</vs-td>
								<vs-td>
									{{ formatPrice(tr.price_vnd, 'vnd') }}
								</vs-td>
								<vs-td>
									<vs-button
										vs-type="gradient"
										size="lagre"
										color="success"
										icon="edit"
										@click="editOption(tr.id)"
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
		<vs-popup style="width: 100%" title="Inport excel" :active.sync="popupActivo">
			<AddImportExcel @closePopup="closePop($event)" />
		</vs-popup>
		<vs-popup
			style="width: 100%"
			title="Cập nhật giá"
			:active.sync="popupActiveUpdatePriceMultiple"
		>
			<UpdatePriceMultiple
				@closePopup="closePop($event)"
				:arrayItemChecked="arrayItemChecked"
                ref="updatePriceMultipleComponent"
				@updateList="updateList"
			/>
		</vs-popup>
		<vs-popup
			style="width: 100%"
			:title="titleOption"
			:active.sync="popupActiveEditOption"
		>
			<EditOption
				@closePopup="closePop($event)"
				ref="editOptionComponent"
				@updateList="updateList"
			/>
		</vs-popup>
	</div>
</template>

<script>
import Swal from 'sweetalert2';
import { mapActions } from 'vuex';
import AddImportExcel from '../../../components/layouts/modal/excels/importExcel.vue';
import UpdatePriceMultiple from '../../../components/layouts/modal/category/updatePriceMultiple.vue';
import EditOption from '../../../components/layouts/modal/category/editOption.vue';
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
			popupActiveUpdatePriceMultiple: false,
			arrayItemChecked: [],
			activeCollapse: false,
			popupActiveEditOption: false,
			titleOption: 'Thêm mới option',
		};
	},
	components: {
		AddImportExcel,
		UpdatePriceMultiple,
		EditOption,
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
		...mapActions(['listProductOptions', 'deleteProductOptionId', 'loadings']),
		closePop(event) {
			this.popupActivo = event;
		},
		updateList() {
			this.popupActiveUpdatePriceMultiple = false;
			this.popupActiveEditOption = false;
			this.arrayItemChecked = [];
			this.getListProductOptions();
		},
		getListProductOptions() {
			this.loadings(true);
            let product_id = this.$route.params.product_id
            this.filter.product_id = product_id
			this.listProductOptions(this.filter)
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
			this.deleteProductOptionId(this.objDel).then(response => {
				this.getListProductOptions();
				this.$notify.success('Xóa option thành công');
			});
		},
		confirmDestroy(id) {
			this.objDel.id_item = id;
			this.$vs.dialog({
				type: 'confirm',
				color: 'danger',
				title: `Bạn có chắc chắn muốn xóa option này?`,
				text: 'Xóa option này',
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
					this.$notify.error('Vui lòng chọn option để cập nhật giá');
					return;
				}
				this.popupActiveUpdatePriceMultiple = true;
			}
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
		editOption(id) {
			this.popupActiveEditOption = true;
			this.$refs.editOptionComponent.callResetPopup(id);
			this.titleOption = 'Chỉnh sửa option';
		},
		createOption() {
			this.popupActiveEditOption = true;
			this.titleOption = 'Thêm mới option';
		},
	},
	mounted() {
		this.getListProductOptions();
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
