var memo = new Vue({
    el: "#memo",
    components: {
        ckeditor: CKEditor.component
    },
    data: {
        errors: [],
        autosaveConfig: {
            // ne valtozasd
            enabled: false,
            timeout: null,
            // valtoztathatod
            autosaveInterval: 10, // seconds
        },
        history: null,
        editor: ClassicEditor,
        editorContent: '',
        editorConfig: {
            toolbar: {
                items: [
                    'bold',
                    'italic',
                    'link',
                    'undo',
                    'redo',
                    'BulletedList'
                ],
            },
        }
    },
    created: function() {
        this.loadHistory();
    },
    methods: {
        autosave: function() {
            if (!this.autosaveConfig.enabled) {
                return;
            }

            clearTimeout(this.autosaveConfig.timeout);

            this.autosaveConfig.timeout = setTimeout(function() {
                this.save();
            }.bind(this), this.autosaveConfig.autosaveInterval*1000);
        },
        enableAutosave: function() {
            this.autosaveConfig.enabled = true;
        },
        disableAutosave: function() {
            this.autosaveConfig.enabled = false;
            clearTimeout(this.autosaveConfig.timeout);
        },
        save: function() {
            var formData = new FormData();
            formData.append("EventMemo[content]", this.editorContent);
            formData.append(yii.getCsrfParam(), yii.getCsrfToken());

            this.$http.post(memoSaveUrl, formData).then(response => {
                if (response.body.success == 1) {
                    this.loadHistory();
                } else {
                    this.errors.push("Save unsuccesful");
                }
            }, response => {
                this.errors.push("Save unsuccesful");
            });
        },
        restore: function(content) {
            this.editorContent = content;
        },
        loadHistory: function() {
            this.errors = [];
            this.$http.get(memoHistoryUrl).then((response) => {
                if (!!response.body) {
                    this.history = response.body;
                }
            }, (response) => {
                this.errors.push("Something went wrong when tried to load history");
            });
        },
        loadData: function() {
            this.errors = [];
            this.$http.get(memoHistoryUrl).then((response) => {
                if (response.body.length > 0) {
                    this.editorContent = response.body[0].content;
                }
            }, (response) => {
                this.errors.push("Something went wrong when tried to load the memo");
            });
        }
    }
});
