var participates = new Vue({
    el: "#participates",
    data: {
        data: {
            items: null,
            users: [],
        },
        form: {
            user_id: 0,
            days: [],
            role: ''
        },
        errors: [],
    },
    created: function() {
        this.loadData();
    },
    methods: {
        loadData: function(page) {
            this.errors = [];
            this.$http.get(participatesListUrl).then((response) => {
                if (!!response.body) {
                    this.data = response.body;
                }
            }, (response) => {
                this.errors.push("Something went wrong when tried to load participates");
            });
        },
        deleteParticipate: function(id) {
            if (confirm("Are you sure?")) {
                var formData = new FormData();
                formData.append("user_id", id);
                formData.append(yii.getCsrfParam(), yii.getCsrfToken());

                this.$http.post(participateDeleteUrl, formData).then(response => {
                    if (response.body.success == 1) {
                        this.loadData();
                        this.page = 1;
                    } else {
                        this.errors.push("Delete unsuccesful");
                    }
                }, response => {
                    this.errors.push("Delete unsuccesful");
                });
            }
        },
    }
});