<template>
	<div>
		<form class="forms-sample" enctype="multipart/form-data">
			<div class="form-group">
				<label for="file">Chọn loại tiền <span class="text-danger">*</span></label>
				<vs-select
					class="selectExample w-100"
					v-model="objData.currency"
					placeholder="Loại tiền"
				>
					<vs-select-item value="vnd" text="Việt Nam Đồng" />
					<vs-select-item value="usd" text="USD" />
				</vs-select>
				<span v-if="submitted && $v.objData.currency.$error" class="text-danger">
					Vui lòng chọn loại tiền
				</span>
			</div>
			<div class="form-group">
				<label for="file">Giá <span v-if="objData.currency == 'vnd'">(VNĐ)</span> <span v-if="objData.currency == 'usd'">(USD)</span> <span class="text-danger">*</span></label>
				<vs-input
					class="selectExample w-100"
					v-model="objData.price"
					placeholder="Giá"
				>
				</vs-input>
				<span v-if="submitted && $v.objData.price.$error" class="text-danger">
					Vui lòng nhập giá
				</span>
			</div>
			<div class="form-group mt-3">
				<vs-button
					color="success"
					type="gradient"
					class="mr-left-45"
					@click="handleSubmit()"
                    :disabled="loadings"
				>
                    <i class="fa fa-spinner fa-spin" v-if="loadings"></i>
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
				currency: '',
				price: '',
			},
			categories: [],
			submitted: false,
			loadings: false,
			resultImport: [],
			showResultImport: false,
		};
	},
	props: {
		arrayItemChecked: {
			type: Array,
			default: () => [],
		},
	},
	computed: {},
	validations: {
		objData: {
			currency: { required },
			price: { required },
		},
	},
	methods: {
		...mapActions(['updatePriceMultiple', 'loadings']),
		handleSubmit() {
			this.submitted = true;
			this.loadings = true;
			this.$v.$touch();

			if (!this.objData.currency || this.objData.currency == '') {
				this.$notify.error('Vui lòng chọn phân loại trước khi cập nhật.');
				return;
			}

			let data = {
				arrayItemChecked: this.arrayItemChecked,
				price: this.objData.price,
				currency: this.objData.currency,
			};
			this.updatePriceMultiple(data)
				.then(response => {
					if (response.success) {
						this.$notify.success('Cập nhật giá thành công');
						this.$emit('closePopup', false);
						this.$emit('updateList');
                        this.objData.price = '';
                        this.objData.currency = '';
                        this.loadings = false;
                        this.submitted = false;
					} else {
						this.$notify.error('Có lỗi xảy ra. Vui lòng thử lại!');
                        this.loadings = false;
					}
				})
				.catch(error => {
					this.$notify.error('Có lỗi xảy ra. Vui lòng thử lại!');
                    this.loadings = false;
				});
		},
        callResetPopup() {
            this.submitted = false;
        }
	},
	mounted() {
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
label {
	font-weight: 500;
	font-size: 18px;
}
</style>
