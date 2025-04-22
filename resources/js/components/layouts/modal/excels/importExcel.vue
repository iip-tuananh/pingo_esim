<template>
    <div>
        <form class="forms-sample" enctype="multipart/form-data">
            <div class="form-group">
                <label for="file">Chọn file</label>
                <input type="file" class="form-control" id="file" @change="handleFileUpload" placeholder="File"/>
                <span v-if="submitted && $v.objData.file.$error" class="text-danger">Vui lòng chọn file</span>
            </div>
            <div class="result-import" v-if="showResultImport">
                <div><b><u>Chi tiết:</u></b></div>
                <div class="text-success"> Đã import thành công: {{ resultImport.import || 0 }} bản ghi.</div>
                <div class="text-warning"> Đã bỏ qua: {{ resultImport.skip || 0 }} bản ghi.</div>
                <div class="text-danger">
                    <div data-toggle="collapse" data-target="#error-details" class="cursor-pointer collapsed">
                        Không hợp lệ: {{ resultImport.invalid_rows.length || 0 }} bản ghi
                        <i class="fa fa-caret-down" aria-hidden="true"></i>
                    </div>
                    <div class="ml-2 collapse" id="error-details">
                        <div v-for="(row, index) in resultImport.invalid_rows" :key="index">
                            - Dòng {{ row.index }}: {{ row.detail }}.
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group mt-3">
                <vs-button
                color="success"
                type="gradient"
                class="mr-left-45"
                @click="handleSubmit()"
                >Import Excel</vs-button>
            </div>
        </form>
    </div>
</template>

<script>
import { required, email, minLength, sameAs } from "vuelidate/lib/validators";
import { mapActions, mapGetters } from "vuex";
export default {
  data() {
    return {
      objData: {
            file: '',
      },
      submitted: false,
      resultImport: [],
      showResultImport: false,
    };
  },
  computed:{

  },
  validations: {
    objData: {
      file: { required },
    }
  },
  methods: {
    ...mapActions(["saveImportExcel", "loadings"]),
    handleFileUpload(event) {
        // Lấy file từ input
        const file = event.target.files[0];

        if (file) {
        this.objData.file = file;
        console.log("File đã chọn:", this.objData.file);
        }
    },
    handleSubmit() {
      this.submitted = true;
      this.$v.$touch();

        if (!this.objData.file) {
            this.$notify.error("Vui lòng chọn file trước khi import.");
            return;
        }

        if (this.$v.$invalid) {
            this.$notify.error("Vui lòng nhập đầy đủ thông tin.");
            return;
        }
        let formData = new FormData();
        formData.append("file", this.objData.file); // Đúng cách
        console.log("formData", formData);

        this.saveImportExcel(formData) // Truyền formData thay vì objData.file
            .then(response => {
                if(response.success) {
                    this.resultImport = response.details;
                    this.showResultImport = true;
                    this.$notify.success("Thêm mới thành công");
                    // this.$emit("closePopup", false);
                } else {
                    this.$notify.error("Có lỗi xảy ra. Vui lòng thử lại!");
                }
            })
            .catch(error => {
            this.$notify.error("Có lỗi xảy ra. Vui lòng thử lại!");
            });
    },
    }
};
</script>
<style scoped>
    .form-group input[type=file] {
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
