<template>
	<div>
		<form class="forms-sample" enctype="multipart/form-data">
			<div class="form-group">
				<label for="file">
					Tên option
					<span class="text-danger">*</span>
				</label>
				<vs-input
					class="selectExample w-100"
					v-model="objData.name"
					placeholder="Tên option"
				/>
			</div>
			<div class="form-group">
				<label for="file">
					Sku (mã option)
					<span class="text-danger">*</span>
				</label>
				<vs-input class="selectExample w-100" v-model="objData.sku" placeholder="Sku" />
			</div>
			<div class="form-group">
				<label for="file">
					Chọn title
					<span class="text-danger">*</span>
				</label>
				<vs-select class="selectExample w-100" v-model="objData.product_id" placeholder="Title">
					<vs-select-item value="0" text="Không danh mục" />
					<vs-select-item
						:value="item.id"
						:text="JSON.parse(item.name)[0].content"
						v-for="(item, index) in products"
						:key="'f' + index"
					/>
				</vs-select>
				<span v-if="submitted && $v.objData.category.$error" class="text-danger">
					Vui lòng chọn phân loại
				</span>
			</div>
			<div class="form-group">
				<label for="file">
					Giá VND
					<span class="text-danger">*</span>
				</label>
				<vs-input
					class="selectExample w-100"
					v-model="objData.price_vnd"
					placeholder="Giá"
				/>
                <span class="text-danger" v-if="submitted && $v.objData.price_vnd.$error">
                    Vui lòng nhập giá
                </span>
			</div>
			<div class="form-group">
				<label for="file">
					Giá USD
					<span class="text-danger">*</span>
				</label>
				<vs-input
					class="selectExample w-100"
					v-model="objData.price_usd"
					placeholder="Giá"
				/>
                <span class="text-danger" v-if="submitted && $v.objData.price_usd.$error">
                    Vui lòng nhập giá
                </span>
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
export default {
	data() {
		return {
			objData: {
                name: '',
                sku: '',
                product_id: '',
                price_vnd: '',
                price_usd: '',
            },
			submitted: false,
			loading: false,
			products: [],
		};
	},
	computed: {},
	validations: {
		objData: {
			name: { required },
			sku: { required },
			product_id: { required },
			price_vnd: { required },
			price_usd: { required },
		},
	},
	methods: {
		...mapActions(['loadings', 'updateOption', 'getOptionDetail', 'listProduct']),
		handleSubmit() {
			this.submitted = true;
            this.loading = true;
			this.$v.$touch();

			if (!this.objData.product_id || this.objData.product_id == 0) {
				this.$notify.error('Vui lòng chọn title trước khi cập nhật.');
				return;
			}

			let data = this.objData;
			this.updateOption(data)
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
			this.getOptionDetail({ id: id }).then(response => {
				this.objData = response.data;
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
<style scoped>
.form-group input[type='file'] {
	position: relative;
	opacity: 1;
	width: 100%;
	padding: 10px;
	border: 1px solid #ccc;
	border-radius: 4px;
	resize: vertical;
}
</style>
