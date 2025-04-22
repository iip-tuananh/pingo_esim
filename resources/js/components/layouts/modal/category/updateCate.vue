<template>
	<div>
		<form class="forms-sample" enctype="multipart/form-data">
			<div class="form-group">
				<label for="file">Chọn phân loại <span class="text-danger">*</span></label>
				<vs-select
					class="selectExample w-100"
					v-model="objData.category"
					placeholder="Danh mục"
				>
					<vs-select-item value="0" text="Không danh mục" />
					<vs-select-item
						:value="item.id"
						:text="JSON.parse(item.name)[0].content"
						v-for="(item, index) in categories"
						:key="'f' + index"
					/>
				</vs-select>
				<span v-if="submitted && $v.objData.category.$error" class="text-danger">
					Vui lòng chọn phân loại
				</span>
			</div>
			<div class="form-group mt-3">
				<vs-button
					color="success"
					type="gradient"
					class="mr-left-45"
					@click="handleSubmit()"
				>
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
				category: '',
			},
			categories: [],
			submitted: false,
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
			category: { required },
		},
	},
	methods: {
		...mapActions(['addCateMultiple', 'loadings', 'listCate']),
		handleSubmit() {
			this.submitted = true;
			this.$v.$touch();

			if (!this.objData.category || this.objData.category == 0) {
				this.$notify.error('Vui lòng chọn phân loại trước khi cập nhật.');
				return;
			}

			let data = {
				arrayItemChecked: this.arrayItemChecked,
				category: this.objData.category,
			};
			this.addCateMultiple(data)
				.then(response => {
					if (response.success) {
						this.$notify.success('Thêm phân loại thành công');
						this.$emit('closePopup', false);
						this.$emit('updateList');
					} else {
						this.$notify.error('Có lỗi xảy ra. Vui lòng thử lại!');
					}
				})
				.catch(error => {
					this.$notify.error('Có lỗi xảy ra. Vui lòng thử lại!');
				});
		},
        callListCate() {
            this.listCate().then(response => {
				this.categories = response.data;
			});
            this.submitted = false;
		},
	},
	mounted() {
		// this.callListCate();
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
