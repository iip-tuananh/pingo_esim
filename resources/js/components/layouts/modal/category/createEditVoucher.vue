<template>
	<div>
		<form class="forms-sample" enctype="multipart/form-data">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label for="file">
							Mã
							<span class="text-danger">*</span>
						</label>
						<vs-input
							class="selectExample w-100"
							v-model="objData.code"
							:class="{ 'is-invalid': submitted && $v.objData.code.$error }"
						/>
					</div>
					<div class="form-group">
						<label for="file">
							Tên voucher
							<span class="text-danger">*</span>
						</label>
						<vs-input
							class="selectExample w-100"
							v-model="objData.name"
							:class="{ 'is-invalid': submitted && $v.objData.name.$error }"
						/>
					</div>
					<div class="form-group">
						<label for="file">
							Mô tả ngắn gọn
							<span class="text-danger">*</span>
						</label>
						<textarea
							class="selectExample w-100"
							v-model="objData.description"
							rows="3"
							:class="{ 'is-invalid': submitted && $v.objData.description.$error }"
						></textarea>
					</div>
					<div class="form-group">
						<label for="file">
							Số lượng mã
							<span class="text-danger">*</span>
						</label>
						<vs-input
							type="number"
							class="selectExample w-100"
							v-model="objData.quantity"
							:class="{ 'is-invalid': submitted && $v.objData.quantity.$error }"
						/>
					</div>
					<div class="form-group">
						<label for="file">
							Giá trị giảm áp dụng trên đơn hàng
							<span class="text-danger">*</span>
						</label>
						<vs-input
							type="number"
							class="selectExample w-100"
							v-model="objData.value"
							:class="{ 'is-invalid': submitted && $v.objData.value.$error }"
						/>
					</div>
					<div class="form-group">
						<label for="file">Quy định số lượng tối thiểu</label>
						<vs-input
							type="number"
							class="selectExample w-100"
							v-model="objData.limit_product_qty"
						/>
					</div>
					<div class="form-group">
						<label for="file">Quy định giá trị tối thiểu</label>
						<vs-input
							type="number"
							class="selectExample w-100"
							v-model="objData.limit_bill_value"
						/>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="file">Ngày bắt đầu</label>
								<vs-input
									type="date"
									class="selectExample w-100"
									v-model="objData.from_date"
									:class="{
										'is-invalid': submitted && $v.objData.from_date.$error,
									}"
								/>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="file">Ngày kết thúc</label>
								<vs-input
									type="date"
									class="selectExample w-100"
									v-model="objData.to_date"
									:class="{
										'is-invalid': submitted && $v.objData.to_date.$error,
									}"
								/>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="file">Trạng thái</label>
						<vs-select
							class="selectExample w-100"
							v-model="objData.status"
							placeholder="Trạng thái"
							:class="{ 'is-invalid': submitted && $v.objData.status.$error }"
						>
							<vs-select-item :value="1" :text="'Hoạt động'" />
							<vs-select-item :value="0" :text="'Ngưng hoạt động'" />
						</vs-select>
					</div>
				</div>
				<div class="col-md-8">
					<div class="d-flex mb-3">
						<input
							type="checkbox"
							v-model="objData.is_apply_all_product"
							id="all_product"
							style="height: 20px; width: 20px; margin-right: 10px"
						/>
						<label for="all_product" class="label-checkbox">
							Áp dụng cho tất cả sản phẩm
						</label>
					</div>
					<div class="form-group" v-if="!objData.is_apply_all_product">
						<label for="file">Áp dụng cho sản phẩm</label>
						<vs-select
							class="selectExample w-100"
							v-model="objData.product_ids"
							multiple
						>
							<vs-select-item
								:value="item.id"
								:text="JSON.parse(item.name)[0].content"
								v-for="(item, index) in products"
								:key="'f' + index"
							/>
						</vs-select>
					</div>

					<TinyMce v-model="objData.content" />
				</div>
			</div>

			<div class="form-group mt-3">
				<vs-button
					color="success"
					type="gradient"
					class="mr-left-45"
					@click="handleSubmit()"
					:loading="loading"
				>
					<i class="fa fa-spinner fa-spin" v-if="loading"></i>
					Cập nhật
				</vs-button>
			</div>
		</form>
	</div>
</template>

<script>
import { required, email, minLength, sameAs } from 'vuelidate/lib/validators';
import { mapActions, mapGetters } from 'vuex';
import TinyMce from '../../../_common/tinymce';

export default {
	data() {
		return {
			objData: {
				name: '',
				code: '',
				description: '',
				quantity: '',
				value: '',
				limit_product_qty: '',
				limit_bill_value: '',
				from_date: '',
				to_date: '',
				status: '',
				is_apply_all_product: false,
				product_ids: [],
			},
			submitted: false,
			loading: false,
			products: [],
		};
	},
	components: {
		TinyMce,
	},
	computed: {},
	validations: {
		objData: {
			name: { required },
			code: { required },
			description: { required },
			quantity: { required },
			value: { required },
			from_date: { required },
			to_date: { required },
			status: { required },
		},
	},
	methods: {
		...mapActions(['loadings', 'addVoucher', 'detailVoucher', 'listProduct']),
		handleSubmit() {
			this.submitted = true;
			this.loading = true;
			this.$v.$touch();
			if (this.$v.$invalid) {
				this.loading = false;
				return;
			}
			let data = this.objData;
			this.addVoucher(data)
				.then(response => {
					if (response.success) {
						this.$notify.success('Thao tác thành công');
						this.$emit('closePopup', false);
						this.$emit('updateList');
						this.loading = false;
					} else {
						this.$notify.error('Có lỗi xảy ra. Vui lòng thử lại!');
						this.loading = false;
					}
				})
				.catch(error => {
					this.$notify.error('Có lỗi xảy ra. Vui lòng thử lại!');
					this.loading = false;
				});
		},
		callResetPopup(id) {
			if (id == null) {
				this.objData = {
					name: '',
					code: '',
					description: '',
					quantity: '',
					value: '',
					limit_product_qty: '',
					limit_bill_value: '',
					from_date: '',
					to_date: '',
					status: '',
					is_apply_all_product: false,
					product_ids: [],
				};
			}
			this.detailVoucher({ id: id }).then(response => {
				this.objData = response.data;
				this.objData.product_ids = JSON.parse(this.objData.product_ids);
			});
			this.submitted = false;
			this.loading = false;
		},
	},
	mounted() {
		this.listProduct({}).then(response => {
			this.products = response.data;
		});
	},
};
</script>
<style>
.my-custom-popup .vs-popup {
	width: 1200px !important;
}
.my-custom-popup label {
	position: absolute;
	z-index: 10;
	top: -10px;
	left: 12px;
	background: #ffff;
	padding: 0 5px;
}
.my-custom-popup .label-checkbox {
	font-weight: 500;
	position: relative;
	top: 0;
	left: 0;
}
textarea {
	resize: vertical;
	border: 1px solid #ccc;
	border-radius: 4px;
	padding: 10px;
}
.form-group {
	margin-bottom: 20px;
}
</style>
