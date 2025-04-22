<template>
	<div class="row">
		<div class="col-md-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title mt-0">Danh sách title</h4>
					<p class="card-description">Thêm mới hoặc chỉnh sửa title</p>

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
									<el-dropdown-item command="addCate">
										Thêm phân loại
									</el-dropdown-item>
									<!-- <el-dropdown-item command="delete">Xoá</el-dropdown-item>
                                    <el-dropdown-item command="view">Xem chi tiết</el-dropdown-item> -->
								</el-dropdown-menu>
							</el-dropdown>
							<button
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
							</button>
							<router-link class="nav-link" :to="{ name: 'createProduct' }">
								<vs-button type="gradient" style="float: right">Thêm mới</vs-button>
							</router-link>
						</div>
					</div>
					<div class="row collapse-filter" v-if="activeCollapse">
						<div class="col-md-3">
							<div class="form-group">
								<input
									type="text"
									placeholder="Tên title"
									v-model="keyword"
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
							<vs-th>Ảnh title</vs-th>
							<vs-th>Tên title</vs-th>
							<vs-th>Handle</vs-th>
							<vs-th>Options</vs-th>
							<vs-th>Phân loại</vs-th>
							<!-- <vs-th>Số lượng</vs-th> -->
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
								<vs-td>
									<vs-avatar size="large" :src="JSON.parse(tr.images)[0]" />
								</vs-td>
								<vs-td>{{ JSON.parse(tr.name)[0].content }}</vs-td>
								<vs-td>{{ tr.sku }}</vs-td>
								<vs-td>
									<span class="badge badge-primary badge-pill" @click="openOption(tr.id)">
										{{ tr.product_options.length }}
									</span>
								</vs-td>
								<vs-td>
									{{
										tr.cate != null
											? JSON.parse(tr.cate)[0].content
											: '--Trống--'
									}}
								</vs-td>
								<vs-td>
									<router-link
										:to="{ name: 'edit_product', params: { id: tr.id } }"
									>
										<vs-button
											vs-type="gradient"
											size="lagre"
											color="success"
											icon="edit"
										></vs-button>
									</router-link>
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
			title="Cập nhật phân loại"
			:active.sync="popupActiveUpdateCate"
		>
			<UpdateCate
				@closePopup="closePop($event)"
				ref="updateCateComponent"
				:arrayItemChecked="arrayItemChecked"
				@updateList="updateList"
			/>
		</vs-popup>
	</div>
</template>

<script>
import Swal from 'sweetalert2';
import { mapActions } from 'vuex';
import AddImportExcel from '../../components/layouts/modal/excels/importExcel.vue';
import UpdateCate from '../../components/layouts/modal/category/updateCate.vue';
export default {
	data() {
		return {
			list: [],
			keyword: '',
			objDel: {
				id_item: '',
				slug: '',
			},
			popupActivo: false,
			popupActiveUpdateCate: false,
			activeCollapse: false,
			arrayItemChecked: [],
		};
	},
	components: {
		AddImportExcel,
		UpdateCate,
	},
	computed: {},
	watch: {
		popupActiveUpdateCate(newVal) {
			if (newVal) {
				this.$refs.updateCateComponent.callListCate();
			}
		},
	},
	methods: {
		...mapActions(['listProduct', 'deleteId', 'loadings']),
		closePop(event) {
			this.popupActivo = event;
		},
		updateList() {
			this.popupActiveUpdateCate = false;
			this.arrayItemChecked = [];
			this.listProducts();
		},
		listProducts() {
			this.loadings(true);
			this.listProduct({ keyword: this.keyword })
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
				this.listProduct({ keyword: this.keyword }).then(response => {
					this.list = response.data;
				});
			}, 800);
		},
		destroy() {
			this.deleteId(this.objDel).then(response => {
				this.listProducts();
				this.$notify.success('Xóa title thành công');
			});
		},
		confirmDestroy(id, slug) {
			this.objDel.id_item = id;
			this.$vs.dialog({
				type: 'confirm',
				color: 'danger',
				title: `Bạn có chắc chắn muốn xóa title này?`,
				text: 'Xóa title này',
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
			if (command === 'addCate') {
				if (this.arrayItemChecked.length == 0) {
					this.$notify.error('Vui lòng chọn title để cập nhật phân loại');
					return;
				}
				this.popupActiveUpdateCate = true;
			}
		},
		openOption(id) {
			this.$router.push({ name: 'list_options', params: { product_id: id } });
		},
	},
	mounted() {
		this.listProducts();
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
