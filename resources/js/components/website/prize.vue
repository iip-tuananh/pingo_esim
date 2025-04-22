<template>
	<div>
		<h3 class="page-title">Các hoạt động eSIM</h3>
		<div class="row">
			<div class="col-md-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<div class="row" v-for="(item, index) in objData" :key="index">
							<div class="col-md-3">
								<div class="form-group">
									<image-upload
										:oldImage="item.image"
										type="avatar"
										v-model="item.image"
										:title="'thu-vien-'"
									></image-upload>
								</div>
							</div>
							<div class="col-md-9">
								<div class="font-weight-bold">Bước {{ index + 1 }}</div>
								<div class="form-group">
									<label>Tiêu đề</label>
									<label
										style="float: right; cursor: pointer"
										title="Xóa ảnh"
										v-if="index != 0"
										@click="removeObjPartner(index)"
									>
										<vs-icon icon="clear"></vs-icon>
									</label>
									<vs-input
										type="text"
										v-model="item.name[0].content"
										size="default"
										placeholder="Tiêu đề"
										class="w-100"
									/>
									<el-button
										size="small"
										@click="showSettingLangExist('name', index)"
									>
										Đa ngôn ngữ
									</el-button>
									<div class="dropLanguage" v-if="item.showLangName">
										<div
											class="form-group"
											v-for="(langItem, indexLang) in item.lang"
											:key="indexLang"
										>
											<label v-if="indexLang != 0">{{ langItem.name }}</label>
											<vs-input
												v-if="indexLang != 0"
												type="text"
												size="default"
												placeholder="Tiêu đề"
												class="w-100 inputlang"
												v-model="item.name[indexLang].content"
											/>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label>Nội dung</label>
									<TinyMce v-model="item.content[0].content" :height="300" />
									<el-button
										size="small"
										@click="showSettingLangExist('content', index)"
									>
										Đa ngôn ngữ
									</el-button>
									<div class="dropLanguage" v-if="item.showLangContent">
										<div
											class="form-group"
											v-for="(langItem, indexLang) in item.lang"
											:key="indexLang"
										>
											<label v-if="indexLang != 0">{{ langItem.name }}</label>
											<TinyMce
												v-if="indexLang != 0"
												v-model="item.content[indexLang].content"
												:height="300"
											/>
										</div>
									</div>
								</div>
								<!-- <div class="form-group">
									<label>Link</label>
									<vs-input
										type="text"
										v-model="item.link"
										size="default"
										placeholder="Link liên kết với ảnh (bỏ trống nếu không có)"
										class="w-100"
									/>
								</div> -->
								<div class="form-group">
									<label>Trạng thái hiển thị trang chủ</label>
									<vs-select v-model="item.status">
										<vs-select-item value="1" text="Hiển thị trang chủ" />
										<vs-select-item value="0" text="Không hiển thị trang chủ" />
									</vs-select>
								</div>
							</div>
							<hr style="border: 0.5px solid #04040426; width: 100%" />
						</div>
						<vs-button color="primary" @click="savePrizes">Lưu</vs-button>
						<vs-button color="success" @click="addObjPartner">Thêm bước</vs-button>
					</div>
				</div>
			</div>
		</div>
		<!-- content-wrapper ends -->
	</div>
</template>


<script>
import { mapActions } from 'vuex';
import { required } from 'vuelidate/lib/validators';
import TinyMce from '../_common/tinymce';

export default {
	name: 'prize',
	data() {
		return {
			objData: [
				{
					name: [
						{
							lang_code: 'vi',
							content: '',
						},
						{
							lang_code: 'en-US',
							content: '',
						},
					],
					image: '',
					status: '',
					link: '',
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
					showLangName: false,
					showLangContent: false,
					lang: [],
				},
			],
			lang: [],
		};
	},
	components: {
		TinyMce,
	},
	computed: {},
	watch: {},
	methods: {
		...mapActions(['savePrize', 'loadings', 'listLanguage', 'listPrize']),
		savePrizes() {
			this.loadings(true);
			this.savePrize({ data: this.objData })
				.then(response => {
					this.loadings(false);
					this.$notify.success('Thêm bước hoạt động thành công');
				})
				.catch(error => {
					this.loadings(false);
					this.$notify.error('Thêm bước hoạt động thất bại');
				});
		},
		addObjPartner() {
			this.objData.push({
				name: [
					{
						lang_code: 'vi',
						content: '',
					},
					{
						lang_code: 'en-US',
						content: '',
					},
				],
				image: '',
				status: '',
				link: '',
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
				showLangName: false,
				showLangContent: false,
				lang: this.lang,
			});
		},
		removeObjPartner(i) {
			this.objData.splice(i, 1);
		},
		listBanners() {
			this.loadings(true);
			this.listPrize()
				.then(response => {
					this.loadings(false);
					if (response.data.length > 0) {
						this.objData = response.data;
						this.objData = this.objData.map(item => {
							return {
								...item,
								name: JSON.parse(item.name),
								content: JSON.parse(item.content),
								showLangName: false,
								showLangContent: false,
								lang: this.lang,
							};
						});
					} else {
						this.objData = [
							{
								name: [
									{
										lang_code: 'vi',
										content: '',
									},
									{
										lang_code: 'en-US',
										content: '',
									},
								],
								image: '',
								status: '',
								link: '',
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
								lang: this.lang,
								showLangName: false,
								showLangContent: false,
							},
						];
					}
				})
				.catch(error => {
					this.loadings(false);
				});
		},
		listLang() {
			this.listLanguage()
				.then(response => {
					this.lang = response.data;
					this.objData.forEach(item => {
						item.lang = this.lang;
					});
					console.log(this.objData);
				})
				.catch(error => {});
		},
		showSettingLangExist(value, key) {
			if (value == 'content') {
				this.objData[key].showLangContent = !this.objData[key].showLangContent;
				this.objData[key].lang.forEach((langItem, indexLang) => {
					if (!this.objData[key].content[indexLang]) {
						this.$set(this.objData[key].content, indexLang, {
							lang_code: langItem.code,
							content: '',
						});
					}
				});
			}
			if (value == 'name') {
				this.objData[key].showLangName = !this.objData[key].showLangName;
				this.objData[key].lang.forEach((langItem, indexLang) => {
					if (
						!this.objData[key].name[indexLang] &&
						langItem.code !== this.objData[key].name[0]?.lang_code
					) {
						this.$set(this.objData[key].name, indexLang, {
							lang_code: langItem.code,
							content: '',
						});
					}
				});
			}
		},
	},
	mounted() {
		this.listLang();
		this.listBanners();
	},
};
</script>
