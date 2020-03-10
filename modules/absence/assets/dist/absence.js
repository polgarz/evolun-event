var absences = new Vue({
    el: "#absences",
    data: {
        absences: null,
        kidList: null,
        form: {
            'kid': null,
            'reason': '',
        },
        errors: [],
    },
    created: function () {
        this.loadData();
    },
    methods: {
        singleSelectLabel: function(option) {
            return option.name + " (" + option.family + ")";
        },
        deleteKid: function (id) {
            if (confirm("Are you sure?")) {
                var formData = new FormData();
                formData.append("kid_id", id);
                formData.append(yii.getCsrfParam(), yii.getCsrfToken());

                this.$http.post(deleteAbsenceUrl, formData).then(response => {
                    if (response.body.success == 1) {
                        this.loadData();
                    } else {
                        this.errors.push("Delete unsuccesful");
                    }
                }, response => {
                    this.errors.push("Delete unsuccesful");
                });
            }
        },
        newAbsence: function (event) {
            this.errors = [];

            var formData = new FormData();
            formData.append("Absence[kid_id]", this.form.kid.id);
            formData.append("Absence[reason]", this.form.reason);
            formData.append(yii.getCsrfParam(), yii.getCsrfToken());

            this.$http.post(newAbsenceUrl, formData).then(response => {
                if (response.body.success == 1) {
                    event.target.reset();
                    this.form.kid = null;
                    this.form.reason = '';
                    this.loadData();
                } else {
                    this.errors = response.body.error;
                }
            }, response => {
                this.errors.push("Send unsuccesful");
            });
        },
        loadData: function () {
            this.errors = [];
            this.$http.get(absenceListUrl).then((response) => {
                if (!!response.body) {
                    this.absences = response.body;
                }
            }, (response) => {
                this.errors.push("Something went wrong when tried to load absences");
            });

            this.$http.get(kidListUrl).then((response) => {
                if (!!response.body) {
                    this.kidList = response.body;
                }
            }, (response) => {
                this.errors.push("Something went wrong when tried to load kids");
            });
        }
    }
});