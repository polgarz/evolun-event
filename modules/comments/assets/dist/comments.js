var comments = new Vue({
    el: "#comments",
    data: {
        comments: null,
        form: {
            'comment': ''
        },
        errors: [],
    },
    created: function() {
        this.loadData();
    },
    methods: {
        deleteComment: function(id) {
            if (confirm("Biztosan törlöd ezt a megjegyzést?")) {
                var formData = new FormData();
                formData.append("comment_id", id);
                formData.append(yii.getCsrfParam(), yii.getCsrfToken());

                this.$http.post(commentDeleteUrl, formData).then(response => {
                    if (response.body.success == 1) {
                        this.loadData();
                    } else {
                        this.errors.push("Sikertelen törlés");
                    }
                }, response => {
                    this.errors.push("Sikertelen törlés");
                });
            }
        },
        newComment: function(event) {
            this.errors = [];

            var formData = new FormData();
            formData.append("EventComment[comment]", this.form.comment);
            formData.append(yii.getCsrfParam(), yii.getCsrfToken());

            this.$http.post(newCommentUrl, formData).then(response => {
                if (response.body.success == 1) {
                    event.target.reset();
                    this.form.comment = '';
                    this.loadData();
                } else {
                    this.errors = response.body.error;
                }
            }, response => {
                this.errors.push("Sikertelen küldés");
            });
        },
        loadData: function() {
            this.errors = [];
            this.$http.get(commentsListUrl).then((response) => {
                if (!!response.body) {
                    this.comments = response.body;
                }
            }, (response) => {
                this.errors.push("Hiba a megjegyzések betöltése közben");
            });
        }
    }
});