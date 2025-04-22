<template>
	<div>
		<h3 class="page-title">Các câu hỏi thường gặp</h3>
		<div class="row">
			<div class="col-md-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<div class="row" v-for="(item, index) in objData" :key="index">
							<div class="col-md-12">
								<div class="font-weight-bold">Câu hỏi {{ index + 1 }}</div>
								<div class="form-group">
									<label>Câu hỏi</label>
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
										v-model="item.question[0].content"
										size="default"
										placeholder="Câu hỏi"
										class="w-100"
									/>
									<el-button
										size="small"
										@click="showSettingLangExist('question', index)"
									>
										Đa ngôn ngữ
									</el-button>
									<div class="dropLanguage" v-if="item.showLangQuestion">
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
												placeholder="Câu hỏi"
												class="w-100 inputlang"
												v-model="item.question[indexLang].content"
											/>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label>Câu trả lời</label>
									<TinyMce v-model="item.answer[0].content" :height="300" />
									<el-button
										size="small"
										@click="showSettingLangExist('answer', index)"
									>
										Đa ngôn ngữ
									</el-button>
									<div class="dropLanguage" v-if="item.showLangAnswer">
										<div
											class="form-group"
											v-for="(langItem, indexLang) in item.lang"
											:key="indexLang"
										>
											<label v-if="indexLang != 0">{{ langItem.name }}</label>
											<TinyMce
												v-if="indexLang != 0"
												v-model="item.answer[indexLang].content"
												:height="300"
											/>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label>Trạng thái</label>
									<vs-select v-model="item.status">
										<vs-select-item value="1" text="Hiển thị" />
										<vs-select-item value="0" text="Không hiển thị" />
									</vs-select>
								</div>
							</div>
							<hr style="border: 0.5px solid #04040426; width: 100%" />
						</div>
						<vs-button color="primary" @click="saveFaqs">Lưu</vs-button>
						<vs-button color="success" @click="addObjPartner">Thêm câu hỏi</vs-button>
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
					question: [
						{
							lang_code: 'vi',
							content: '',
						},
						{
							lang_code: 'en-US',
							content: '',
						},
					],
					answer: [
						{
							lang_code: 'vi',
							content: '',
						},
						{
							lang_code: 'en-US',
							content: '',
						},
					],
					status: '',
					showLangQuestion: false,
					showLangAnswer: false,
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
		...mapActions(['saveFaq', 'loadings', 'listLanguage', 'listFaq']),
		saveFaqs() {
			this.loadings(true);
			this.saveFaq({ data: this.objData })
				.then(response => {
					this.loadings(false);
					this.$notify.success('Thêm câu hỏi thành công');
				})
				.catch(error => {
					this.loadings(false);
					this.$notify.error('Thêm câu hỏi thất bại');
				});
		},
		addObjPartner() {
			this.objData.push({
				question: [
					{
						lang_code: 'vi',
						content: '',
					},
					{
						lang_code: 'en-US',
						content: '',
					},
				],
				answer: [
					{
						lang_code: 'vi',
						content: '',
					},
					{
						lang_code: 'en-US',
						content: '',
					},
				],
				status: '',
				showLangQuestion: false,
				showLangAnswer: false,
				lang: this.lang,
			});
		},
		removeObjPartner(i) {
			this.objData.splice(i, 1);
		},
		listFaqs() {
			this.loadings(true);
			this.listFaq()
				.then(response => {
					this.loadings(false);
					if (response.data.length > 0) {
						this.objData = response.data;
						this.objData = this.objData.map(item => {
							return {
								...item,
								question: JSON.parse(item.question),
								answer: JSON.parse(item.answer),
								showLangQuestion: false,
								showLangAnswer: false,
								lang: this.lang,
							};
						});
					} else {
						this.objData = [
							{
								question: [
									{
										lang_code: 'vi',
										content: '',
									},
									{
										lang_code: 'en-US',
										content: '',
									},
								],
								answer: [
									{
										lang_code: 'vi',
										content: '',
									},
									{
										lang_code: 'en-US',
										content: '',
									},
								],
								status: '',
								showLangQuestion: false,
								showLangAnswer: false,
								lang: this.lang,
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
				})
				.catch(error => {});
		},
		showSettingLangExist(value, key) {
			if (value == 'question') {
				this.objData[key].showLangQuestion = !this.objData[key].showLangQuestion;
				this.objData[key].lang.forEach((langItem, indexLang) => {
					if (!this.objData[key].question[indexLang]) {
						this.$set(this.objData[key].question, indexLang, {
							lang_code: langItem.code,
							content: '',
						});
					}
				});
			}
			if (value == 'answer') {
				this.objData[key].showLangAnswer = !this.objData[key].showLangAnswer;
				this.objData[key].lang.forEach((langItem, indexLang) => {
					if (
						!this.objData[key].answer[indexLang] &&
						langItem.code !== this.objData[key].answer[0]?.lang_code
					) {
						this.$set(this.objData[key].answer, indexLang, {
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
		this.listFaqs();
	},
};
</script>
