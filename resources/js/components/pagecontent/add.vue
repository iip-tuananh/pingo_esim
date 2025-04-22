<template>
	<div>
		<div class="row">
			<div class="col-md-8 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<div class="form-group">
							<label>Tên trang nội dung</label>
							<vs-input
								type="text"
								size="default"
								placeholder="Tên bài viết"
								class="w-100"
								v-model="objData.title[0].content"
								:class="{ 'is-invalid': submitted && $v.objData.title.$error }"
							/>
							<div v-if="submitted && !$v.objData.title.required" class="noti-err">
								Title không để trống
							</div>
							<el-button size="small" @click="showSettingLangExist('title')">
								Đa ngôn ngữ
							</el-button>
							<div class="dropLanguage" v-if="showLang.title == true">
								<div class="form-group" v-for="(item, index) in lang" :key="index">
									<label v-if="index != 0">{{ item.name }}</label>
									<vs-input
										v-if="index != 0"
										type="text"
										size="default"
										placeholder="Tên bài viết"
										class="w-100 inputlang"
										v-model="objData.title[index].content"
									/>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Nội dung</label>
							<TinyMce
								v-model="objData.content[0].content"
								:class="{ 'is-invalid': submitted && $v.objData.content.$error }"
							/>
							<div v-if="submitted && !$v.objData.content.required" class="noti-err">
								Nội dung không để trống
							</div>
							<el-button size="small" @click="showSettingLangExist('content')">
								Đa ngôn ngữ
							</el-button>
							<div class="dropLanguage" v-if="showLang.content == true">
								<div class="form-group" v-for="(item, index) in lang" :key="index">
									<label v-if="index != 0">{{ item.name }}</label>
									<TinyMce
										v-if="index != 0"
										v-model="objData.content[index].content"
									/>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Mô tả ngắn</label>
							<TinyMce
								v-model="objData.description[0].content"
								:class="{
									'is-invalid': submitted && $v.objData.description.$error,
								}"
							/>
							<div
								v-if="submitted && !$v.objData.description.required"
								class="noti-err"
							>
								Nội dung không để trống
							</div>
							<el-button size="small" @click="showSettingLangExist('description')">
								Đa ngôn ngữ
							</el-button>
							<div class="dropLanguage" v-if="showLang.description == true">
								<div class="form-group" v-for="(item, index) in lang" :key="index">
									<label v-if="index != 0">{{ item.name }}</label>
									<TinyMce
										v-if="index != 0"
										v-model="objData.description[index].content"
									/>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Ảnh đại diện</label>
							<image-upload
								v-model="objData.image"
								type="avatar"
								:title="'trang-noi-dung'"
							></image-upload>
						</div>
						<vs-button color="primary" @click="addPagecontent">Thêm mới</vs-button>
					</div>
				</div>
			</div>
			<div class="col-md-4 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<div class="form-group">
							<label>Trạng thái</label>
							<vs-select v-model="objData.status">
								<vs-select-item value="1" text="Hiện" />
								<vs-select-item value="0" text="Ẩn" />
							</vs-select>
						</div>
						<div class="form-group">
							<label>Danh mục</label>
							<vs-select v-model="objData.type">
								<vs-select-item value="ve-chung-toi" text="Về Chúng Tôi" />
								<vs-select-item
									value="ho-tro-khach-hang"
									text="Hỗ trợ khách hàng"
								/>
							</vs-select>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- content-wrapper ends -->
	</div>
</template>


<script>
import { mapActions } from 'vuex';
import TinyMce from '../_common/tinymce';
import { required } from 'vuelidate/lib/validators';
export default {
	name: 'product',
	data() {
		return {
			submitted: false,
			cate: [],
			objData: {
				title: [
					{
						lang_code: 'vi',
						content: '',
					},
					{
						lang_code: 'en-US',
						content: '',
					},
				],
				content: [
					{
						lang_code: 'vi',
						content: '',
					},
					{
						lang_code: 'en-US',
						content: '',
					},
				],
				description: [
					{
						lang_code: 'vi',
						content: '',
					},
					{
						lang_code: 'en-US',
						content: '',
					},
				],
				status: 1,
				image: '',
				type: 've-chung-toi',
			},
			showLang: {
				title: false,
				content: false,
				description: false,
			},
			lang: [],
		};
	},
	validations: {
		objData: {
			title: { required },
			description: { required },
			content: { required },
		},
	},
	components: {
		TinyMce,
	},
	computed: {},
	watch: {},
	methods: {
		...mapActions(['savePageContent', 'loadings', 'listLanguage']),
		addPagecontent() {
			this.submitted = true;
			this.$v.$touch();
			if (this.$v.$invalid) {
				return;
			} else {
				this.loadings(true);
				this.savePageContent(this.objData)
					.then(response => {
						this.loadings(false);
						this.$router.push({ name: 'pageContent' });
						this.$notify.success('Thêm trang nội dung thành công');
					})
					.catch(error => {
						this.loadings(false);
						this.$notify.error('Thêm trang nội dung thất bại');
					});
			}
		},
		listLang() {
			this.listLanguage()
				.then(response => {
					this.lang = response.data;
				})
				.catch(error => {});
		},
        showSettingLangExist(value) {
			if (value == 'content') {
				this.showLang.content = !this.showLang.content;
				this.lang.forEach((value, index) => {
					if (!this.objData.content[index]) {
						var oj = {};
						oj.lang_code = value.code;
						oj.content = '';
						this.objData.content.push(oj);
					}
				});
			}
			if (value == 'description') {
				this.showLang.description = !this.showLang.description;
				this.lang.forEach((value, index) => {
					if (!this.objData.description[index]) {
						var oj = {};
						oj.lang_code = value.code;
						oj.content = '';
						this.objData.description.push(oj);
					}
				});
			}
			if (value == 'title') {
				this.showLang.title = !this.showLang.title;
				this.lang.forEach((value, index) => {
					if (!this.objData.title[index] && value.code != this.objData.title[0].lang_code) {
						var oj = {};
						oj.lang_code = value.code;
						oj.content = '';
						this.objData.title.push(oj);
					}
				});
				console.log(this.lang);
			}
		},
	},
	mounted() {
		this.listLang();
	},
};
</script>
